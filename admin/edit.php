<!DOCTYPE html>
<html>
<head>
    <title>Edit Class Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .container {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        /* Rest of your styles */
        
        /* CSS for form styling */
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-weight: bold;
        }
        
        select, input[type="text"], input[type="time"], input[type="date"] {
            width: -webkit-fill-available; /* Full width input fields */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 5px;
        }
        
        input[type="submit"] {
            width: 100%; /* Full width submit button */
            padding: 10px 20px;
            background-color: #017a07;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
        }
        
        input[type="submit"]:hover {
            background-color: #014a04;
        }
    </style>
</head>
<body>
    <h2>Edit Class Schedule</h2>
    <div class="container">
        <?php
        // Database configuration
        $host = 'localhost'; // Database host
$dbname = 'data-class'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

        try {
            // Create a PDO database connection
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

            // Set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                // Handle POST request to update existing record
                $recordId = $_POST['id'];
                $course_name = isset($_POST['course_select']) ? $_POST['course_select'] : '';
                $class_no = isset($_POST['class_number']) ? $_POST['class_number'] : '';
                $instructor = isset($_POST['instructor']) ? $_POST['instructor'] : '';
                $batch_name = $_POST['batch_name'];
                $class_time = $_POST['class_time'];
                $class_date = $_POST['class_date'];

                // Add validation here if needed

                // Update the existing record
                $updateSql = "UPDATE class_schedule
                              SET course_name = :course_name,
                                  class_no = :class_no,
                                  instructor = :instructor,
                                  batch_name = :batch_name,
                                  class_time = :class_time,
                                  class_date = :class_date
                              WHERE id = :id";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':course_name', $course_name);
                $updateStmt->bindParam(':class_no', $class_no);
                $updateStmt->bindParam(':instructor', $instructor);
                $updateStmt->bindParam(':batch_name', $batch_name);
                $updateStmt->bindParam(':class_time', $class_time);
                $updateStmt->bindParam(':class_date', $class_date);
                $updateStmt->bindParam(':id', $recordId, PDO::PARAM_INT);
                $updateStmt->execute();

                // Redirect to a success page or wherever you want
                header('Location: index.php?success=updated');
                exit();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                // Handle GET request to display edit form
                $recordId = $_GET['id'];
                $selectSql = "SELECT * FROM class_schedule WHERE id = :id";
                $selectStmt = $pdo->prepare($selectSql);
                $selectStmt->bindParam(':id', $recordId, PDO::PARAM_INT);
                $selectStmt->execute();
                $record = $selectStmt->fetch(PDO::FETCH_ASSOC);

                if ($record) {
                    // Render the edit form
                    ?>
                    <form method="POST" action="edit.php">
                        <input type="hidden" name="id" value="<?= $record['id'] ?>">

                        <div class="form-group">
                            <label for="instructor">Instructor Name:</label>
                            <select id="instructor" name="instructor">
                                <option value="">Select Instructor</option>
                                <option value="Ashraful Islam" <?= ($record['instructor'] == 'Ashraful Islam') ? 'selected' : ''; ?>>Ashraful Islam</option>
                                <option value="Sadiqul Islam" <?= ($record['instructor'] == 'Sadiqul Islam') ? 'selected' : ''; ?>>Sadiqul Islam</option>
                                <option value="Hasibur Rahman" <?= ($record['instructor'] == 'Hasibur Rahman') ? 'selected' : ''; ?>>Hasibur Rahman</option>
                                <option value="Nazmul Islam" <?= ($record['instructor'] == 'Nazmul Islam') ? 'selected' : ''; ?>>Nazmul Islam</option>
                                <option value="Reyad Naj" <?= ($record['instructor'] == 'Reyad Naj') ? 'selected' : ''; ?>>Reyad Naj</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course_select">Course Name:</label>
                            <select id="course_select" name="course_select">
                                <option value="">Select Course</option>
                                <option value="CPA Marketing Premium Course" <?= ($record['course_name'] == 'CPA Marketing Premium Course') ? 'selected' : ''; ?>>CPA Marketing Premium Course</option>
                                <option value="Professional Data Entry" <?= ($record['course_name'] == 'Professional Data Entry') ? 'selected' : ''; ?>>Professional Data Entry</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class_number">Class Number:</label>
                            <select id="class_number" name="class_number">
                                <option value="">Select Class No</option>
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value="Class <?= $i ?>" <?= ($record['class_no'] == "Class $i") ? 'selected' : ''; ?>>Class <?= $i ?></option>
                                    <option value="Support Class <?= $i ?>" <?= ($record['class_no'] == "Support Class $i") ? 'selected' : ''; ?>>Support Class <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="batch_name">Batch Name:</label>
                            <input type="text" id="batch_name" name="batch_name" value="<?= $record['batch_name'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="class_time">Class Time:</label>
                            <input type="time" id="class_time" name="class_time" value="<?= $record['class_time'] ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="class_date">Class Date:</label>
                            <input type="date" id="class_date" name="class_date" value="<?= $record['class_date'] ?>" required>
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Update">
                        </div>
                    </form>
                    <?php
                } else {
                    echo 'Record not found.';
                }
            } else {
                // Invalid request
                echo 'Invalid request.';
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo 'Error: ' . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
