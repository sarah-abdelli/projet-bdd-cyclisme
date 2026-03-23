<?php
// Active l'affichage de toutes les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclut le fichier contenant les fonctions liées aux éditions
require_once('fonctions_edition.php');

// Variable pour stocker les messages d'erreur ou de confirmation
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que tous les champs nécessaires sont bien envoyés
  if (
    isset($_POST['edition_id']) &&
    isset($_POST['edi_datedebut']) &&
    isset($_POST['edi_datefin']) &&
    isset($_POST['edi_villedebut']) &&
    isset($_POST['edi_villefin']) &&
    isset($_POST['course_id'])  
  ){

    // Récupération des données du formulaire
    $edition_id = $_POST['edition_id']; 
    $edi_datedebut = $_POST['edi_datedebut'];
    $edi_datefin = $_POST['edi_datefin']; 
    $edi_villedebut = $_POST['edi_villedebut']; 
    $edi_villefin = $_POST['edi_villefin']; 
    $course_id = $_POST['course_id'];
    
    // Regroupe les données dans un tableau associatif
    $EditionDetails = [
            "edition_id" => $_POST['edition_id'],
            "edi_datedebut" => $_POST['edi_datedebut'],
            "edi_datefin" => $_POST['edi_datefin'],
            "edi_villedebut" => $_POST['edi_villedebut'],
            "edi_villefin" => $_POST['edi_villefin'],
            "course_id" => $_POST['course_id']
    ];
    // Appelle la fonction de mise à jour
    $result = updateEdition($EditionDetails);
    
    // Si la mise à jour a réussi, redirige vers la page de contenu
    if ($result) {
            header("Location: ../contenu.php");
            exit;
    } else {
        // Sinon, affiche un message d'erreur
        $message = "Erreur lors de la mise à jour du pays.";
    }
} else {
    // Si les champs ne sont pas tous présents
    $message = "Action non autorisée.";
}
}





if (!empty($_POST['edition_id'])) {
    // Si un ID d'édition a été transmis, on récupère ses informations    
    $edition_id = $_POST['edition_id'];
    $detailsDuEdition = getEditionById($edition_id); // Récupérer les détails de l'édition

} else {
    // Sinon, on signale que l'identifiant est invalide
    $message = "Identifiants invalides.";
    $detailsDuEdition = null;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification de l'édition</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php if ($detailsDuEdition): ?>
    <h1>Modification de l'édition : <?= htmlspecialchars($detailsDuEdition['edition_id']) ?></h1>

    
        <form action="" method="post">
           <!-- Champ caché pour stocker l'identifiant de l'édition -->
           <input type="hidden" name="edition_id" value="<?= htmlspecialchars($detailsDuEdition['edition_id']) ?>">
 
                <!-- Champ pour saisir la date de début de l'édition -->
                <label for="edi_datedebut">Date de début de l'édition :</label>
                <input type="date" name="edi_datedebut" value="<?= htmlspecialchars($detailsDuEdition['edi_datedebut']) ?>" required>
                <!-- Champ pour saisir la date de fin de l'édition -->
                <label for="edi_datefin">Date de fin de l'édition :</label>
                <input type="date" name="edi_datefin" value="<?= htmlspecialchars($detailsDuEdition['edi_datefin']) ?>" required>
                <!-- Champ pour saisir la ville de départ de l'édition -->
               <label for="edi_villedebut">La ville où l'édition a commencé :</label>
                <input type="text" name="edi_villedebut" value="<?= htmlspecialchars($detailsDuEdition['edi_villedebut']) ?>" required>
                <!-- Champ pour saisir la ville d'arrivée de l'édition -->
                <label for="edi_villefin">La ville où l'édition a fini :</label>
                <input type="text" name="edi_villefin" value="<?= htmlspecialchars($detailsDuEdition['edi_villefin']) ?>" required>
                <!-- Menu déroulant pour associer l'édition à une course existante -->
                <label for="course_id">Choisir une course :</label>
                <select name="course_id" required>
                    <option value="">-- Sélectionnez une course --</option>
                    <option value="1" <?php if ($detailsDuEdition['course_id'] == 1) echo 'selected'; ?>>Tour de France</option>
                    <option value="2" <?php if ($detailsDuEdition['course_id'] == 2) echo 'selected'; ?>>Giro d'Italie</option>
                    <option value="3" <?php if ($detailsDuEdition['course_id'] == 3) echo 'selected'; ?>>Vuelta d'Espagne</option>
                </select>
            

        <!-- Bouton pour envoyer les modifications -->
        <input type="submit" value="Modifier">

        <!-- Bouton pour réinitialiser les champs du formulaire -->
        <input type="reset" value="Effacer">
        
        <!-- Bouton pour revenir à la page principale -->
        <button type="button" onclick="window.location.href='../contenu.php'">Retour</button>
        </form>
    
<?php else: ?>
    <!-- Si les détails sont absents, affiche un message d'erreur -->
    <p style="color:red; text-align:center;"><b><?= $message ?></b></p>
<?php endif; ?>

</body>
</html>
