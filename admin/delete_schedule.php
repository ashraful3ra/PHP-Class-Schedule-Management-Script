<?php
// Include the database connection file
include '../db.php';

try {
    // Check if the 'id' parameter is provided in the URL
    if (isset($_GET['id'])) {
        // Sanitize and get the 'id' parameter
        $id = intval($_GET['id']);

        // Prepare and execute a SQL query to delete the class schedule record
        $sql = "DELETE FROM class_schedule WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Redirect back to the class schedule page after successful deletion
            header("Location: index.php");
            exit();
        } else {
            echo "Error deleting record.";
        }
    } else {
        echo "Invalid request. 'id' parameter not provided.";
    }
} catch (PDOException $e) {
    // Handle database errors
    echo 'Error: ' . $e->getMessage();
}
?>
