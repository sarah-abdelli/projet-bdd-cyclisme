<?php
require 'fonctions_classer.php';
echo"<h2> Tests des fonctions de la table classer </h2>"; 
/* Test de connexion() */
echo"<b> 1) Connexion à la base de donnée Palmares</b><br>";
$ptrDB = connexion();
if ($ptrDB) {
    echo " Connexion réussie !<br />";
} else {
    echo " Erreur de connexion !<br />";
    
}
/****************************************************************************/
/* Test de getClasserById() */

echo "<br />";
$id_coureur = 11;  //choisir des valeur differente de ID coureur a tester 
$id_Edition = 20;  //choisir des valeur differente de ID edition a tester 
echo " <b>2.1) Le classement du coureur $id_coureur de l'edition $id_Edition :</b> <br />";
afficherTableauSimple(getClasserById($id_coureur,$id_Edition));
echo "<br />";

$id_coureur = 11;  
$id_Edition = 20;
echo "<br />";
echo "<b>2.2) Le classement du coureur $id_coureur de l'edition $id_Edition :</b> <br />";
afficherTableauSimple(getClasserById($id_coureur,$id_Edition ));
echo "<br />";

/********************************************************************************/
/* Test de insertClasser() */
echo "<br />";
echo " <b>3) inserer un nouveau classement du coureur $id_coureur à ledition  $id_Edition  :</b> <br />";
echo " <br />";
$id_coureur = 30;  
$id_Edition = 30;
//ce classement sera le meme du jour 1 à l'edition 6
$g10_classer = getClasserById('11','20');
$g10_classer['coureur_id']=$id_coureur;
$g10_classer['edition_id']=$id_Edition;
afficherTableauSimple(insertClasser($g10_classer));
echo " <br />";

echo " <br />";
afficherTableauSimple(getClasserById($id_coureur,$id_Edition));
echo " <br />";

/************************************************************************************/
/* Test de updateClasser() */
echo "<br />";
$id_coureur = 30;  
$id_Edition = 30;
echo " <b>4) mettre à jour le classement du coureur $id_coureur à edition $id_Edition :</b> <br />";
echo " <br />";
$g10_classer = getClasserById($id_coureur,$id_Edition);

echo"avant : <br/>";
afficherTableauSimple($g10_classer);
echo " <br />";
echo " <br />";
echo"Apres avoir modifié le temps : <br/>";
$g10_classer['temps'] = 10;
afficherTableauSimple(updateClasser($g10_classer));
echo " <br />";

/**************************************************************************************/
/* Test de deleteClasser() */
echo "<br />";
$id_coureur = 30;  
$id_Edition = 3;
echo " <b>5)Supprimer le classement du coureur $id_coureur à edition $id_Edition:</b> <br />";
echo " <br />";

deleteClasser($id_coureur,$id_Edition);
echo " <br />";
afficherTableauSimple(getClasserById($id_coureur,$id_Edition));
echo " <br />";

/****************************************************************************************/
/* Test de getAllClasser() */
echo "<br />";
echo " <b>6)affichage de tous les classement des coureur dans la totalités des editions :</b> <br />";
echo " <br />";
afficherTableau(getAllClasser());
echo " <br />";


 ?>
