<?php
$host = 'localhost';
$dbname = 'admissi5_class';
$username = 'admissi5_class';
$password = 'D)NAJ^6ZJ[h4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    die(); // Terminate script execution if unable to connect to the database
}
?>
