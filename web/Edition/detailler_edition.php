<?php
// On inclut les fonctions liées à l'entité "édition"
require_once 'fonctions_edition.php';

// On vérifie si l'identifiant de l'édition est présent dans la requête
if (isset($_REQUEST['edition_id'])) {
    $edition_id = $_REQUEST['edition_id'];// On le récupère
} else {
    $edition_id = null;// Sinon, on l'initialise à null
}

// On appelle la fonction pour récupérer les détails de l'édition correspondante
$detailsDuEdition = getEditionById($edition_id);

// Fonction pour obtenir les informations de la course liée à l'édition
function getCourseInfos($course_id) {
    $conn = connexion(); // Connexion à la base de données (fonction réutilisée)
     // Préparation de la requête SQL pour récupérer le nom et le pays de la course
    $query = "SELECT course_nom, course_pays FROM g10_course WHERE course_id = $1";
    pg_prepare($conn, "getCourse", $query);// On prépare la requête
    $result = pg_execute($conn, "getCourse", array($course_id)); // On l’exécute avec l’ID de course
    // Si des résultats sont trouvés, on retourne les données sous forme de tableau associatif
    return ($result && pg_num_rows($result) > 0) ? pg_fetch_assoc($result) : null;
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
// Si on a bien trouvé une édition avec l'ID fourni
if ($detailsDuEdition) {
// Titre avec le numéro de l’édition
    echo "<h1>Détail de l'edition Num : " . ($detailsDuEdition['edition_id']) . "</h1>";
    // On affiche les différentes informations de l’édition dans une liste
    echo "<ul>";
    echo "<li><b> le ID : </b>" . ($detailsDuEdition['edition_id']) . "</li>";
    echo "<li><b> la date de debut : </b>" . ($detailsDuEdition['edi_datedebut']) . "</li>";
    echo "<li><b> la date de fin d'edition: </b>" . ($detailsDuEdition['edi_datefin']) . "</li>";
    echo "<li><b> la ville de debut : </b>" . ($detailsDuEdition['edi_villedebut']) . "</li>";
    echo "<li><b> la ville de fin d'edition: </b>" . ($detailsDuEdition['edi_villefin']) . "</li>";
    // On récupère les infos de la course associée à cette édition
   $courseInfos = getCourseInfos($detailsDuEdition['course_id']);
if ($courseInfos) {
 // Si les infos sont disponibles, on les affiche dans une sous-liste
    echo "<li><b> course : </b>";
    echo "<ul><li>". $courseInfos['course_nom'] . "</li>";
     echo"<li>Pays : ". $courseInfos['course_pays'] . "</li></ul></li>";
} else {
// Sinon on indique que les infos ne sont pas disponibles
    echo "<li> Edition : Informations non disponibles</li>";
}

    echo "</ul>";
} 
// Lien pour revenir à la page de contenu
echo '<a href="../contenu.php">Retour</a>';
// Lien vers la page d'accueil
echo '<a href="../index.php">Accueil</a>';
?>
