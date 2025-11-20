<?php
$host = 'localhost';
$dbname = 'votre_base'; // Remplace par le nom réel de ta base
$user = 'root';
$pass = '';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>