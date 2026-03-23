<?php
    // Inclusion des fichiers nécessaires
    require_once '../monEnv.php'; // Fichier contenant la configuration de la BDD
    require_once 'fonctions_edition.php'; // Fichier contenant les fonction de la gestion de la table Edition 
    $message = "";
    // Vérifie si le formulaire a été soumis via la méthode POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      
        // Création d’un tableau associatif contenant les données à insérer
        $tabComp = array(
            "edition_id" => $_POST['edition_id'],
            "edi_datedebut" => $_POST['edi_datedebut'],
            "edi_datefin" => $_POST['edi_datefin'],
            "edi_villedebut" => $_POST['edi_villedebut'],
            "edi_villefin" => $_POST['edi_villefin'],
            "course_id" => $_POST['course_id']
        );

        /// Appel de la fonction d’insertion de insertEdition avec les données récupérées
        $var = insertEdition($tabComp);

        // Vérifie si l'insertion dans la BDD a été faite avec succès ou pas 
        if (!isset($var['message'])){
               header("Location: ../contenu.php");
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

   
    <title>Insertion d'édition</title>
     
</head>

<body>
       <?php if (!empty($message)) echo $message; ?>
    <h1>Insertion d'une nouvelle édition</h1>

    <!-- Début du formulaire. Méthode POST pour envoyer les données au script PHP "inserer_edition.php" -->
    <form action="" method="POST">

        <!-- Champ pour entrer l'identifiant unique de l'édition type entier >= 0 -->
        <label for="edition_id">Identifiant de l'édition:</label>
        <input type="number" id="edition_id" name="edition_id" min="0" required><br><br>

        <!-- Champ de type date pour la date de début de l'édition (obligatoire) -->
        <label for="edi_datedebut">Date de début de l'édition :</label>
        <input type="date" id="edi_datedebut" name="edi_datedebut" required><br><br>

        <!-- Champ de type date pour la date de fin de l'édition (obligatoire) -->
        <label for="edi_datefin">Date de fin de l'édition :</label>
        <input type="date" id="edi_datefin" name="edi_datefin" required><br><br>

        <!-- Champ texte pour la ville de départ de l'édition -->
        <label for="edi_villedebut">La ville où l'édition a commencé :</label>
        <input type="text" id="edi_villedebut" name="edi_villedebut" required><br><br>

        <!-- Champ texte pour la ville d'arrivée de l'édition -->
        <label for="edi_villefin">La ville où l'édition a fini :</label>
        <input type="text" id="edi_villefin" name="edi_villefin" required><br><br>

        <!-- Menu pour sélectionner la course associée à cette édition -->
        <label for="course_id">Choisir une course :</label>
        <select name="course_id" id="course_id" required>
            <option value="">-- Sélectionnez une course --</option>
            <!-- Valeurs des ID des courses existantes en base -->
            <option value="1">Tour de France</option>
            <option value="2">Giro d'Italie</option>
            <option value="3">Vuelta d'Espagne</option>
        </select><br><br>

        <!-- Bouton pour envoyer le formulaire -->
        <input type="submit" value="Ajouter l'édition">

        <!-- Bouton pour effacer les champs du formulaire -->

        <input type="reset" value="Effacer">
        <button type="button" onclick="window.location.href='../contenu.php'" >Retour</button>
      
    </form>
    <!-- Fin du formulaire -->

</body>
</html>








































