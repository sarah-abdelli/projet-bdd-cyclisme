<?php 
// Inclusion des fichiers nécessaires pour les fonctions spécifiques aux coureurs, éditions et base de données
require_once '../Coureur/fonctions_coureur.php';
require_once '../Edition/fonctions_edition.php';
require_once '../monEnv.php';
require_once 'fonctions_classer.php';

// Variable pour afficher des messages d'erreur ou de succès
$message = "";

// Vérifie si le formulaire a été soumis par la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données envoyées par le formulaire
    $num_classement = $_POST['num_classement'];
    $distance_parcourue = $_POST['distance_parcourue'];
    $temps = $_POST['temps'];
    $coureur_id = $_POST['coureur_id'];
    $edition_id = $_POST['edition_id'];
    // Tableau associatif avec les données récupérées
    $tabComp = array(
        "num_classement" => $num_classement,
        "distance_parcourue" => $distance_parcourue,
        "temps" => $temps,
        "coureur_id" => $coureur_id,
        "edition_id" => $edition_id,
    );
    // Appel à la fonction d'insertion dans la base de données
    $var = insertClasser($tabComp);

    // Si aucune erreur n'est retournée par la fonction d'insertion, rediriger l'utilisateur
    if (!isset($var['message'])) {
        header("Location: ../contenu.php");
        exit;// Arrêter le script après la redirection
    } else {
        // Si une erreur est survenue, afficher un message d'erreur        
        $message = "<p style='color: red'><b>Une erreur est survenue lors de l'insertion des données.</b></p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../css/style.css"><!-- Lien vers le fichier CSS pour le style de la page -->
    <meta charset="UTF-8">
    <title>Insertion des performances d'un coureur</title>
    
</head><body>

    <h1>Insertion des performances d'un coureur</h1>

    <!-- Affichage du message d'erreur si une erreur est survenue -->
    <?php if (!empty($message)) echo $message; ?>

    <!-- Formulaire d'insertion des performances -->
    <form action="" method="POST">
        <!-- Champ pour le numéro du classement -->
        <label for="num_classement">Le numéro du classement :</label>
        <select id="num_classement" name="num_classement" required>
            <option value="">-- Sélectionnez --</option>
            <option value="1">Le premier</option>
            <option value="2">Le deuxième</option>
            <option value="3">Le troisième</option>
        </select><br><br>
        <!-- Champ pour la distance parcourue -->
        <label for="distance_parcourue">La distance parcourue (en km) :</label>
        <input type="number" step="any" id="distance_parcourue" name="distance_parcourue" min="0.01" required><br><br>
        <!-- Champ pour le temps -->
        <label for="temps">Le temps (en min) :</label>
        <input type="number" id="temps" name="temps" min="0"><br><br>
        <!-- Champ pour sélectionner un coureur dans la base de données -->
        <label for="coureur_id">L'identifiant de coureur :</label>
        <?php
            // Récupérer tous les coureurs de la base de données et afficher un menu déroulant
            $tab = getAllCoureur();
            $res = '<select id="coureur_id" name="coureur_id" required>';
            $res .= '<option value="">-- Sélectionnez --</option>';
            foreach ($tab as $comp) {
                $res .= '<option value="'.$comp['coureur_id'].'">'.$comp['coureur_id'].': '.$comp['coureur_nom'].'</option>';
            }
            $res .= '</select>';
            echo $res;
        ?>
        <br><br>
        <!-- Champ pour sélectionner une édition dans la base de données -->
        <label for="edition_id">L'identifiant d'édition :</label>
        <?php
            // Récupérer toutes les éditions et afficher un menu déroulant
            $ta = getAllEdition();
            $re = '<select id="edition_id" name="edition_id" required>';
            $re .= '<option value="">-- Sélectionnez --</option>';
            foreach ($ta as $comp) {
                $re .= '<option value="'.$comp['edition_id'].'">'.$comp['edition_id'].': '.$comp['edi_datedebut'].'</option>';
            }
            $re .= '</select>';
            echo $re;
        ?>
        <br><br>
        <!-- Boutons de soumission et de réinitialisation du formulaire -->
        <input type="submit" value="Ajouter">
        <input type="reset" value="Effacer">
        <!-- Bouton pour revenir à la page précédente -->
        <button type="button" onclick="window.location.href='../contenu.php'">Retour</button>
       
    </form>

</body>
</html>

