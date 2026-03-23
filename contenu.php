<?php
session_start(); //Démarrer une session PHP

//Affichage des erreurs 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de donnée
require_once 'monEnv.php'; 

// Fonction ui recupére la date du début d'une édition à partir de son ID
function getEdiDateById($edition_id): ?string {
    $db = connexion(); // Connexion à la base
    $sql = "SELECT edi_datedebut FROM g10_edition WHERE edition_id = $1";
    pg_prepare($db, "getEdiDateById", $sql);
    $res = pg_execute($db, "getEdiDateById", [$edition_id]);
    $row = pg_fetch_assoc($res);
    pg_close($db); // Fermeture de la connexion
    return $row['edi_datedebut'] ?? null; // Retourne la date ou null
}

// Fonction qui récupére le nom d'une course à partir de son ID
function getCourseNomById($course_id): ?string {
    $db = connexion();
    $sql = "SELECT course_nom FROM g10_course WHERE course_id = $1";
    pg_prepare($db, "getCourseNomById", $sql);
    $res = pg_execute($db, "getCourseNomById", [$course_id]);
    $row = pg_fetch_assoc($res);
    pg_close($db);
    return $row['course_nom'] ?? null;
}

// Fonction qui récupére le nom d'un coureur à partir de son ID
function getCoureurNomById($coureur_id): ?string {
    $db = connexion();
    $sql = "SELECT coureur_nom FROM g10_coureur WHERE coureur_id = $1";
    pg_prepare($db, "getCoureurNomById", $sql);
    $res = pg_execute($db, "getCoureurNomById", [$coureur_id]);
    $row = pg_fetch_assoc($res);
    pg_close($db);
    return $row['coureur_nom'] ?? null;
}

// Vérifie si une table a été sélectionnée, sinon redirige vers l'accueil(va de session)
if (!isset($_SESSION['table'])) {
    echo "<p style='color:red;'>Aucune table sélectionnée.</p>";
    header("Location: index.php");
    exit;
}

$table = $_SESSION['table']; // Récupère la table active depuis la session
$fonctions = ucfirst($table) . "/fonctions_{$table}.php"; // Construit le chemin du fichier fonctions
$fonctionSelect = "getAll" . ucfirst($table); // Nom de la fonction pour récupérer toutes les données

// Vérifie si le fichier des fonctions existe
if (!file_exists($fonctions)) {
    echo "<p style='color:red;'>Fichier manquant : $fonctions</p>";
    exit;
}
require_once $fonctions; // Inclut le fichier des fonctions

// Vérifie si la fonction de récupération des données existe
if (!function_exists($fonctionSelect)) {
    echo "<p style='color:red;'>La fonction $fonctionSelect n'existe pas.</p>";
    exit;
}

$donnees = call_user_func($fonctionSelect); // Appelle dynamiquement la fonction pour obtenir les données

// Vérifie que les données sont valides (tableau non vide et bien formé)
if (!is_array($donnees) || empty($donnees) || !is_array($donnees[0])) {
    echo "<p style='color:red;'>Erreur : données invalides ou vides.</p>";
    exit;
}
?>

<!-- Style CSS -->
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ccc;
    }
    th {
        background-color: #f8f8f8;
        font-weight: bold;
    }
    tr:hover {
        background-color: #f0f0f0;
    }
    button {
        background-color: #000a8b;
        color: white;
        border: none;
        margin: 0 2px;
        padding: 4px 8px;
    }
    button:hover {
        background-color: #a9a7a7;
    }
    a {
        text-decoration: none;
        color: black;
    }
    h1 {
        font-family: Arial, sans-serif;
        text-align: center;
    }
</style>

<?php
// Titre principal de la page
echo "<h1>Contenu de " . ucfirst($table) . "</h1>";
// Lien de retour à la page d'accueil
echo "<a style='color:blue;' href='index.php'><button>Retour à l'accueil</button></a><br><br>";

// Lien vers la page d'insertion pour la table courante
echo "<a href=\"" . ucfirst($table) . "/insertion_{$table}.php\"><button>Insérer un nouveau $table</button></a><br><br>";

// Définition des clés identifiantes par table
$cles_par_table = [
    'classer' => ['coureur_id', 'edition_id'],
    'coureur' => ['coureur_id'],
    'course' => ['course_id'],
    'edition' => ['edition_id']
];

$cles_identifiantes = $cles_par_table[$table]; // Récupère les clés pour la table active

echo "<table><tr>";
// Affichage de l'en-tête du tableau
foreach (array_keys($donnees[0]) as $colonne) {
    //gestion des clés étrangères
    if ($table === "edition" && $colonne === "course_id") continue; // Masquer course_id dans edition
    if ($table === "classer") {
        if ($colonne === "coureur_id") {
            echo "<th>Coureur</th>"; // Remplace coureur_id par le nom du coureur
            continue;
        }
        if ($colonne === "edition_id") {
            echo "<th>Date édition</th>"; // Remplace edition_id par la date du debut de l'édition
            continue;
        }
    }
    echo "<th>" . ucfirst($colonne) . "</th>"; //cas general
}
echo "<th>Actions</th></tr>"; // Colonne supplémentaire pour les boutons

// Affichage des lignes de données(enregistrement de la table )
foreach ($donnees as $ligne) {
    echo "<tr>";
    foreach ($ligne as $colonne => $val) {

        // Masquer course_id dans edition
        if ($table === "edition" && $colonne === "course_id") {
            continue;
        }

        // Pour la table classer : remplacement des ID par des valeurs significatives 
        if ($table === "classer") {
            if ($colonne === "coureur_id") {
                echo "<td>" . htmlspecialchars(getCoureurNomById($val)) . "</td>";
                continue;
            }
            if ($colonne === "edition_id") {
                echo "<td>" . htmlspecialchars(getEdiDateById($val)) . "</td>";
                continue;
            }
        }

        // Cas général : affiche la valeur brute
        echo "<td>" . htmlspecialchars($val) . "</td>";
    }

    // Génération des boutons d'action pour chaque ligne
    echo "<td>";
    $hiddenInputs = '';
    foreach ($cles_identifiantes as $cle) {
        $hiddenInputs .= '<input type="hidden" name="' . $cle . '" value="' . htmlspecialchars($ligne[$cle]) . '">';
    }

    // Formulaire de détail
    echo '<form action="' . ucfirst($table) . '/detailler_' . $table . '.php" method="post" style="display:inline;">' . $hiddenInputs . '<button type="submit">Détailler</button></form>';
    // Formulaire de modification
    echo '<form action="' . ucfirst($table) . '/modification_' . $table . '.php" method="post" style="display:inline;">' . $hiddenInputs . '<button type="submit">Modifier</button></form>';
    // Formulaire de suppression avec confirmation
    echo '<form action="supprimer.php" method="post" style="display:inline;" onsubmit="return confirm(\'Supprimer cette entrée ?\');">' . $hiddenInputs .'<button type="submit">Supprimer</button></form>';
    echo "</td></tr>";
}
echo "</table>"; // Fin du tableau
?>

