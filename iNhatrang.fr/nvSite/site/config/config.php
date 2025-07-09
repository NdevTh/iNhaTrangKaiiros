<?php
// config/config.php

$host = 'localhost'; // ou l'adresse de ton serveur MySQL
$dbname = 'u824835648_inhatrang';
$user = 'u824835648_irene'; // remplace par ton identifiant MySQL
$pass = 'irene2003';     // remplace par ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
} 