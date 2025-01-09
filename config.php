<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$host = '51.254.109.35:3306';
$dbname = 's6649_bloc3';
$username = 'u6649_Ysi98rp29W';
$password = 'Ndx273b8QNGBE5sj=Ru=EGEA';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
