<?php
// On inclut le fichier qui contient les fonctions liées aux coureurs
require 'fonctions_coureur.php';

// On vérifie si un ID de coureur a été transmis via GET ou POST
if (isset($_REQUEST['coureur_id'])) {
    $coureur_id = $_REQUEST['coureur_id'];// Si oui, on le récupère
} else {
    $coureur_id = null;// Sinon, on initialise à null
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

// On appelle la fonction pour récupérer les informations du coureur à partir de son ID
$detailsDuCoureur = getCoureurById($coureur_id);

// Si on a bien récupéré un coureur avec cet ID
if ($detailsDuCoureur) {
 // On affiche un titre avec le prenom et le nom du coureur
    echo "<h1>Détail de " . htmlspecialchars($detailsDuCoureur['coureur_prenom']) . " " . htmlspecialchars($detailsDuCoureur['coureur_nom']) . "</h1>";
    // On affiche les informations sous forme de liste
    echo "<ul>";
    echo "<li><b>Le ID: </b>" . htmlspecialchars($detailsDuCoureur['coureur_id']) . "</li>";
    echo "<li><b>Nationalité : </b>" . htmlspecialchars($detailsDuCoureur['nationalite']) . "</li>";
    echo "<li><b>Nom de l'équipe : </b>" . htmlspecialchars($detailsDuCoureur['nom_equipe']) . "</li>";
    echo "<li><b>Date de naissance : </b>" . htmlspecialchars($detailsDuCoureur['date_naissance']) . "</li>";
    } else {
    echo "<li> Coureur : Information non disponible</li>";
}
    echo "</ul>";
    


// Lien de retour vers la page précédente
echo '<a href="../contenu.php">Retour</a>';
// Lien vers la page d’accueil
echo '<a href="../index.php">Accueil</a>';
?>
