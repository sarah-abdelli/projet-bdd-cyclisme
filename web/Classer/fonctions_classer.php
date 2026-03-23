<?php


require_once __DIR__ . '/../monEnv.php';



// Afficher les fonctions définies par l'utilisateur
$functions = get_defined_functions();

// Vérifier si la fonction 'connexion' est dans la liste des fonctions définies par l'utilisateur
if (!(in_array('connexion', $functions['user']))) {
   
   function connexion() {
       $strConnex = "host={$_ENV['dbHost']} dbname={$_ENV['dbName']} user={$_ENV['dbUser']} password={$_ENV['dbPasswd']}";
       return pg_connect($strConnex);
   }
}

/**
 * getClasserById
 * @param string $id_coureur $id_edition 
 * @return array tableau associatif associé à un classement d'un coureur
 */
 
 
function getClasserById(string $id_coureur,string $id_edition) : array {

    $ptrDB = connexion();
    
    $query = "SELECT * FROM g10_classer WHERE coureur_id = $1  AND edition_id=$2 ";
    pg_prepare($ptrDB,"reqPrepSelectById", $query);
    
    $params = array($id_coureur, $id_edition );
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", $params);

    if (isset($ptrQuery))
	$resu=pg_fetch_assoc( $ptrQuery);
    if (empty($resu))
        $resu =  array("message" => "Identifiant de g10_classer non valide :  $id_coureur");

     pg_free_result($ptrQuery);// Libère la mémoire allouée pour le résultat de la requête
     pg_close($ptrDB);// Ferme la connexion à la base de données PostgreSQL

     return $resu;
}

/**
 * getAllClasser
 * @param void
 * @return array tableau associatif contenant touts les classements des coureurs 
 */

function getAllClasser() : array {
        
   
    $ptrDB = connexion();
    
    $query = "SELECT * FROM g10_classer";
    pg_prepare($ptrDB, "reqPrepSelectAll", $query);
    
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectAll", array());

    $resu = array();

    if (isset($ptrQuery)) {
	while($ligne = pg_fetch_assoc($ptrQuery))
        array_push($resu, $ligne);
    }
    
    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 *  insertClasser
 * @param $g10_classer 
 * @return array tableau associatif contenant le nouveau classement enregistré dans la base de données.
 */

function insertClasser(array $g10_classer) : array {
    
    $ptrDB = connexion();
    
    
      // Vérification si l'ID existe déjà

     $reqSql = "SELECT edition_id, coureur_id FROM g10_classer WHERE edition_id = $1 AND coureur_id = $2";
    $reqResult = pg_prepare($ptrDB, "req_edition_id", $reqSql);
    $reqResult = pg_execute($ptrDB, "req_edition_id", array($g10_classer['edition_id'] , $g10_classer['coureur_id']));
     

    if (pg_num_rows($reqResult) > 0) {
        pg_free_result($reqResult);
        pg_close($ptrDB);
        return ["message" => "Le classement  avec edition_id:  " . $g10_classer['edition_id'] ."et coureur_id: ". $g10_classer['coureur_id'] . " existe déjà."];
    }

    $requette = 'INSERT INTO g10_classer(num_classement,distance_parcourue,temps,coureur_id,edition_id) VALUES ($1,$2,$3,$4,$5)';
    pg_prepare($ptrDB, "requettePrepaInsert", $requette);
    $ptrQuery = pg_execute($ptrDB, "requettePrepaInsert", array(
    							$g10_classer['num_classement'],
    							$g10_classer['distance_parcourue'],
    							$g10_classer['temps'],
    							$g10_classer['coureur_id'],
    							$g10_classer['edition_id'])
    							);

    if($ptrQuery)
      return getClasserById($g10_classer['coureur_id'],$g10_classer['edition_id']);
      
    return array();
}

/**
 *  updateClasser
 * @param $g10_classer 
 * @return array tableau associatif contenant le classement met à jour dans la base de données.
 */
function updateClasser(array $g10_classer): array  {
   
    $ptrDB = connexion();
    
    $requette = 'UPDATE g10_classer SET num_classement = $1,
                                       distance_parcourue = $2,
                                       temps=$3 
                                       WHERE coureur_id = $4 AND edition_id=$5' ;

     pg_prepare($ptrDB, "requettePrepaUpdate", $requette);
     $ptrQuery = pg_execute($ptrDB, "requettePrepaUpdate",
      array(
     $g10_classer['num_classement'],
     $g10_classer['distance_parcourue'],
     $g10_classer['temps'],
     $g10_classer['coureur_id'],
     $g10_classer['edition_id'])
     );

     if($ptrQuery)
        return getClasserById($g10_classer['coureur_id'],$g10_classer['edition_id']);
    
     return array();
}

/**
 *  deleteClasser
 * @param $id_coureur , $id_edition
 * @return void.
 * supprime un classement dans la base de données.
 */

function deleteClasser(string $coureur_id, string $edition_id): void {
   $ptrDB = connexion();

   // Suppression des classements associés au coureur et à l'édition
   $requeteClasser = "DELETE FROM g10_classer WHERE coureur_id = $1 AND edition_id = $2";
   pg_prepare($ptrDB, "requetePrepaClasser", $requeteClasser);
   $ptrQueryClasser = pg_execute($ptrDB, "requetePrepaClasser", array($coureur_id, $edition_id));

   // Vérification si la suppression du classement a réussi
   if ($ptrQueryClasser) {
       echo("Le classement du coureur de l'édition a été supprimé avec succès.<br/>");

       // Suppression des coureurs orphelins (coureur qui n'a plus de relations dans g10_classer)
       $requeteCoureur = "DELETE FROM g10_coureur WHERE coureur_id NOT IN (
           SELECT DISTINCT coureur_id FROM g10_classer
       )";
       pg_prepare($ptrDB, "requetePrepaCoureur", $requeteCoureur);
       $ptrQueryCoureur = pg_execute($ptrDB, "requetePrepaCoureur", array());

       // Suppression des éditions orphelines (édition qui n'a plus de relations dans g10_classer)
       $requeteEdition = "DELETE FROM g10_edition WHERE edition_id NOT IN (
           SELECT DISTINCT edition_id FROM g10_classer
       )";
       pg_prepare($ptrDB, "requetePrepaEdition", $requeteEdition);
       $ptrQueryEdition = pg_execute($ptrDB, "requetePrepaEdition", array());

       // Vérification du nombre de coureurs supprimés
       $nbCoureursSupprimes = pg_affected_rows($ptrQueryCoureur);

       // Vérification du nombre d'éditions supprimées
       $nbEditionSupprimes = pg_affected_rows($ptrQueryEdition);

       // Vérification de la suppression des éditions
      
       }}


/****************************************************    AFFICHAGE    *****************************************************************/
//afficher le tableau associatif retourné par getClasserById sous forme d'un tableau 
function afficherTableauSimple($tabAssoc) {

    // Vérifie si le tableau contient un message d'erreur
     if (isset($tabAssoc['message'])) {
        echo "<p>" . ($tabAssoc['message']) . "</p>";
        return;
    }

    // Commence le tableau HTML
    echo "<table border='1'>";
    echo "<tr>
            <th>Num Classement</th>
            <th>Distance Parcourue</th>
            <th>Temps</th>
            <th>Coureur ID</th>
            <th>Édition ID</th>
          </tr>";  // Entêtes du tableau

    // Affiche les données du seul enregistrement
    echo "<tr>";  // Début de la ligne
    echo "<td>" . $tabAssoc['num_classement'] . "</td>";  
    echo "<td>" . $tabAssoc['distance_parcourue']. "</td>";  
    echo "<td>" . $tabAssoc['temps'] . "</td>";  
    echo "<td>" . $tabAssoc['coureur_id']. "</td>"; 
    echo "<td>" . $tabAssoc['edition_id']. "</td>";  
    echo "</tr>";  // Fin de la ligne

    // Fin du tableau HTML
    echo "</table>";
}


//afficher le tableau associatif retourné par getAllClasser sous forme d'un tableau 
function afficherTableau($tabAssoc) {

    if (empty($tabAssoc)) {
        echo "<p>Aucun résultat trouvé.</p>";
        return;
    }

    echo "<table border='1' >";
    echo "<tr>
          <th>Num Classement</th>
    	  <th>Distance Parcourue</th>
   	  <th>Temps</th>
   	  <th>Coureur ID</th>
          <th>Édition ID</th>
          </tr>";

   foreach ($tabAssoc as $valeur) {  
   
    echo "<tr>";
    echo "<td>" . $valeur['num_classement'] . "</td>";
    echo "<td>" . $valeur['distance_parcourue'] . "</td>";
    echo "<td>" . $valeur['temps']. "</td>";
    echo "<td>" . $valeur['coureur_id']. "</td>";
    echo "<td>" . $valeur['edition_id'] . "</td>";
    echo "</tr>";
    
    
}
  
    echo "</table>";
}

?>
