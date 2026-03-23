<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion des fichiers contenant les fonctions nécessaires
require_once('../Coureur/fonctions_coureur.php');
require_once('../Edition/fonctions_edition.php');
require_once('fonctions_classer.php');

// Initialisation d'un message vide (utilisé pour afficher les erreurs éventuelles)
$message = "";

// Vérification que la requête est bien envoyée en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Vérifie que toutes les données nécessaires sont présentes dans la requête POST
    if (
        isset($_POST['coureur_id']) &&
        isset($_POST['edition_id']) &&
        isset($_POST['num_classement']) &&
        isset($_POST['distance_parcourue']) &&
        isset($_POST['temps'])
    ) {
        //Récupération des données du formulaire
        $coureur_id = $_POST['coureur_id'];
        $edition_id = $_POST['edition_id'];
        $num_classement = $_POST['num_classement'];
        $distance_parcourue = $_POST['distance_parcourue'];
        $temps = $_POST['temps'];

        //Création d'un tableau associatif représentant les données à mettre à jour
        $classer = [
            'coureur_id' => $coureur_id,
            'edition_id' => $edition_id,
            'num_classement' => $num_classement,
            'distance_parcourue' => $distance_parcourue,
            'temps' => $temps
        ];
        //Appel de la fonction de mise à jour du classement
        $result = updateClasser($classer);
        //Redirection vers la page d'accueil en cas de succès
        if ($result) {
            header("Location: ../contenu.php");
            exit;
        } else {
            //Affichage d'un message d'erreur si la mise à jour échoue
            $message = "Erreur lors de la mise à jour du classement.";
        }
    } else {
        //Affichage d'un message d'erreur si certaines données sont manquantes
        $message = "Données manquantes.";
    }
}

// ---Récupération sécurisée du classement à modifier pour préremplir le formulaire

// Vérifie si les champs 'coureur_id' et 'edition_id' sont présents et non vides dans la requête POST.
// Ces deux identifiants sont indispensables pour retrouver un enregistrement unique dans la table de classement
if (!empty($_POST['coureur_id']) && !empty($_POST['edition_id'])) {
    // On récupère les identifiants envoyés par le formulaire (cachés dans des champs hidden)
    $coureur_id = $_POST['coureur_id'];
    $edition_id = $_POST['edition_id'];
    
    // On appelle la fonction getClasserById() pour récupérer les détails du classement correspondant
    // Ces données serviront à préremplir les champs du formulaire HTML
    $details = getClasserById($coureur_id, $edition_id);
} else {
    // Si l'un des identifiants est manquant ou vide, on affiche un message d'erreur
    // et on indique qu'aucune donnée de classement n'est disponible po
    $message = "Identifiants invalides.";
    $details = null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modification d'un classement </title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<!-- Si les données de classement ont été récupérées -->
<?php if ($details): 
echo"<h1>Modification du classement du coureur n° " . htmlspecialchars($details['coureur_id']) ." de l'édition n° " . htmlspecialchars($details['edition_id']) ."</h1>" ;?>

<!-- Formulaire de modification -->
<form action="" method="post">
    <!-- Champs cachés pour envoyer les identifiants -->
    <input type="hidden" name="coureur_id" value="<?= htmlspecialchars($details['coureur_id']) ?>">
    <input type="hidden" name="edition_id" value="<?= htmlspecialchars($details['edition_id']) ?>">

        <!-- Numéro de classement (sous forme de liste déroulante) -->
        <label for="num_classement">Le numéro du classement :</label>
        <select id="num_classement" name="num_classement"  value="<?= htmlspecialchars($details['num_classement']) ?>" required>
            <option value="">-- Sélectionnez --</option>
            <option value="1">Le premier</option>
            <option value="2">Le deuxième</option>
            <option value="3">Le troisième</option>
        </select><br><br>
  

        <!-- Distance parcourue (champ numérique avec décimales) -->
        <label for="distance_parcourue">La distance parcourue (en km) :</label>
        <input type="number" step="any" id="distance_parcourue" name="distance_parcourue" min="0.01" value="<?= htmlspecialchars($details['distance_parcourue']) ?>" required><br><br>
   

        <!-- Temps (champ numérique) -->
        <label for="temps">Le temps (en min) :</label>
        <input type="number" id="temps" name="temps" min="0" value="<?= htmlspecialchars($details['temps']) ?>" required><br><br>
   
        <!-- Boutons d'action -->
        <input type="submit" value="Modifier"><!-- Envoie les données -->
        <input type="reset" value="Effacer"><!-- Réinitialise le formulaire -->
        <button type="button" onclick="window.location.href='../contenu.php'">Retour</button><!-- Retour à la liste -->
</form>

<?php else: ?>
    <!-- Affichage du message d'erreur si le classement n'est pas disponible -->
    <p style="color:red; text-align:center;"><b><?= $message ?></b></p>
<?php endif; ?>


</body>
</html>
