<?php
    
    // Inclusion des fichiers nécessaires
    require_once '../monEnv.php';  
    require_once 'fonctions_course.php';  

    	$message = "";
    	// Vérifie si le formulaire a été envoyé en méthode POST
    	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		
      // Récupération des valeurs envoyées depuis le formulaire
    	$course_id = $_POST['course_id'];
		$course_nom = $_POST['course_nom'];
		$course_pays = $_POST['course_pays'];
		
                
		
      // Créer un tableau associatif contenant les données à insérer		
		$tabComp = array(
	      "course_id" => $_POST['course_id'],
         "course_nom" => $_POST['course_nom'],
         "course_pays" => $_POST['course_pays'],
      );
          
      // Appel de la fonction d’insertion de insertCourse avec les données récupérées      
      $var = insertCourse($tabComp);
      
      // Vérifie si l'insertion dans la BDD a été faite avec succès ou pas   
     if (!isset($var['message'])){
                  header("Location: ../contenu.php");
                  exit;
      } else {
             echo "<p style='color: red'><b>Une erreur est survenue lors de l'insertion des données.</b></p>";
         }
      }
		
		
?>




<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="../css/style.css">
    <meta charset="UTF-8">
    <title>Insertion d'une course</title>
  
</head>

<body>
    <?php if (!empty($message)) echo $message; ?>
    <h1>Insertion d'une nouvelle course</h1>
    
<!-- Début du formulaire, les données seront envoyées en POST vers "inserer_course.php" -->
    <form action="" method="POST">

        <label for="course_id">Identifiant de la course:</label>
        <input type="number" id="course_id" name="course_id" min="0" required>

        <label for="course_nom">Nom de la course :</label>
        <select name="course_nom" id="course_nom" required>
            <option value="">-- Sélectionnez une course --</option>
            <option value="Tour de France">Tour de France</option>
            <option value="Giro d'Italie">Giro d'Italie</option>
            <option value="Vuelta d'Espagne">Vuelta d'Espagne</option>
        </select>

        <label for="course_pays">Pays de la course :</label>
        <input type="text" id="course_pays" name="course_pays" required>
          <!-- Bouton pour envoyer les données -->
        <input type="submit" value="Ajouter la course">
        <!-- Bouton pour réinitialiser les champs du formulaire -->
        <input type="reset" value="Effacer">
         <button type="button" onclick="window.location.href='../contenu.php'" >Retour</button>
        
    </form>

</body>
</html>
