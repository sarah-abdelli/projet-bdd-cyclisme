 <?php
// On inclut les fichiers necessaires à la configuration et aux fonctions
require_once '../monEnv.php';
require_once '../Coureur/fonctions_coureur.php';
require_once '../Edition/fonctions_edition.php';

// On initialise la variable pour afficher une erreur s’il y a un problème
$erreur = null;

// Si la méthode utilisée est POST, cela signifie que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // On récupère les valeurs envoyées depuis le formulaire
    $coureur_id = $_POST['coureur_id'];
    $coureur_nom = $_POST['coureur_nom'];
    $coureur_prenom = $_POST['coureur_prenom'];
    $nationalite = $_POST['nationalite'];
    $nom_equipe = isset($_POST['nom_equipe']) ? $_POST['nom_equipe'] : '';
    $date_naissance = $_POST['date_naissance'];

// On regroupe les données dans un tableau associatif
    $tabComp = array(
        "coureur_id" => $coureur_id,
        "coureur_nom" => $coureur_nom,
        "coureur_prenom" => $coureur_prenom,
        "nationalite" => $nationalite,
        "nom_equipe" => $nom_equipe,
        "date_naissance" => $date_naissance
    );

 // On appelle la fonction d’insertion du coureur
    $var = insertCoureur($tabComp);
 // Si l’insertion s’est bien passée (pas de message d’erreur)
    if (!isset($var['message'])) {
        header("Location: ../contenu.php");// On redirige vers la page de contenu
        exit; // toujours terminer après header
    } else {
        $erreur = "Une erreur est survenue lors de l'insertion des données."; // Sinon, on affiche un message d’erreur
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <title>Insertion d'un coureur</title>
    
</head>
<body>
      <?php if (isset($erreur)) {
    echo "<p style='color:red; text-align:center;'><b>$erreur</b></p>";
} ?>

     <h1>Insertion d'un nouveau coureur</h1>

    <!-- Début du formulaire, les données seront envoyées en POST vers "inserer_coureur.php" -->
    <form action="" method="POST">

        <!-- Champ pour l'identifiant du coureur (numérique, obligatoire) -->
        <label for="coureur_id">Identifiant de coureur:</label>
        <input type="number" id="coureur_id" name="coureur_id" min="0" required><br><br>

        <!-- Champ pour le nom du coureur (texte, obligatoire) -->
        <label for="coureur_nom">Nom de coureur :</label>
        <input type="text" id="coureur_nom" name="coureur_nom" required><br><br>

        <!-- Champ pour le prénom du coureur (texte, obligatoire) -->
        <label for="coureur_prenom">Prénom de coureur :</label>
        <input type="text" id="coureur_prenom" name="coureur_prenom" required><br><br>

        <!-- Champ pour la nationalité du coureur (texte, obligatoire) -->
        <label for="nationalite">La nationalité :</label>
        <input type="text" id="nationalite" name="nationalite" required><br><br>

        <!-- Champ pour le nom de l'équipe (texte, facultatif) -->
        <label for="nom_equipe">Le nom de l'équipe :</label>
        <input type="text" id="nom_equipe" name="nom_equipe"><br><br>

        <!-- Champ pour la date de naissance (obligatoire) -->
        <label for="date_naissance">Date de naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" required><br><br>

        <!-- Bouton pour envoyer les données -->
        <input type="submit" value="Ajouter le coureur">

        <!-- Bouton pour réinitialiser les champs du formulaire -->
        <input type="reset" value="Effacer">
  <button type="button" onclick="window.location.href='../contenu.php'">Retour</button>
       
    </form>

    <!-- Fin du formulaire -->
</body>
</html>

