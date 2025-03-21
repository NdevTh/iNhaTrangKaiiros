<?php
session_start();

require("./bdd/bdd.php");

if (!isset($_SESSION['nom'])) {
    $_GET['page'] = "login";
}

// si l'utilisateur soumet le formulaire de login, on vérifie que les infos correspondent en bdd
if (isset($_POST['subbtn'])) {
    // requete
    $req = "SELECT * FROM `users` WHERE `use_login` = '" . $_POST['use_login'] . "' AND `use_passw` = '" . $_POST['use_passw'] . "'";
    // lancer la requete et stocker le resultat dans $res
    $res = $db->query($req);
    // transformer la requete en tableau
    $tbl = $res->fetchAll();

    if (count($tbl) > 0) {
        $_SESSION['nom'] = $tbl[0]['use_login'];
    } else {
        echo "Erreur, merci de vérifier votre login/mot de passe.";
    }
    // Vérifier en BDD si l'association login/ mot de passe est correct en BDD pour créer la session. Sinon, prévenir d'un souci.
}

// déconnection et effacement de ce qui se trouvait dans la session + redirection
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'deco') {
        session_destroy();
        unset($_SESSION['nom']);
        $_GET['page'] = "login";
    }
}

if (!isset($_GET['page'])) $_GET['page'] = "accueil"; // si la page existe pas on envoie au page par defaut
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../fosnotFront/styles/style.css">
    <!-- <script type="text/javascript" src="../js/scriptLog.js"></script> -->
    <link rel="stylesheet" type="text/css" href="../fosnotFront/styles/styleLog.css">
    <link rel="stylesheet" type="text/css" href="./styleBack.css">
</head>

<body class="fontAcc">
    <header class="menu">
        <?php include('../fosnotBack/menu.php'); ?>
    </header>

    <main class="corps">
        <?php
        if (file_exists('pages/' . $_GET['page'] . '.php')) {
            include('pages/' . $_GET['page'] . '.php');
        }
        ?>

    </main>

    <footer class="foot">
        <?php include('../fosnotBack/footer.php'); ?>
    </footer>

</body>

</html>