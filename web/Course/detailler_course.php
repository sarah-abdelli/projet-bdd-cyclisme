<?php
// On inclut le fichier qui contient les fonctions relatives aux courses
require 'fonctions_course.php';

// On vérifie si un identifiant de course a été envoyé par la requête (GET ou POST)
if (isset($_REQUEST['course_id'])) {
    $course_id = $_REQUEST['course_id'];// Si oui, on le stocke dans une variable
} else {
    $course_id = null;// Sinon, on met null par defaut
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail du classement</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php

// On appelle la fonction qui permet de récupérer les détails de la course avec son ID
$detailsDuCourse = getCourseById($course_id);


// Si la fonction retourne bien des données (la course existe)
if ($detailsDuCourse) {
   // On affiche un titre avec le nom de la course
    echo "<h1>Détail de " . ($detailsDuCourse['course_nom']) . "</h1>";
    // On affiche les informations de la course dans une liste
    echo "<ul>";
    echo "<li><b> le ID : </b>" . ($detailsDuCourse['course_id']) . "</li>";
    echo "<li><b>Pays : </b>" . ($detailsDuCourse['course_pays']) . "</li>";
    echo "</ul>";
}  else {
    echo "<li> Course : Information non disponible</li>";
}
// Lien pour revenir à la page précédente
echo '<a href="../contenu.php">Retour</a>';

// Lien vers la page d’accueil principale
echo '<a href="../index.php">Accueil</a>';
?>
