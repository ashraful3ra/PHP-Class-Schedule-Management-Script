<?php
// Database configuration
$host = 'localhost'; // Database host
$dbname = 'data-class'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve form data
    $course_name = isset($_POST['course_select']) ? $_POST['course_select'] : '';
    $class_no = isset($_POST['class_number']) ? $_POST['class_number'] : '';
    $instructor = isset($_POST['instructor']) ? $_POST['instructor'] : '';
    $batch_name = $_POST['batch_name'];
    $class_time = $_POST['class_time'];
    $class_date = $_POST['class_date'];
    $class_topics = $_POST['class_topics']; // Add this line to retrieve class topics

    // Check if a similar record already exists
    $checkSql = "SELECT COUNT(*) FROM class_schedule
                 WHERE course_name = :course_name
                 AND batch_name = :batch_name
                 AND class_time = :class_time
                 AND class_no = :class_no
                 AND class_no = :instructor
                 AND class_date = :class_date";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':course_name', $course_name);
    $checkStmt->bindParam(':class_no', $class_no);
    $checkStmt->bindParam(':instructor', $instructor);
    $checkStmt->bindParam(':batch_name', $batch_name);
    $checkStmt->bindParam(':class_time', $class_time);
    $checkStmt->bindParam(':class_date', $class_date);
    $checkStmt->execute();
    $existingRecords = $checkStmt->fetchColumn();

    if ($existingRecords > 0) {
        // Redirect back to the form with a message indicating duplicate data
        header('Location: index.php?error=duplicate');
        exit();
    }

    // If no duplicate record found, proceed with inserting
    $insertSql = "INSERT INTO class_schedule (course_name, class_no, instructor, batch_name, class_time, class_date, class_topics)
                  VALUES (:course_name, :class_no, :instructor, :batch_name, :class_time, :class_date, :class_topics)";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindParam(':course_name', $course_name);
    $insertStmt->bindParam(':class_no', $class_no);
    $insertStmt->bindParam(':instructor', $instructor);
    $insertStmt->bindParam(':batch_name', $batch_name);
    $insertStmt->bindParam(':class_time', $class_time);
    $insertStmt->bindParam(':class_date', $class_date);
    $insertStmt->bindParam(':class_topics', $class_topics); // Add this line to bind class topics

    // Execute the SQL statement
    $insertStmt->execute();

    // Redirect back to the form or to a success page
    header('Location: index.php?success=inserted');
    exit();
} catch (PDOException $e) {
    // Handle database errors
    echo 'Error: ' . $e->getMessage();
}
?>