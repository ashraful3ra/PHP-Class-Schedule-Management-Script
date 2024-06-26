<?php
// Include the database connection file
include '../db.php';

try {
    // Calculate the current time
    $currentTime = date('H:i:s');

    // Query to find and mark a single class as finished by its ID
    $sql = "UPDATE class_schedule SET is_finished = 1 WHERE id = :classID AND TIME(class_date) <= :currentTime AND is_finished = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':classID', $classID, PDO::PARAM_INT);
    $stmt->bindParam(':currentTime', $currentTime, PDO::PARAM_STR);
    $stmt->execute();

    $affectedRows = $stmt->rowCount();
    
    if ($affectedRows > 0) {
        echo "Successfully marked class with ID $classID as finished.";
    } else {
        echo "Class with ID $classID was not marked as finished (already finished or not found).";
    }

} catch (PDOException $e) {
    // Handle database errors
    echo 'Error: ' . $e->getMessage();
}
?>
