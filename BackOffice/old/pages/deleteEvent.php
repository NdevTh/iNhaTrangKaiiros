<?php


$id = $_GET['id'];

$req = "DELETE FROM `events` WHERE `eve_id` = $id";

$db->query($req);
unset($_POST);


echo "les données ont bien été supprimées.<a href='index.php?page=evenement'>Retour à la liste</a>";
