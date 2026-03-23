
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
 * getEditionById
 * @param string $id
 * @return array tableau associatif associé à une edition
 
 */
function getEditionById(string $id) : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g10_edition WHERE edition_id = $1";

    /*  préparer la requête avec la fonction pg_prepare(...) ici */
    pg_prepare($ptrDB,"reqPrepSelectById", $query);

    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery))
        /* récupérer le tableau associatif avec pg_fetch_assoc dans $resu */
         $resu=pg_fetch_assoc( $ptrQuery);
    if (empty($resu))
            $resu =  array("message" => "Identifiant de g10_edition non valide : $id");

    pg_free_result($ptrQuery);    // libérer les ressources avec pg_free_result() 
    pg_close($ptrDB);      // fermer la connexion avec pg_close() 
    return $resu;
}

/**
 * getAllEdition
 * @param void
 * @return array tableau associatif contenant toutes les editions de la base de donnée 
 */
function getAllEdition() : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g10_edition";
    pg_prepare($ptrDB, "reqPrepSelectAll", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectAll", array());

    $resu = array();

    if (isset($ptrQuery)) {
        /*  traitement des lignes du résultats une à une ici */
        while($ligne = pg_fetch_assoc($ptrQuery))
          array_push($resu, $ligne);
    }
    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 *  insertEdition
 * @param $g10_edition 
 * @return array tableau associatif contenant la nouvelle edition enregistrée dans la base de données.
 */
function insertEdition(array $g10_edition) : array {
    $ptrDB = connexion();


     // Vérification si l'ID existe déjà
    $reqSql = "SELECT edition_id FROM g10_edition WHERE edition_id = $1";
    $reqResult = pg_prepare($ptrDB, "req_edition_id", $reqSql);
    $reqResult = pg_execute($ptrDB, "req_edition_id", array($g10_edition['edition_id']));
     

    if (pg_num_rows($reqResult) > 0) {
        pg_free_result($reqResult);
        pg_close($ptrDB);
        return ["message" => "L'edition avec l'ID " . $g10_edition['edition_id'] . " existe déjà."];
    }
   
    

     
    /*  préparation et exécution de la requête INSERT ici */
    $requette = 'INSERT INTO g10_edition(edition_id,edi_datedebut,edi_datefin,edi_villedebut,edi_villefin,course_id) VALUES ($1,$2,$3,$4,$5,$6)';

    pg_prepare($ptrDB, "requettePrepaInsert", $requette);
    $ptrQuery = pg_execute($ptrDB, "requettePrepaInsert", array($g10_edition['edition_id'],
                                                                $g10_edition['edi_datedebut'],
                                                                $g10_edition['edi_datefin'],
                                                                $g10_edition['edi_villedebut'],
                                                                $g10_edition['edi_villefin'],
                                                                $g10_edition['course_id']));

    if($ptrQuery)
      return getEditionById($g10_edition['edition_id']);
    return array();
}

/**
 *  updateEdition
 * @param $g10_edition 
 * @return array tableau associatif contenant l'edition mise à jour dans la base de données.
 */
function updateEdition(array $g10_edition): array  {
    $ptrDB = connexion();

    /*  préparation et exécution de la requête UPDATE  */
    $requette = 'UPDATE g10_edition SET edi_datedebut = $1,edi_datefin = $2,edi_villedebut = $3, edi_villefin = $4,course_id=$5 WHERE
     edition_id = $6';

     pg_prepare($ptrDB, "requettePrepaUpdate", $requette);
     $ptrQuery = pg_execute($ptrDB, "requettePrepaUpdate", array($g10_edition['edi_datedebut'],
                                                                 $g10_edition['edi_datefin'],
                                                                 $g10_edition['edi_villedebut'],
                                                                 $g10_edition['edi_villefin'],
                                                                 $g10_edition['course_id'],
                                                                 $g10_edition['edition_id']));

     if($ptrQuery)
        return getEditionById($g10_edition['edition_id']);
     
     return array();
}

/**
 *  deleteEdition
 * @param $id
 * @return void.
 * supprime une edition de la base de données.
 */

function deleteEdition(string $id): void {
   // Connexion à la base de données
   $ptrDB = connexion();

   // Suppression des classements associés à l'édition
   $requetteClasser = "DELETE FROM g10_classer WHERE edition_id = $1";
   pg_prepare($ptrDB, "requettePrepaClasser", $requetteClasser);
   $ptrQueryClasser = pg_execute($ptrDB, "requettePrepaClasser", array($id));

   // Suppression de l'édition
   $requetteEdition = "DELETE FROM g10_edition WHERE edition_id = $1";
   pg_prepare($ptrDB, "requettePrepaEdition", $requetteEdition);
   $ptrQueryEdition = pg_execute($ptrDB, "requettePrepaEdition", array($id));

   // Vérifier si les deux requêtes ont été exécutées avec succès
   if ($ptrQueryClasser && $ptrQueryEdition) {
       echo("L'édition et ses classements ont été supprimés avec succès.<br/>");
   } else {
       // Si l'une des deux requêtes échoue, afficher un message d'erreur
       echo("La suppression a échoué. Aucun changement n'a été effectué.<br/>");
   }
}
      
/****************************************************    AFFICHAGE    *****************************************************************/

//afficher le tableau associatif retourné par getClasserById sous forme d'un tableau 
function afficherTableauSimpleE($tabAssoc) {

    // Vérifie si le tableau contient un message d'erreur
     if (isset($tabAssoc['message'])) {
        echo "<p>" . ($tabAssoc['message']) . "</p>";
        return;
    }
    // Commence le tableau HTML
    echo "<table border='1'>";
    echo "<tr>
            <th>edition_id</th>
            <th>edi_datedebut</th>
            <th>edi_datefin</th>
            <th>edi_villedebut</th>
            <th>edi_villefin </th>
            <th>course_id</th>
          </tr>";  // Entêtes du tableau

    // Affiche les données du seul enregistrement
    echo "<tr>";  // Début de la ligne
    echo "<td>" . $tabAssoc['edition_id'] . "</td>";  
    echo "<td>" . $tabAssoc['edi_datedebut']. "</td>";  
    echo "<td>" . $tabAssoc['edi_datefin'] . "</td>";  
    echo "<td>" . $tabAssoc['edi_villedebut']. "</td>"; 
    echo "<td>" . $tabAssoc['edi_villefin']. "</td>";  
    echo "<td>" . $tabAssoc['course_id']. "</td>"; 
    echo "</tr>";  // Fin de la ligne

    // Fin du tableau HTML
    echo "</table>";
}


//afficher le tableau associatif retourné par getAllClasser sous forme d'un tableau 
function afficherTableauE($tabAssoc) {

    if (empty($tabAssoc)) {
        echo "<p>Aucun résultat trouvé.</p>";
        return;
    }

    echo "<table border='1' >";
     echo "<tr>
            <th>edition_id</th>
            <th>edi_datedebut</th>
            <th>edi_datefin</th>
            <th>edi_villedebut</th>
            <th>edi_villefin </th>
            <th>course_id</th>
          </tr>";

   foreach ($tabAssoc as $valeur) {  
   
    echo "<tr>";  // Début de la ligne
    echo "<td>" . $valeur['edition_id'] . "</td>";  
    echo "<td>" . $valeur['edi_datedebut']. "</td>";  
    echo "<td>" . $valeur['edi_datefin'] . "</td>";  
    echo "<td>" . $valeur['edi_villedebut']. "</td>"; 
    echo "<td>" . $valeur['edi_villefin']. "</td>";  
    echo "<td>" . $valeur['course_id']. "</td>"; 
    echo "</tr>"; 
    
}
  
    echo "</table>";
}
      
      
      




?>
