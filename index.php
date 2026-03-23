<?php
session_start(); // Démarre une session pour mémoriser la table sélectionnée

// Vérifie que la requête est POST et qu'une table a été soumise
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table'])) {
    $_SESSION['table'] = $_POST['table']; // Enregistre le nom de la table sélectionnée dans la session

    // Redirection vers la page générique de contenu
    header("Location: contenu.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Accueil.css" type="text/css" />
    <title>Grands tours cyclistes</title>
</head>


<body>
    <div class="accueil">
    <h1 class="titre">Palmarès des Grands Tours de Cyclisme</h1>
        <form action="" method="post">
            <input type="hidden" name="table" value="course">
            <button type="submit">Course</button>
        </form>
        <form action="" method="post">
            <input type="hidden" name="table" value="edition">
            <button type="submit">Édition</button>
        </form>
        <form action="" method="post">
            <input type="hidden" name="table" value="classer">
            <button type="submit">Classer</button>
        </form>
        <form action="" method="post">
            <input type="hidden" name="table" value="coureur">
            <button type="submit">Coureur</button>
        </form>
    </div>
</body>

</body>
</html>

