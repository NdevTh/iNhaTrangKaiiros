<ul class="menuNav">
    <li class="menuNav acceuil"><a class="" href="index.php?page=accueil"><img class="logo" src="../fosnotFront/image/logo_negatif.png"></a></li>
    <!-- <li><a class="pMenu" href="/page/acceuil.php">COURS </a></li> -->
    <li><a class="pMenu" href="index.php?page=cours">COURS </a></li>
    <li><a class="pMenu" href="index.php?page=evenement">EVENEMENT </a></li>
    <!-- <li><a class="pMenu" href="/index.php?page=inscription">INSCRIPTION </a></li> -->
    <li>
        <?php if (isset($_SESSION['nom'])) { ?>
            <a class="pMenu" href="index.php?page=accueil&action=deco">DECONNECTION</a>
            <!-- Il faut se connecter pour avoir la liste -->
        <?php } ?>
    </li>
</ul>