<?php
require_once 'fonctions_edition.php';
$ptrDB = connexion();

echo"<h2> Tests des fonctions de la table edition </h2>"; 
/* Test de connexion() */
echo"<b> 1) Connexion à la base de donnée Palmares</b><br>";
if ($ptrDB) {
    echo " Connexion réussie à la base de données !<br />";
} else {
    echo " Erreur de connexion à la base de données !<br />";
}

/* Test de getEditionById() */
/****************************************************************************/
echo " <br />";
$id__edition=2;//choisir des valeur differente de ID a tester 
echo " <b>2.1) Les informations de l'edition $id__edition :</b> <br />";
echo " <br />";
afficherTableauSimpleE(getEditionById($id__edition));
echo " <br />";
$id__edition=31;
echo " <b>2.2) Les informations de l'edition $id__edition :</b> <br />";
echo " <br />";
afficherTableauSimpleE(getEditionById($id__edition));
echo " <br />";

/****************************************************************************/
/* Test de insertEdition() */

echo " <br />";
echo " <b>3) inserer une nouvelle edition :</b> <br />";
$id__edition=2;
$g10_edition = getEditionById($id__edition);
$id__edition=31; //la valeur de ID de l'edition a inserer
$g10_edition['edition_id'] = $id__edition;
afficherTableauSimpleE(insertEdition($g10_edition));
echo " <br />";
echo " <br />";
afficherTableauSimpleE(getEditionById($id__edition));
echo " <br />";

/****************************************************************************/
/* Test de updateEdition() */

echo " <br />";
$id__edition=31; //la valeur de l'ID a modifié
echo " <b>4) mettre à jour les informations de l'edition $id__edition :</b> <br />";
$g10_edition = getEditionById($id__edition);
echo"avant : <br/>";
afficherTableauSimpleE($g10_edition);
echo " <br />";
echo " <br />";
$g10_edition['edi_datedebut'] = '2025-07-06';
$g10_edition['edi_datefin'] = '2025-07-06';
echo"Apres avoir modifié sa date de debut et fin : <br/>";
afficherTableauSimpleE(updateEdition($g10_edition));
echo " <br />";

/****************************************************************************/
/* Test de deleteEdition() */
echo " <b>5)Supprimer une edition :</b> <br />";
echo " <br />";
$id__edition=31;//la valeur de l'ID a supprimer
deleteEdition($id__edition);
afficherTableauSimpleE(getEditionById($id__edition));
echo " <br />";

/****************************************************************************/
/* Test de getAllEdition() */

echo " <br />";
echo " <b>6)affichage de toutes les edition des trois course :</b> <br />";
afficherTableauE(getAllEdition());
echo " <br />";


 ?>
