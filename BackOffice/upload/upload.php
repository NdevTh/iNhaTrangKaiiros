<?php

// Upload d'images pour les évènements
if (isset($_FILES['file'])) {
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));
    //Tableau des extensions que l'on accepte
    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    //Taille max que l'on accepte
    $maxSize = 400000;

    if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

        // on déplace le fichier vers le bon dossier
        move_uploaded_file($tmpName, '../fosnotFront/image/galerie/' . $name);
        // on insère la requete
        $req = $db->prepare("UPDATE `events` SET `eve_directory` = '" . $name . "' WHERE `eve_id` =" . $_GET['id'] . "");
        $req->execute([$name]);
        echo "Image enregistrée";
    } else {
        echo "Une erreur est survenue";
    }
}

// upload pour les cours
if (isset($_FILES['fichier'])) {
    $tmpName = $_FILES['fichier']['tmp_name'];
    $name = $_FILES['fichier']['name'];
    $size = $_FILES['fichier']['size'];
    $error = $_FILES['fichier']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));
    //Tableau des extensions que l'on accepte
    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    //Taille max que l'on accepte
    $maxSize = 400000;

    if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {

        // on déplace le fichier vers le bon dossier
        move_uploaded_file($tmpName, '../fosnotFront/image/' . $name);
        // on insère la requete
        $req = $db->prepare("UPDATE `education` SET `edu_directory` = '" . $name . "' WHERE `edu_id` =" . $_GET['id'] . "");
        $req->execute([$name]);
        echo "Image enregistrée";
    } else {
        echo "Une erreur est survenue";
    }
}
