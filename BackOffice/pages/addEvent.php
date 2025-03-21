<?php
// require("../bdd/bdd.php");
// require("../fosnotBack/upload/upload.php");
?>

<form method="POST" action="" enctype="multipart/form-data">
    Titre : <input type=" text" name="eve_title" value=""><br />
    Date : <input type="date" name="eve_date" value=""><br />
    Description : <textarea name="eve_description"></textarea><br />
    <p>Sélectionner une image (Taille maximale: 8MB) :</p>
    <label for="eve_directory">Fichier</label>
    <input type="file" name="eve_directory" />
    <input type="submit" name="addevent" value="Enregistrer">
</form>

<?php
if (isset($_POST['addevent'])) {

    $titre = $_POST['eve_title'];
    $image = $_FILES['eve_directory'];
    $description = $_POST['eve_description'];
    $date = date('Y-m-d H:i:s');
    $tmpName = $_FILES['eve_directory']['tmp_name'];
    $name = $_FILES['eve_directory']['name'];
    $size = $_FILES['eve_directory']['size'];
    $error = $_FILES['eve_directory']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));
    //Tableau des extensions que l'on accepte
    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    //Taille max que l'on accepte
    $maxSize = 400000;

    if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
        // on déplace le fichier vers le bon dossier
        move_uploaded_file($tmpName, '../fosnotFront/image/galerie/' . $name);

        $req = "INSERT INTO `events` (`eve_title`, `eve_date`, `eve_type`, `eve_description`, `eve_directory`) VALUES ('" . $titre . "', '" . $date . "', 'futur', '" . $description . "', '" . $name . "')";
        $db->query($req);

        echo "les données ont bien été insérées.<a href='index.php?page=evenement'>Retour à la liste</a>";
    } else {
        echo "Une erreur est survenue";
    }
}
// date('d/m/Y H:i:s') -> date à l'heure actuelle

?>