<?php
require_once 'fonctions_coureur.php';

echo"<h2> Tests des fonctions de la table coureur </h2>"; 
/* Test de connexion() */
echo"<b> 1) Connexion à la base de donnée Palmares</b><br>";
$ptrDB = connexion();
if ($ptrDB) {
    echo " Connexion réussie à la base de données !<br />";
} else {
    echo " Erreur de connexion à la base de données !<br />";
}
/****************************************************************************/
/* Test de getCoureurById() */
echo " <br />";
$idCoureur = 2;  //choisir des valeur differente de ID pour tester 
echo " <b>2.1) Les informations du coureur $idCoureur :</b> <br />";
echo " <br />";
afficherTableauSimpleC(getCoureurById($idCoureur));
echo " <br />";

$idCoureur=39;
echo " <b>2.2) Les informations du coureur $idCoureur :</b> <br />";
echo " <br />";
afficherTableauSimpleC(getCoureurById($idCoureur));
echo " <br />";

/****************************************************************************/
/* Test de insertCoureur() */

echo " <br />";
echo " <b>3) inserer un nouveau coureur :</b> <br />";
$idCoureur=2; 
$g10_coureur = getCoureurById($idCoureur);
$idCoureur=39; //la valeur de ID du coureur a inserer
$g10_coureur['coureur_id'] = $idCoureur;
afficherTableauSimpleC(insertCoureur($g10_coureur));
echo " <br />";
echo " <br />";
afficherTableauSimpleC(getCoureurById($idCoureur));
echo " <br />";

/****************************************************************************/
/* Test de updateCoureur() */

echo " <br />";
$idCoureur=39; //la valeur de l'ID a modifié
echo " <b>4) mettre à jour les informations du coureur $idCoureur :</b> <br />";
$g10_coureur = getCoureurById($idCoureur);
echo"avant : <br/>";
afficherTableauSimpleC($g10_coureur);
echo " <br />";
echo " <br />";
$g10_coureur['coureur_prenom'] = 'simon';
$g10_coureur['nationalite'] = 'francais';
echo"Apres avoir modifié son prenom et sa nationalite : <br/>";
afficherTableauSimpleC(updateCoureur($g10_coureur));
echo " <br />";

/****************************************************************************/
/* Test de deleteCoureur() */

echo " <br />";
echo " <b>5)Supprimer un coureur :</b> <br />";
$idCoureur=39; //la valeur de l'ID a supprimer
deleteCoureur($idCoureur);
afficherTableauSimpleC(getCoureurById($idCoureur));
echo " <br />";

/****************************************************************************/
/* Test de getAllCoureur() */

echo " <br />";
echo " <b>6)affichage de tous les Palmares des trois course :</b> <br />";
afficherTableauC(getAllCoureur());
echo " <br />";


 ?>
