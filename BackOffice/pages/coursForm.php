<?php
require("./upload/upload.php");

//  si l'utilisateur soumet le formulaire on récupère les données qu'il a postées 
//  et on met à jour les champs correspondant en bdd
if (isset($_POST['coursupdt'])) {
    $titre = $_POST['edu_title'];
    $description = $_POST['edu_description'];

    $req = "UPDATE `education` SET `edu_title` = '" . $titre . "', `edu_description` = '" . $description . "' WHERE `edu_id` =" . $_GET['id'] . "";
    $db->query($req);
    unset($_POST); // Une fois traitées, on détruit les informations du formulaire

    echo "les données ont bien été modifiées.<a href='index.php?page=cours'>Retour à la liste</a>";
} else {

    $req = "SELECT * FROM `education` WHERE `edu_id` = '" . $_GET['id'] . "'";

    $res = $db->query($req);

    $tbl = $res->fetchAll();

?>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"><br />
        Titre : <input type="text" name="edu_title" value="<?php echo $tbl[0]['edu_title']; ?>"><br />
        Description : <textarea name="edu_description" value=""><?php echo $tbl[0]['edu_description']; ?></textarea><br />
        <input type="submit" name="coursupdt" value="Enregistrer">
    </form>
    <form action="" method="POST" enctype="multipart/form-data">
        <p>Modifier les images (Taille maximale: 8MB) :</p>
        <label for="fichier">Fichier</label>
        <input type="file" name="fichier" />
        <button type="submit">Enregistrer</button>
    </form>

<?php
}
?>