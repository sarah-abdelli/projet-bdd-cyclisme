<?php
    
    // Inclusion du fichier de configuration de l’environnement
    require_once '../monEnv.php';  
    // Inclusion du fichier contenant les fonctions liées à la gestion des courses
    require_once 'fonctions_course.php';

    // Initialisation du message qui s'affichera en cas d'erreur ou d'information
    $message = "";

// Vérifie si le formulaire a été soumis avec la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que tous les champs nécessaires sont présents dans la requête POST
    if (isset($_POST['course_id']) && isset($_POST['course_nom']) && isset($_POST['course_pays'])) {
        
        // Récupère les données envoyées depuis le formulaire
        $course_id = $_POST['course_id'];
        $course_nom = $_POST['course_nom'];
        $course_pays = $_POST['course_pays'];

        // Regroupe les données dans un tableau associatif à passer à la fonction d’update
        $course = [
            'course_id' => $course_id,
            'course_nom' => $course_nom,
            'course_pays' => $course_pays
        ];

        // Appelle la fonction qui met à jour les données de la course dans la base de données
        $result = updateCourse($course);

        // Si la mise à jour réussit, redirige vers la page de contenu principale
        if ($result) {
                  header("Location: ../contenu.php");
                  exit;
        } else {
            // Sinon, affiche un message d'erreur            
            $message = "Erreur lors de la mise à jour de la course.";
        }
    } else {
        // Si les champs du formulaire sont manquants
        $message = "Données invalides ou manquantes.";
    }
} else {
    // Si la méthode utilisée n'est pas POST
    $message = "Méthode non autorisée.";
}

// Récupère les détails de la course à modifier si un ID a bien été transmis
if (!empty($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    // Appelle une fonction pour récupérer les infos de la course par son identifiant
    $details = getCourseById($course_id);
} else {
    // Si aucun ID n'est fourni    
    $message = "Identifiants invalides.";
    $details = null;
}	
		
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Insertion d'une course</title>
    <!-- Lien vers la feuille de style -->
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
<?php if ($details): ?>
    <!-- Si les détails de la course sont bien récupérés, on affiche le formulaire -->    
    <h1>Insertion d'une nouvelle course</h1>
    <!-- Formulaire d'édition de la course. Il envoie les données en POST vers la même page -->
    <form action="" method="POST">
        
        <!-- Champ caché pour stocker l'identifiant de la course -->
        <label for="course_id">Identifiant de la course:</label>
        <input type="hidden" id="course_id" name="course_id" min="0" value="<?= htmlspecialchars($details['course_id']) ?>"  required>

        <!-- Menu déroulant pour choisir le nom de la course -->
        <label for="course_nom">Nom de la course :</label>
        <select name="course_nom" id="course_nom" value="<?= htmlspecialchars($details['course_nom']) ?>" required>
            <option value="">-- Sélectionnez une course --</option>
            <option value="Tour de France">Tour de France</option>
            <option value="Giro d'Italie">Giro d'Italie</option>
            <option value="Vuelta d'Espagne">Vuelta d'Espagne</option>
        </select>
        
        <!-- Champ de texte pour saisir le pays de la course -->
        <label for="course_pays">Pays de la course :</label>
        <input type="text" id="course_pays" name="course_pays" value="<?= htmlspecialchars($details['course_pays']) ?>" required>
        
        <!-- Boutons d'action -->
        <input type="submit" value="Modifier"><!-- Envoie le formulaire -->
        <input type="reset" value="Effacer"><!-- Réinitialise le formulaire -->
         <button type="button" onclick="window.location.href='../contenu.php'" >Retour</button><!-- Retour à la page principale -->
         
    </form>
    <?php else: ?>
    <!-- Si les détails sont absents, affiche un message d'erreur -->
    <p style="color:red; text-align:center;"><b><?= $message ?></b></p>
<?php endif; ?>

</body>
</html>
