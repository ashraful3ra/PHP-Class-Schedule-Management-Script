<!DOCTYPE html>
<html>
<head>
    <title>Class Schedule</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Hind Siliguri', Arial, sans-serif;
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
        /* Styles for Filter Form */
        .filter-form {
            margin-bottom: 0px;
            text-align: center;
        }

        .filter-form select,
        .filter-form input[type="submit"] {
            padding: 8px 15px;
            margin-right: 10px;
            border: 1px solid #a90f0f;
            border-radius: 5px;
            background-color: #fff;
            color: #a90f0f;
            font-size: 16px;
            cursor: pointer;
        }

        .filter-form input[type="submit"] {
            background-color: #a90f0f;
            color: #fff;
            border-color: #a90f0f;
        }

        .filter-form select:focus,
        .filter-form input[type="submit"]:hover {
            background-color: #FFF;
            border-color: #a90f0f;`
            color: #fff;
        }

        .filter-form select {
            min-width: 200px; /* Adjust as needed */
        }
        .tooltip {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 999;
        }
        
        /* Styles for Live Search */
        .live-search-container {
            margin-top: 20px;
            text-align: center;
        }
        
        .live-search-container label {
            margin-right: 10px;
        }
        
        .live-search-container input[type="text"] {
            padding: 8px 15px;
            border: 1px solid #a90f0f;
            border-radius: 5px;
            font-size: 16px;
        }

    </style>
</head>
<body>
    <h2>DigiLearner Class Schedule</h2>
    <div class="container">
        <div class="current-time-container">
            <div id="current-date"></div>
            <div id="current-time"></div>
        </div>

        <!-- Upcoming Classes Table -->
        <div class="table-container">
            <div class="table-header">Upcoming Classes</div>
            <!-- Live Search -->
            <div class="live-search-container">
                <input type="text" id="live-search" placeholder="Search">
            </div>

            
            
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
                include 'db.php';
                
                
                // Bangla day names array
                $banglaDays = array(
                    "Sunday" => "রবিবার",
                    "Monday" => "সোমবার",
                    "Tuesday" => "মঙ্গলবার",
                    "Wednesday" => "বুধবার",
                    "Thursday" => "বৃহস্পতিবার",
                    "Friday" => "শুক্রবার",
                    "Saturday" => "শনিবার"
                );


                try {
                    $upcomingPage = isset($_GET['upcoming_page']) ? $_GET['upcoming_page'] : 1;
                    $upcomingPerPage = 10;
                    $upcomingOffset = ($upcomingPage - 1) * $upcomingPerPage;

                    $currentDate = date("Y-m-d");

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

                    $sqlCount = "SELECT COUNT(*) FROM class_schedule WHERE is_finished = 0";
                    $totalUpcomingRecords = $pdo->query($sqlCount)->fetchColumn();
                    $totalUpcomingPages = ceil($totalUpcomingRecords / $upcomingPerPage);

                    $counter = 1;
                    foreach ($upcomingClassSchedules as $schedule) {
                        $scheduleDate = $schedule['class_date'];
                        $isToday = ($scheduleDate == $currentDate);

                        $classTopic = $schedule['class_topics'];
                        
                        // Convert class day to Bangla
                        $classDay = $banglaDays[$schedule['class_day']];
                        ?>
                        <tr <?php if ($isToday) echo 'style="color: green; font-weight: bold;"'; ?>>
                            <td><?php echo $counter; ?></td>
                            <td><?php echo $schedule['course_name']; ?></td>
                            <td><?php echo $schedule['batch_name']; ?></td>
                            <td><?php echo $schedule['instructor']; ?></td>
                            <td class="class-number" data-topic="<?php echo htmlspecialchars($classTopic); ?>"><?php echo $schedule['class_no']; ?></td>
                            <td><?php echo $schedule['formatted_class_time']; ?></td>
                            <td><?php echo $schedule['formatted_class_date']; ?></td>
                            <td><?php echo $classDay; ?></td>
                        </tr>
                        <?php
                        $counter++;
                    }
                } catch (PDOException $e) {
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
                            $classTopic = $schedule['class_topics'];
                            ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $schedule['course_name']; ?></td>
                                <td><?php echo $schedule['batch_name']; ?></td>
                                <td><?php echo $schedule['instructor']; ?></td>
                                <td class="class-number" data-topic="<?php echo htmlspecialchars($classTopic); ?>"><?php echo $schedule['class_no']; ?></td>
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
    // JavaScript code to display class topic tooltip
    document.addEventListener("DOMContentLoaded", function () {
        function showClassTopicTooltip(event) {
            const classNumberCell = event.currentTarget;
            const classTopic = classNumberCell.getAttribute("data-topic");

            // Check if the cell has a class topic
            if (classTopic) {
                const tooltip = document.createElement("div");
                tooltip.className = "tooltip";
                tooltip.textContent = classTopic;

                classNumberCell.appendChild(tooltip);

                // Position the tooltip relative to the class number cell
                const cellRect = classNumberCell.getBoundingClientRect();
                const tooltipRect = tooltip.getBoundingClientRect();

                tooltip.style.top = cellRect.top + cellRect.height + "px";
                tooltip.style.left = cellRect.left + cellRect.width / 2 - tooltipRect.width / 2 + "px";

                // Show the tooltip
                tooltip.style.display = "block";

                // Remove the tooltip when the mouse leaves the cell
                classNumberCell.addEventListener("mouseleave", function () {
                    tooltip.remove();
                });
            }
        }

        // Attach event listeners to class number cells for hovering (Upcoming Classes)
        const upcomingClassNumberCells = document.querySelectorAll(".upcoming-classes tbody td.class-number");
        upcomingClassNumberCells.forEach((cell) => {
            cell.addEventListener("mouseenter", showClassTopicTooltip);
        });

        // Attach event listeners to class number cells for hovering (Finished Classes)
        const finishedClassNumberCells = document.querySelectorAll(".finished-classes tbody td.class-number");
        finishedClassNumberCells.forEach((cell) => {
            cell.addEventListener("mouseenter", showClassTopicTooltip);
        });
    });
</script>


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
        
        // JavaScript code for live search
        document.addEventListener("DOMContentLoaded", function () {
            const liveSearchInput = document.getElementById('live-search');
            liveSearchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase().trim();
                const upcomingClassRows = document.querySelectorAll('.upcoming-classes tbody tr');
        
                upcomingClassRows.forEach(row => {
                    const courseName = row.cells[1].textContent.toLowerCase();
                    const batchName = row.cells[2].textContent.toLowerCase();
                    const instructorName = row.cells[3].textContent.toLowerCase();
                    const classNumber = row.cells[4].textContent.toLowerCase();
                    const classTime = row.cells[5].textContent.toLowerCase();
                    const classDate = row.cells[6].textContent.toLowerCase();
                    const classDay = row.cells[7].textContent.toLowerCase();
        
                    if (courseName.includes(searchTerm) || batchName.includes(searchTerm) || instructorName.includes(searchTerm) ||
                        classNumber.includes(searchTerm) || classTime.includes(searchTerm) || classDate.includes(searchTerm) ||
                        classDay.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

    </script>
</body>
</html>
