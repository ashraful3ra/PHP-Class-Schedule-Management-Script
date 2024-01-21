<!DOCTYPE html>
<html>
<head>
    <title>Class Schedule</title>
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
            background-color: #017a07;
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
            border: 1px solid #017a07;
            border-radius: 5px;
            text-decoration: none;
            color: #017a07;
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
            border: 1px solid #017a07;
            padding: 5px 10px;
            border-radius: 5px;
            color: #017a07;
            margin-right: 5px;
            cursor: pointer;
        }
        .pagination a:hover {
            background-color: #017a07;
            color: #fff;
        }
        /* Styles for Filter Form */
        .filter-form {
            margin-bottom: 0px;
            text-align: center;
        }

        .filter-form select,
        .filter-form input[type="submit"] {
            padding: 8px 15px;
            margin-right: 10px;
            border: 1px solid #017a07;
            border-radius: 5px;
            background-color: #fff;
            color: #017a07;
            font-size: 16px;
            cursor: pointer;
        }

        .filter-form input[type="submit"] {
            background-color: #017a07;
            color: #fff;
            border-color: #017a07;
        }

        .filter-form select:focus,
        .filter-form input[type="submit"]:hover {
            background-color: #016106;
            border-color: #016106;
            color: #fff;
        }

        .filter-form select {
            min-width: 200px; /* Adjust as needed */
        }
    </style>
</head>
<body>
    <h2>DATA Entry Class Schedule</h2>
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
                        <th>SL</th> <!-- Added Sl No column -->
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th>Instructor Name</th>
                        <th>Class Number</th>
                        <th>Class Time</th>
                        <th>Class Date</th>
                        <th>Class Day</th>
                    </tr>
                </thead>
                <tbody>
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
        
                        // Determine the current page for upcoming classes
                        $upcomingPage = isset($_GET['upcoming_page']) ? $_GET['upcoming_page'] : 1;
                        $upcomingPerPage = 10;
                        $upcomingOffset = ($upcomingPage - 1) * $upcomingPerPage;
        
                        // Get the current date in the format matching your database date format
                        $currentDate = date("Y-m-d");
        
                        // Query to retrieve upcoming class schedule data with pagination, ordered by id in descending order
                        $sql = "SELECT *, DATE_FORMAT(class_date, '%d %M, %Y') as formatted_class_date, DATE_FORMAT(class_time, '%h:%i %p') as formatted_class_time, DATE_FORMAT(class_date, '%W') as class_day FROM class_schedule WHERE is_finished = 0 ORDER BY id DESC LIMIT :limit OFFSET :offset";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':limit', $upcomingPerPage, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $upcomingOffset, PDO::PARAM_INT);
                        $stmt->execute();
                        $upcomingClassSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                        // Count total upcoming records for pagination
                        $sqlCount = "SELECT COUNT(*) FROM class_schedule WHERE is_finished = 0";
                        $totalUpcomingRecords = $pdo->query($sqlCount)->fetchColumn();
                        $totalUpcomingPages = ceil($totalUpcomingRecords / $upcomingPerPage);
        
                        $counter = 1; // Initialize a counter for serial number
                        foreach ($upcomingClassSchedules as $schedule) :
                            // Check if the schedule's date matches the current date
                            $scheduleDate = $schedule['class_date'];
                            $isToday = ($scheduleDate == $currentDate);
        
                            // Apply red and bold styles if it's today's date
                            ?>
                            <tr <?php if ($isToday) echo 'style="color: red; font-weight: bold;"'; ?>>
                                <td><?php echo $counter; ?></td> <!-- Display the serial number -->
                                <td><?php echo $schedule['course_name']; ?></td>
                                <td><?php echo $schedule['batch_name']; ?></td>
                                <td><?php echo $schedule['instructor']; ?></td>
                                <td><?php echo $schedule['class_no']; ?></td>
                                <td><?php echo $schedule['formatted_class_time']; ?></td>
                                <td><?php echo $schedule['formatted_class_date']; ?></td>
                                <td><?php echo $schedule['class_day']; ?></td>
                            </tr>
                            <?php
                            $counter++; // Increment the counter for the next row
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
             
            <!-- Batch Filter Form -->
            <form action="" method="get" class="filter-form">
                 <select name="batch_filter">
                    <option value="">All Batches</option>
                    <?php
                    // Get the currently selected batch filter from the query parameters
                    $batchFilter = isset($_GET['batch_filter']) ? $_GET['batch_filter'] : '';

                    // Query to get distinct batch names
                    $batchSql = "SELECT DISTINCT batch_name FROM class_schedule WHERE is_finished = 1 ORDER BY batch_name";
                    foreach ($pdo->query($batchSql) as $batch) {
                        // Check if the current batch name is the one that was selected
                        $selected = ($batch['batch_name'] == $batchFilter) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($batch['batch_name']) . '" ' . $selected . '>Batch - ' . htmlspecialchars($batch['batch_name']) . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Filter">
            </form>





            <table class="finished-classes">
                <!-- Table headers here -->
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Course Name</th>
                        <th>Batch Name</th>
                        <th>Instructor Name</th>
                        <th>Class Number</th>
                        <th>Class Time</th>
                        <th>Class Date</th>
                        <th>Class Day</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    try {
                        // Handle batch filter
                        $batchFilter = isset($_GET['batch_filter']) ? $_GET['batch_filter'] : '';

                        // Determine the current page for finished classes
                        $finishedPage = isset($_GET['finished_page']) ? $_GET['finished_page'] : 1;
                        $finishedPerPage = 10;
                        $finishedOffset = ($finishedPage - 1) * $finishedPerPage;

                        // Modify the query to include batch filter if selected
                        $batchFilterSql = $batchFilter ? " AND batch_name = :batchFilter" : "";

                        // Query to retrieve finished class schedule data with batch filter and pagination
                        $sql = "SELECT *, DATE_FORMAT(class_date, '%d %M, %Y') as formatted_class_date, DATE_FORMAT(class_time, '%h:%i %p') as formatted_class_time, DATE_FORMAT(class_date, '%W') as class_day FROM class_schedule WHERE is_finished = 1" . $batchFilterSql . " ORDER BY id DESC LIMIT :limit OFFSET :offset";
                        $stmt = $pdo->prepare($sql);
                        if ($batchFilter) {
                            $stmt->bindParam(':batchFilter', $batchFilter, PDO::PARAM_STR);
                        }
                        $stmt->bindParam(':limit', $finishedPerPage, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $finishedOffset, PDO::PARAM_INT);
                        $stmt->execute();
                        $finishedClassSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Count total finished records for pagination (including batch filter)
                        $sqlCount = "SELECT COUNT(*) FROM class_schedule WHERE is_finished = 1" . $batchFilterSql;
                        $countStmt = $pdo->prepare($sqlCount);
                        if ($batchFilter) {
                            $countStmt->bindParam(':batchFilter', $batchFilter, PDO::PARAM_STR);
                        }
                        $countStmt->execute();
                        $totalFinishedRecords = $countStmt->fetchColumn();
                        $totalFinishedPages = ceil($totalFinishedRecords / $finishedPerPage);
                        $counter = 1;
                        foreach ($finishedClassSchedules as $schedule) :
                            // Display finished class records
                            ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $schedule['course_name']; ?></td>
                                <td><?php echo $schedule['batch_name']; ?></td>
                                <td><?php echo $schedule['instructor']; ?></td>
                                <td><?php echo $schedule['class_no']; ?></td>
                                <td><?php echo $schedule['formatted_class_time']; ?></td>
                                <td><?php echo $schedule['formatted_class_date']; ?></td>
                                <td><?php echo $schedule['class_day']; ?></td>
                            </tr>
                            
                            <?php
                            $counter++; 
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
                    <a href="?finished_page=<?php echo ($finishedPage - 1); ?>&batch_filter=<?php echo htmlspecialchars($batchFilter); ?>">Previous</a>
                <?php endif; ?>
                <?php if ($finishedPage < $totalFinishedPages) : ?>
                    <a href="?finished_page=<?php echo ($finishedPage + 1); ?>&batch_filter=<?php echo htmlspecialchars($batchFilter); ?>">Next</a>
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

            // Apply red and bold styles to the current date
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
