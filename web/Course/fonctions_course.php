<?php

require_once __DIR__ . '/../monEnv.php';



function connexion() {
    /**  renseigner $strConnex à l'aide de $_ENV configuré dans monEnv.php */
    $strConnex = "host={$_ENV['dbHost']} dbname={$_ENV['dbName']} user={$_ENV['dbUser']} password={$_ENV['dbPasswd']}";
    $ptrDB = pg_connect($strConnex);
    
    return $ptrDB;
}

/*
 * getCourseById
 * @param string $id
 * @return array tableau associatif associé à la course
 
 */
function getCourseById(string $id) : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g10_course WHERE course_id = $1";

    /*  préparer la requête avec la fonction pg_prepare(...) ici */
    pg_prepare($ptrDB,"reqPrepSelectById", $query);

    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery))
        /* récupérer le tableau associatif avec pg_fetch_assoc dans $resu */
         $resu=pg_fetch_assoc( $ptrQuery);
        if (empty($resu))
            $resu =  array("message" => "Identifiant de g10_course non valide : $id");
   
    pg_free_result($ptrQuery);  // libérer les ressources avec pg_free_result()  
    pg_close($ptrDB);    //fermer la connexion avec pg_close()  
    return $resu;
}

/*
 * getAllCourse
 * @param void
 * @return array tableau associatif contenant toutes les courses de la base de donnée 
 */
function getAllCourse() : array {
    $ptrDB = connexion();

    $query = "SELECT * FROM g10_course";
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

/*
 *  insertCourse
 * @param $g10_course 
 * @return array tableau associatif contenant la nouvelle course enregistrée dans la base de données.
 */

function insertCourse(array $g10_course) : array {
    $ptrDB = connexion();
    
        // Vérification si l'ID existe déjà
    $reqSql = "SELECT course_id FROM g10_course WHERE course_id = $1";
    $reqResult = pg_prepare($ptrDB, "req_edition_id", $reqSql);
    $reqResult = pg_execute($ptrDB, "req_edition_id", array($g10_course['course_id']));
    
    // Si l'ID existe déjà
    if (pg_num_rows($reqResult) > 0) {
        pg_free_result($reqResult);
        pg_close($ptrDB);
        return ["message" => "La course avec l'ID " . $g10_course['course_id'] . " existe déjà."];
    }
    /*  préparation et exécution de la requête INSERT  */
    $requette = 'INSERT INTO g10_course(course_id,course_nom,course_pays) VALUES ($1,$2,$3)';

    pg_prepare($ptrDB, "requettePrepaInsert", $requette);
    $ptrQuery = pg_execute($ptrDB, "requettePrepaInsert", array($g10_course['course_id'],
                                                                $g10_course['course_nom'],
                                                                $g10_course['course_pays']));

    if($ptrQuery)
      return getCourseById($g10_course['course_id']);
    return array();
}

/*
 *  updateCourse
 * @param $g10_course 
 * @return array tableau associatif contenant la course mise à jour dans la base de données.
 */
function updateCourse(array $g10_course): array  {
    $ptrDB = connexion();

    /*  préparation et exécution de la requête UPDATE  */
    $requette = 'UPDATE g10_course SET course_nom = $1,course_pays = $2 WHERE course_id = $3';

     pg_prepare($ptrDB, "requettePrepaUpdate", $requette);
     $ptrQuery = pg_execute($ptrDB, "requettePrepaUpdate", array($g10_course['course_nom'],
                                                                 $g10_course['course_pays'],
                                                                 $g10_course['course_id']));

     if($ptrQuery)
        return getCourseById($g10_course['course_id']);
     return array();
}

/*
 *  deleteCourse
 * @param $id
 * @return void.
 * supprime une course de la base de données.
 */
 

function deleteCourse(string $id): void {
   $ptrDB = connexion();

   // Supprimer des classements
   $requetteClasser = "DELETE FROM g10_classer WHERE edition_id IN (SELECT edition_id FROM g10_edition WHERE course_id = $1)";
   pg_prepare($ptrDB, "requettePrepaClasser", $requetteClasser);
   $ptrQueryClasser = pg_execute($ptrDB, "requettePrepaClasser", array($id));

   // Supprimer des editions
   $requetteEdition = "DELETE FROM g10_edition WHERE course_id = $1";
   pg_prepare($ptrDB, "requettePrepaEdition", $requetteEdition);
   $ptrQueryEdition = pg_execute($ptrDB, "requettePrepaEdition", array($id));

   // Supprimer de la course
   $requetteCourse = "DELETE FROM g10_course WHERE course_id = $1";
   pg_prepare($ptrDB, "requettePrepaCourse", $requetteCourse);
   $ptrQueryCourse = pg_execute($ptrDB, "requettePrepaCourse", array($id));

  // Supprimer des coureurs orphelins
   $requeteCoureur = "DELETE FROM g10_coureur WHERE coureur_id NOT IN (SELECT DISTINCT coureur_id FROM g10_classer)";
   $ptrQueryCoureur = pg_query($ptrDB, $requeteCoureur);

   // le nombre de coureurs supprimé
   $nbCoureursSupprimes = pg_affected_rows($ptrQueryCoureur);

   // Verifier de la suppression
   if ($ptrQueryClasser && $ptrQueryEdition && $ptrQueryCourse) {
       echo("La course et ses relations ont été supprimées avec succès.<br/>");

       if ($nbCoureursSupprimes > 0) {
           echo("$nbCoureursSupprimes coureur(s) orphelin(s) supprimé(s).<br/>");
       } else {
           echo("Aucun coureur orphelin à supprimer.<br/>");
       }
   } else {
       echo("La suppression a échoué.<br/>");
   }

   // Fermeture de la connexion
   pg_close($ptrDB);

}


   
   
  
  
      
            
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
                        <th>course_id</th>
            <th>course_nom</th>
            <th>course_pays ID</th>

          </tr>";  // Entêtes du tableau

    // Affiche les données du seul enregistrement
    echo "<tr>";  // Début de la ligne
        echo "<td>" . $tabAssoc['course_id'] . "</td>";  
    echo "<td>" . $tabAssoc['course_nom'] . "</td>";  
    echo "<td>" . $tabAssoc['course_pays']. "</td>";  

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
            <th>course_id</th>
          <th>course_nom</th>
            <th>course_pays </th>

   	  
          </tr>";

   foreach ($tabAssoc as $valeur) {  
   
    echo "<tr>";
     echo "<td>" .$valeur['course_id']. "</td>";
    echo "<td>" . $valeur['course_nom']. "</td>";
    echo "<td>" .$valeur['course_pays']. "</td>";

    echo "</tr>";
    
    
}
  
    echo "</table>";
}
      
      
      


?>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
 
