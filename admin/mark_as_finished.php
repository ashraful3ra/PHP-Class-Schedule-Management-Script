<?php
include '../db.php';

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the class ID from the URL query parameter
    $classId = $_GET['id'];

    // Query to mark the class as finished
    $sql = "UPDATE class_schedule SET is_finished = 1 WHERE id = :classId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the main page or any other appropriate page
    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    // Handle database errors
    echo 'Error: ' . $e->getMessage();
}
?>
