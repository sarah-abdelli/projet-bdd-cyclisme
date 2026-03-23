<?php
require_once 'fonctions_course.php';

echo"<h2> Tests des fonctions de la table course </h2>"; 
/* Test de connexion() */
echo"<b> 1) Connexion à la base de donnée Palmares</b><br>";
$ptrDB = connexion();
if ($ptrDB) {
    echo " Connexion réussie à la base de données !<br />";
} else {
    echo " Erreur de connexion à la base de données !<br />";
}

/****************************************************************************/
/* Test de getCourseById() */

echo " <br />";
$idCourse = 2;  //choisir des valeur differente de ID pour tester 
echo " <b>2.1) Les informations du la course $idCourse :</b> <br />";
echo " <br />";
afficherTableauSimple(getCourseById($idCourse));
echo " <br />";

$idCourse = 4;
echo " <b>2.2) Les informations du la course $idCourse :</b> <br />";
echo " <br />";
afficherTableauSimple(getCourseById($idCourse));
echo " <br />";

/****************************************************************************/
/* Test de insertCourse() */

echo " <br />";
echo " <b>3) inserer une nouvelle course :</b> <br />";
$idCourse=2;
$g10_course = getCourseById($idCourse);

$idCourse=52;//la valeur de ID du course a inserer
$g10_course['course_id'] = $idCourse;
afficherTableauSimple(insertCourse($g10_course));
echo " <br />";
echo " <br />";
afficherTableauSimple(getCourseById($idCourse));
echo " <br />";

/****************************************************************************/
/* Test de updateCourse() */

echo " <br />";
$idCourse=52;//la valeur de l'ID a modifié
echo " <b>4) mettre à jour les informations de la course $idCourse :</b> <br/>";
$g10_course = getCourseById($idCourse);
echo"avant : <br/>";
afficherTableauSimple($g10_course);
echo " <br />";
echo " <br />";
$g10_course['course_nom'] = 'Tour de France';
$g10_course['course_pays'] = 'A';
echo"Apres avoir modifié son nom  et pays : <br/>";
afficherTableauSimple(updateCourse($g10_course));
echo " <br />";

/****************************************************************************/
/* Test de deletecourse() */
echo " <b>5)Supprimer une course :</b> <br />";
echo " <br />";
$idCourse=52;
deletecourse($idCourse);
afficherTableauSimple(getCourseById($idCourse));
echo " <br />";

/****************************************************************************/
/* Test de getAllCourse() */

echo " <br />";
echo " <b>6)affichage des grands tour de cyclisme :</b> <br />";
afficherTableau(getAllCourse());
echo " <br />";


 ?>
