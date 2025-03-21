<?php

try {
    $db = new PDO('mysql:host=localhost;dbname=fosnotv1;charset=utf8mb4', 'root', "");
} catch (PDOException $e) {
    die('Erreur connexion : ' . $e->getMessage());
}
