<?php
// On inclut les fonctions liées au classement
require 'fonctions_classer.php';

// On vérifie si un ID de coureur est passé dans la requête (GET ou POST)
if (isset($_REQUEST['coureur_id'])) {
    $coureur_id = $_REQUEST['coureur_id'];// On le récupère
} else {
    $coureur_id = null;// Sinon, on l'initialise à null
}
// On fait de même pour l'ID de l'édition
if (isset($_REQUEST['edition_id'])) {
    $edition_id = $_REQUEST['edition_id'];
} else {
    $edition_id = null;
}

// On récupère les détails du classement correspondant au coureur et à l’édition
$detailsDuClassement = getClasserById($coureur_id, $edition_id);


// Fonction pour récupérer les informations du coureur à partir de son ID
function getCoureurInfos($id) {
    $conn = connexion(); // Connexion à la base
    $query = "SELECT coureur_nom, coureur_prenom, nationalite, nom_equipe, date_naissance FROM g10_coureur WHERE coureur_id = $1";
    pg_prepare($conn, "getCoureur", $query);// Préparation de la requête
    $res = pg_execute($conn, "getCoureur", array($id));// Exécution avec le paramètre
    
    // On retourne les données si elles existent
    return ($res && pg_num_rows($res) > 0) ? pg_fetch_assoc($res) : null;
}

// Fonction pour récupérer les informations de l'édition à partir de son ID
function getEditionInfos($id) {
    $conn = connexion();
    $query = "SELECT edi_datedebut, edi_datefin, edi_villedebut, edi_villefin FROM g10_edition WHERE edition_id = $1";
    pg_prepare($conn, "getEdition", $query);
    $res = pg_execute($conn, "getEdition", array($id));
    return ($res && pg_num_rows($res) > 0) ? pg_fetch_assoc($res) : null;
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
// Si un classement a bien été trouvé
if ($detailsDuClassement) {
    echo "<h1>Détail du classement </h1>";
    echo "<ul>";
    
    // On affiche les informations principales du classement
    echo "<li><b> Numéro de classement : </b>" . ($detailsDuClassement['num_classement']) . "</li>";
    echo "<li><b> Distance parcourue : </b>" . ($detailsDuClassement['distance_parcourue']) . "</li>";
    echo "<li><b> Temps : </b>" . ($detailsDuClassement['temps']) . "</li>";
    
     // On récupère les infos du coureur concerné
    $coureur = getCoureurInfos($detailsDuClassement['coureur_id']);
if ($coureur) {
// Si les infos existent, on les affiche dans une sous-liste
    echo "<li><b>Détail du coureur : </b><ul>" ;
    echo"<li>Nom :" . $coureur['coureur_prenom'] . " " . $coureur['coureur_nom'] . "</li>";
    echo"<li>Nationalite : " . $coureur['nationalite'] . "</li>";
    echo"<li>Nom d'équipe : " . $coureur['nom_equipe'] . "</li>";
    echo"<li>Date de naissance : " . $coureur['date_naissance'] . "</li></ul></li>";
} else {
 // Sinon, on indique que les informations ne sont pas disponibles
    echo "<li> Coureur : Information non disponible</li>";
}
    // On récupère les infos de l’édition concernée
$edition = getEditionInfos($detailsDuClassement['edition_id']);
if ($edition) {
// On affiche les details de l'édition dans une sous-liste
    echo "<li><b> Détail du l'édition : </b><ul>";
    echo"<li> de " . $edition['edi_datedebut'] . " au " . $edition['edi_datefin'] . "</li>";
    echo"<li> La ville ou l'édition a commencer : " . $edition['edi_villedebut'] . "</li>";
    echo"<li> La ville ou l'édition a finie : " . $edition['edi_villefin'] . "</li></ul></li>";
} else {
    echo "<li> Classer : Information non disponible</li>";
}

    echo "</ul>";
} 
// On propose des liens pour retourner en arrière et  à l'accueil
echo '<a href="../contenu.php">Retour</a>';
echo '<a href="../index.php">Accueil</a>';
?>
