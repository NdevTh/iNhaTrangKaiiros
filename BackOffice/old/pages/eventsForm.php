<?php
require("../fosnotBack/upload/upload.php");

//  si l'utilisateur soumet le formulaire on récupère les données qu'il a postées 
//  et on met à jour les champs correspondant en bdd
if (isset($_POST['eventsupdt'])) {
    $titre = $_POST['eve_title'];
    $description = $_POST['eve_description'];

    $req = "UPDATE `events` SET `eve_title` = '" . $titre . "', `eve_description` = '" . $description . "' WHERE `eve_id` =" . $_GET['id'] . "";
    $db->query($req);
    unset($_POST); // Une fois traitées, on détruit les informations du formulaire

    echo "les données ont bien été modifiées.<a href='index.php?page=evenement'>Retour à la liste</a>";
} else {

    $req = "SELECT * FROM `events` WHERE `eve_id` = '" . $_GET['id'] . "'";

    $res = $db->query($req);

    $tbl = $res->fetchAll();

?>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"><br />
        Titre : <input type="text" name="eve_title" value="<?php echo $tbl[0]['eve_title']; ?>"><br />
        Description : <textarea name="eve_description"><?php echo $tbl[0]['eve_description']; ?></textarea><br />
        <input type="submit" name="eventsupdt" value="Enregistrer">
    </form>

    <form action="" method="POST" enctype="multipart/form-data">
        <p>Modifier les images (Taille maximale: 8MB) :</p>
        <label for="file">Fichier</label>
        <input type="file" name="file" />
        <button type="submit">Enregistrer</button>
    </form>
<?php
}
?>