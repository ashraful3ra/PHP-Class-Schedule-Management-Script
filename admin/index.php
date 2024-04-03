<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel | Class Schedule</title>
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
            width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #a90f0f;
            color: #fff;
        }
        .current-time-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #current-date {
            text-align: left;
            font-size: 18px;
        }
        #current-time {
            text-align: right;
            font-size: 18px;
            color: red;
        }
        .table-container {
            display: flex;
            flex-direction: column; /* Display tables in a column layout */
        }
        .upcoming-classes {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px; /* Add space between tables */
        }
        .finished-classes {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .table-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .action-column {
            text-align: center;
        }
        .action-button {
            padding: 5px 10px;
            border: 1px solid #a90f0f;
            border-radius: 5px;
            text-decoration: none;
            color: #a90f0f;
            cursor: pointer;
        }
        .action-button.delete {
            color: red;
            border-color: red;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            text-decoration: none;
            border: 1px solid #a90f0f;
            padding: 5px 10px;
            border-radius: 5px;
            color: #a90f0f;
            margin-right: 5px;
            cursor: pointer;
        }
        .pagination a:hover {
            background-color: #a90f0f;
            color: #fff;
        }
    </style>
</head>
<body>
    <h2>DigiLearner Class Schedule</h2>
    <div class="pagination">
    <center>
        <a href="/" target="_blank"><< Back Home</a>
        <br><br>
        <a href="add.php">Add New Class Schedule</a>
    </center>
</div>
<br>
    <div class="container">
        <div class="current-time-container">
            <div id="current-date"></div>
            <div id="current-time"></div>
        </div>

        <!-- Upcoming Classes Table -->
        <div class="table-container">
            <div class="table-header">Upcoming Classes</div>
            <table class="upcoming-classes">
                <!-- Table headers here -->
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th>Instructor Name</th>
                        <th>Class Number</th>
                        <th>Class Time</th>
                        <th>Class Date</th>
                        <th>Class Day</th>
                        <th>Action</th> <!-- Add Action column -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../db.php';

                    try {
                        // Create a PDO database connection
                        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

                        // Set the PDO error mode to exception
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Determine the current page for upcoming classes
                        $upcomingPage = isset($_GET['upcoming_page']) ? $_GET['upcoming_page'] : 1;
                        $upcomingPerPage = 10;
                        $upcomingOffset = ($upcomingPage - 1) * $upcomingPerPage;

                        // Query to retrieve upcoming class schedule data with pagination, ordered by id in descending order
                        $sql = "SELECT *,
               DATE_FORMAT(class_date, '%d %M, %Y') as formatted_class_date,
               DATE_FORMAT(class_time, '%h:%i %p') as formatted_class_time,
               DATE_FORMAT(class_date, '%W') as class_day,
               CASE 
                   WHEN class_date = CURDATE() THEN 1
                   ELSE 0
               END AS is_today
        FROM class_schedule 
        WHERE is_finished = 0 
        ORDER BY is_today DESC, class_date ASC, class_time ASC 
        LIMIT :limit OFFSET :offset";

                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':limit', $upcomingPerPage, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $upcomingOffset, PDO::PARAM_INT);
                        $stmt->execute();
                        $upcomingClassSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);


                        // Count total upcoming records for pagination
                        $sqlCount = "SELECT COUNT(*) FROM class_schedule WHERE is_finished = 0";
                        $totalUpcomingRecords = $pdo->query($sqlCount)->fetchColumn();
                        $totalUpcomingPages = ceil($totalUpcomingRecords / $upcomingPerPage);

                        foreach ($upcomingClassSchedules as $schedule) :
                            // Display upcoming class records
                            ?>
                            <tr>
                                <td><?php echo $schedule['course_name']; ?></td>
                                <td><?php echo $schedule['batch_name']; ?></td>
                                <td><?php echo $schedule['instructor']; ?></td>
                                <td><?php echo $schedule['class_no']; ?></td>
                                <td><?php echo $schedule['formatted_class_time']; ?></td>
                                <td><?php echo $schedule['formatted_class_date']; ?></td>
                                <td><?php echo $schedule['class_day']; ?></td>
                                <td class="action-column">
                                <div>
                                    <a href="mark_as_finished.php?id=<?php echo $schedule['id']; ?>" class="action-button">Finished</a>
                                    |
                                    <a href="edit.php?id=<?php echo $schedule['id']; ?>" class="action-button">Edit</a>
                                </div>
                            </td>
                            </tr>
                            <?php
                        endforeach;
                    } catch (PDOException $e) {
                        // Handle database errors
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
            <!-- Pagination for Upcoming Classes -->
            <div class="pagination">
                <?php if ($upcomingPage > 1) : ?>
                    <a href="?upcoming_page=<?php echo ($upcomingPage - 1); ?>">Previous</a>
                <?php endif; ?>
                <?php if ($upcomingPage < $totalUpcomingPages) : ?>
                    <a href="?upcoming_page=<?php echo ($upcomingPage + 1); ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Finished Classes Table -->
        <div class="table-container">
            <div class="table-header">Finished Classes</div>
            <table class="finished-classes">
                <!-- Table headers here -->
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th>Instructor Name</th>
                        <th>Class Number</th>
                        <th>Class Time</th>
                        <th>Class Date</th>
                        <th>Class Day</th>
                        <th>Action</th> <!-- Add Action column -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Determine the current page for finished classes
                        $finishedPage = isset($_GET['finished_page']) ? $_GET['finished_page'] : 1;
                        $finishedPerPage = 20;
                        $finishedOffset = ($finishedPage - 1) * $finishedPerPage;

                        // Query to retrieve finished class schedule data with pagination, ordered by id in descending order
                        $sql = "SELECT *, DATE_FORMAT(class_date, '%d %M, %Y') as formatted_class_date, DATE_FORMAT(class_time, '%h:%i %p') as formatted_class_time, DATE_FORMAT(class_date, '%W') as class_day FROM class_schedule WHERE is_finished = 1 ORDER BY id DESC LIMIT :limit OFFSET :offset";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':limit', $finishedPerPage, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $finishedOffset, PDO::PARAM_INT);
                        $stmt->execute();
                        $finishedClassSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);


                        // Count total finished records for pagination
                        $sqlCount = "SELECT COUNT(*) FROM class_schedule WHERE is_finished = 1";
                        $totalFinishedRecords = $pdo->query($sqlCount)->fetchColumn();
                        $totalFinishedPages = ceil($totalFinishedRecords / $finishedPerPage);

                        foreach ($finishedClassSchedules as $schedule) :
                            // Display finished class records
                            ?>
                            <tr>
                                <td><?php echo $schedule['course_name']; ?></td>
                                <td><?php echo $schedule['batch_name']; ?></td>
                                <td><?php echo $schedule['instructor']; ?></td>
                                <td><?php echo $schedule['class_no']; ?></td>
                                <td><?php echo $schedule['formatted_class_time']; ?></td>
                                <td><?php echo $schedule['formatted_class_date']; ?></td>
                                <td><?php echo $schedule['class_day']; ?></td>
                                <td class="action-column">
                                    <a href="delete_schedule.php?id=<?php echo $schedule['id']; ?>" class="action-button delete">Delete</a>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    } catch (PDOException $e) {
                        // Handle database errors
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
            <!-- Pagination for Finished Classes -->
            <div class="pagination">
                <?php if ($finishedPage > 1) : ?>
                    <a href="?finished_page=<?php echo ($finishedPage - 1); ?>">Previous</a>
                <?php endif; ?>
                <?php if ($finishedPage < $totalFinishedPages) : ?>
                    <a href="?finished_page=<?php echo ($finishedPage + 1); ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // JavaScript code to update the live time with seconds
        function updateLiveTime() {
            const currentDateElement = document.getElementById('current-date');
            const currentTimeElement = document.getElementById('current-time');
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long'
            });
            const formattedTime = currentDate.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            currentDateElement.innerHTML = `${formattedDate}`;
            currentTimeElement.innerHTML = `${formattedTime}`;
        }

        // Update the live time every second
        setInterval(updateLiveTime, 1000);

        // Initial update
        updateLiveTime();
    </script>
</body>
</html>
