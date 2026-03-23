 <?php
require_once '../monEnv.php'; // Inclusion du fichier d'environnement
require_once '../Coureur/fonctions_coureur.php';// Inclusion des fonctions liées à la gestion des coureurs
require_once '../Edition/fonctions_edition.php';// // Inclusion des fonctions liées aux éditions


$message = "";// Message à afficher en cas d’erreur ou de succès


// Vérifie si le formulaire a été soumis avec la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Vérifie que toutes les données nécessaires sont présentes dans la requête 
  if (
    isset($_POST['coureur_id']) &&
    isset($_POST['coureur_nom']) &&
    isset($_POST['coureur_prenom']) &&
    isset($_POST['nationalite']) &&
    isset($_POST['nom_equipe']) &&
    isset($_POST['date_naissance'])
) {
    // Récupère les données du formulaire
    $coureur_id = $_POST['coureur_id'];
   	$coureur_nom = $_POST['coureur_nom'];
    $coureur_prenom = $_POST['coureur_prenom'];
    $nationalite = $_POST['nationalite'];
    $nom_equipe = $_POST['nom_equipe'];
    $date_naissance = $_POST['date_naissance'];

    // Regroupe les données dans un tableau associatif
    $classer = [
    "coureur_id" => $coureur_id,
    "coureur_nom" => $coureur_nom,
    "coureur_prenom" => $coureur_prenom,
    "nationalite" => $nationalite,
    "nom_equipe" => $nom_equipe,
    "date_naissance" => $date_naissance
    ];
      // Appelle la fonction d’update du coureur avec les données modifiées
      $result = updateCoureur($classer);

        // Si la mise à jour a réussi, redirige vers la page de contenu
        if ($result) {
            header("Location: ../contenu.php");
            exit;
        } else {
            // Sinon, affiche un message d’erreur            
            $message = "Erreur lors de la mise à jour du classement.";
        }
    } else {
        // Si un champ est manquant, affiche un message d’erreur
        $message = "Données manquantes.";
    }
}

// Vérifie si l'identifiant du coureur a été transmis (via champ caché)
if (!empty($_POST['coureur_id'])) {
    $coureur_id = $_POST['coureur_id'];
    // Récupère les détails du coureur à partir de son ID    
    $details = getCoureurById($coureur_id);
} else {
    // Si l'identifiant est invalide ou absent, prépare un message d'erreur    
    $message = "Identifiants invalides.";
    $details = null;
}

   

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Insertion d'un coureur</title>
    <link rel="stylesheet" href="../css/style.css"><!-- Feuille de style CSS -->
</head>
<body>
    <?php if ($details): ?>


     <h1>Modification d'un coureur</h1>

    <!-- Formulaire de modification d’un coureur -->    
    <form action="" method="POST">

        <!-- Identifiant du coureur, champ caché pour ne pas être modifié -->
        <input type="hidden" name="coureur_id" value="<?= htmlspecialchars($details['coureur_id']) ?>">

        <!-- Nom -->
        <label for="coureur_nom">Nom de coureur :</label>
        <input type="text" id="coureur_nom" name="coureur_nom" value="<?= htmlspecialchars($details['coureur_nom']) ?>" required><br><br>

        <!-- Prénom -->
        <label for="coureur_prenom">Prénom de coureur :</label>
        <input type="text" id="coureur_prenom" name="coureur_prenom" value="<?= htmlspecialchars($details['coureur_prenom']) ?>" required><br><br>

        <!-- Nationalité -->
        <label for="nationalite">La nationalité :</label>
        <input type="text" id="nationalite" name="nationalite" value="<?= htmlspecialchars($details['nationalite']) ?>" required><br><br>

        <!-- Nom de l'équipe -->
        <label for="nom_equipe">Le nom de l'équipe :</label>
        <input type="text" id="nom_equipe" name="nom_equipe" value="<?= htmlspecialchars($details['nom_equipe']) ?>" required><br><br>



        <!-- Date de naissance -->
        <label for="date_naissance">Date de naissance :</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($details['date_naissance']) ?>" required><br><br>

        <!-- Bouton de soumission du formulaire -->        
        <input type="submit" value="Modifier">

        <!-- Bouton pour effacer tous les champs -->
        <input type="reset" value="Effacer">
        <!-- Bouton de retour à la page précédente sans soumission -->        
        <button type="button" onclick="window.location.href='../contenu.php'" >Retour</button>
       
    </form>

    <!-- Fin du formulaire -->
    <?php else: ?>
    <p style="color:red; text-align:center;"><b><?= $message ?></b></p>
<?php endif; ?>
</body>
</html>

