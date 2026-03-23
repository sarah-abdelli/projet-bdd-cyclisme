<?php
session_start(); // Démarre la session pour accéder à $_SESSION['table']
require_once 'monEnv.php'; // Inclusion du fichier de connexion 

// Vérifie que la requête est de type POST et qu'une table est sélectionnée dans la session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['table'])) {
    
    $table = $_SESSION['table']; // Récupère la table sélectionnée
    $fonctions = ucfirst($table) . "/fonctions_{$table}.php"; // Construit le chemin du fichier des fonctions spécifiques à la table

    // Vérifie que le fichier des fonctions existe
    if (!file_exists($fonctions)) {
        echo "<p style='color:red;'>Fichier manquant : $fonctions</p>";
        exit;
    }

    require_once $fonctions; // Inclut le fichier des fonctions de suppression pour la table

    // Effectue la suppression selon la table concernée
    switch ($table) {
        case 'course':
            // Vérifie que l'identifiant de course est présent
            if (!empty($_POST['course_id'])) {  
                $course_id = $_POST['course_id'];
                deleteCourse($course_id); // Appelle la fonction de suppression
                header("Location: contenu.php"); // Redirige vers la page de contenu après suppression
                exit;
            }
            break;

        case 'edition':
            // Vérifie que l'identifiant d'édition est présent
            if (!empty($_POST['edition_id'])) { 
                $edition_id = $_POST['edition_id'];
                deleteEdition($edition_id); // Supprime l'édition correspondante
                header("Location: contenu.php");
                exit;  
            } 
            break;

        case 'coureur':
            // Vérifie que l'identifiant de coureur est présent
            if (!empty($_POST['coureur_id'])) {
                $coureur_id = $_POST['coureur_id'];
                deleteCoureur($coureur_id); // Supprime le coureur
		header("Location: contenu.php");
		exit;
            }
            break;

        case 'classer':
            // Vérifie que les deux clés (clé composite) sont présentes
            if (!empty($_POST['coureur_id']) && !empty($_POST['edition_id'])) {
                $coureur_id = $_POST['coureur_id'];
                $edition_id = $_POST['edition_id'];
                deleteClasser($coureur_id, $edition_id); // Supprime l'entrée de la table classer
                header("Location: contenu.php");
                exit;
            }
            break; 
        default:
            echo "Table non reconnue."; // Si la table n'est pas gérée explicitement
            exit;
    }

} else {
    echo "Requête invalide."; // Si la méthode n’est pas POST ou table non définie
}
?>

