
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
 * getCoureurById
 * @param string $id
 * @return array tableau associatif associé à un coureur dans la base de donnée
 */
function getCoureurById(string $id) : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g10_coureur WHERE coureur_id = $1";

    /*  préparer la requête avec la fonction pg_prepare(...)  */
    pg_prepare($ptrDB,"reqPrepSelectById", $query);

    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery))
        /* récupérer le tableau associatif avec pg_fetch_assoc dans $resu */
         $resu=pg_fetch_assoc( $ptrQuery);
    if (empty($resu))
         $resu =  array("message" => "Identifiant de g10_coureur non valide : $id");

    pg_free_result($ptrQuery);    //libérer les ressources avec pg_free_result()  
    pg_close($ptrDB);   //   fermer la connexion avec pg_close()  
    return $resu;
}

/**
 * getAllCoureur
 * @param void
 * @return array tableau associatif contenant touts les coureurs de la base de donnée 
 */
function getAllCoureur() : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g10_coureur";
    pg_prepare($ptrDB, "reqPrepSelectAll", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectAll", array());

    $resu = array();

    if (isset($ptrQuery)) {
        /*  traitement des lignes du résultats une à une  */
        while($ligne = pg_fetch_assoc($ptrQuery))
          array_push($resu, $ligne);
    }
    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 *  insertCoureur
 * @param $g10_coureur 
 * @return array tableau associatif contenant le nouveau coureur enregistré dans la base de données.
 */
function insertCoureur(array $g10_coureur) : array {
    $ptrDB = connexion();
    
     // Vérification si l'ID existe déjà
    $reqSql = "SELECT coureur_id FROM g10_coureur WHERE coureur_id = $1";
    $reqResult = pg_prepare($ptrDB, "req_coureur_id", $reqSql);
    $reqResult = pg_execute($ptrDB, "req_coureur_id", array($g10_coureur['coureur_id']));
    
    // Si l'ID existe déjà
    if (pg_num_rows($reqResult) > 0) {
        pg_free_result($reqResult);
        pg_close($ptrDB);
        return ["message" => "La coureur avec l'ID " . $g10_coureur['coureur_id'] . " existe déjà."];
    }

    /*  préparation et exécution de la requête INSERT ici */
    $requette = 'INSERT INTO g10_coureur(coureur_id,coureur_nom,coureur_prenom,nationalite,nom_equipe,date_naissance) VALUES ($1,$2,$3,$4,$5,$6)';

    pg_prepare($ptrDB, "requettePrepaInsert", $requette);
    $ptrQuery = pg_execute($ptrDB, "requettePrepaInsert", array($g10_coureur['coureur_id'],
                                                                $g10_coureur['coureur_nom'],
                                                                $g10_coureur['coureur_prenom'],
                                                                $g10_coureur['nationalite'],
                                                                $g10_coureur['nom_equipe'],
                                                                $g10_coureur['date_naissance']));

    if($ptrQuery)
      return getCoureurById($g10_coureur['coureur_id']);
    return array();
}

/**
 *  updateCoureur
 * @param $g10_coureur 
 * @return array tableau associatif contenant le coureur met à jour dans la base de données.
 */
function updateCoureur(array $g10_coureur): array  {
    $ptrDB = connexion();

    /*  préparation et exécution de la requête UPDATE  */
    $requette = 'UPDATE g10_coureur SET coureur_nom = $1,coureur_prenom = $2,nationalite = $3, nom_equipe = $4,date_naissance=$5 WHERE
     coureur_id = $6';

     pg_prepare($ptrDB, "requettePrepaUpdate", $requette);
     $ptrQuery = pg_execute($ptrDB, "requettePrepaUpdate", array($g10_coureur['coureur_nom'],$g10_coureur['coureur_prenom'],$g10_coureur['nationalite'],
    $g10_coureur['nom_equipe'],$g10_coureur['date_naissance'],$g10_coureur['coureur_id']));

     if($ptrQuery)
        return getCoureurById($g10_coureur['coureur_id']);
     return array();
}

/**
 *  deleteCoureur
 * @param $id
 * @return void.
 * supprime un coureur dans la base de données.
 */

function deleteCoureur(string $id): void {
   // Connexion à la base de données
   $ptrDB = connexion();

   // Suppression des classements associés au coureur
   $requetteClasser = "DELETE FROM g10_classer WHERE coureur_id = $1";
   pg_prepare($ptrDB, "requettePrepaClasser", $requetteClasser);
   $ptrQueryClasser = pg_execute($ptrDB, "requettePrepaClasser", array($id));

   // Suppression du coureur
   $requetteCoureur = "DELETE FROM g10_coureur WHERE coureur_id = $1";
   pg_prepare($ptrDB, "requettePrepaCoureur", $requetteCoureur);
   $ptrQueryCoureur = pg_execute($ptrDB, "requettePrepaCoureur", array($id));

   // Vérifier si les deux requêtes ont été exécutées avec succès
   if ($ptrQueryClasser && $ptrQueryCoureur) {
       echo("Le coureur et ses classements ont été supprimés avec succès.<br/>");
   } else {
       // Si l'une des deux requêtes échoue, afficher un message d'erreur
       echo("La suppression a échoué. Aucun changement n'a été effectué.<br/>");
   }
}  
      
/****************************************************    AFFICHAGE    *****************************************************************/

//afficher le tableau associatif retourné par getClasserById sous forme d'un tableau 
function afficherTableauSimpleC($tabAssoc) {

    // Vérifie si le tableau contient un message d'erreur
     if (isset($tabAssoc['message'])) {
        echo "<p>" . ($tabAssoc['message']) . "</p>";
        return;
    }

    // Commence le tableau HTML
    echo "<table border='1'>";
    echo "<tr>
            <th>coureur_id </th>
            <th> coureur_nom</th>
            <th>coureur_prenom</th>
            <th>nationalite ID</th>
            <th>nom_equipe ID</th>
            <th>date_naissance ID</th>
          </tr>";  // Entêtes du tableau

    // Affiche les données du seul enregistrement
    echo "<tr>";  // Début de la ligne
    echo "<td>" . $tabAssoc['coureur_id'] . "</td>"; 
    echo "<td>" . $tabAssoc['coureur_nom'] . "</td>";  
    echo "<td>" . $tabAssoc['coureur_prenom']. "</td>";  
    echo "<td>" . $tabAssoc['nationalite'] . "</td>";  
    echo "<td>" . $tabAssoc['nom_equipe']. "</td>"; 
    echo "<td>" . $tabAssoc['date_naissance']. "</td>";  
    echo "</tr>";  // Fin de la ligne

    // Fin du tableau HTML
    echo "</table>";
}


//afficher le tableau associatif retourné par getAllClasser sous forme d'un tableau 
function afficherTableauC($tabAssoc) {

    if (empty($tabAssoc)) {
        echo "<p>Aucun résultat trouvé.</p>";
        return;
    }

    echo "<table border='1' >";
    echo "<tr>
          <th>Num coureur_id</th>
    	  <th> coureur_nom</th>
   	  <th>coureur_prenom</th>
   	  <th>nationalite ID</th>
          <th>nom_equipe ID</th>
          <th>date_naissance ID</th>
          </tr>";

   foreach ($tabAssoc as $valeur) {  
   
      echo "<tr>";  // Début de la ligne
    echo "<td>" . $valeur['coureur_id'] . "</td>"; 
    echo "<td>" . $valeur['coureur_nom'] . "</td>";  
    echo "<td>" . $valeur['coureur_prenom']. "</td>";  
    echo "<td>" . $valeur['nationalite'] . "</td>";  
    echo "<td>" . $valeur['nom_equipe']. "</td>"; 
    echo "<td>" . $valeur['date_naissance']. "</td>";  
    echo "</tr>";  // Fin de la ligne
    
}
  
    echo "</table>";
}
      



?>
