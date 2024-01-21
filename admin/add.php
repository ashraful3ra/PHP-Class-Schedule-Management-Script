<!DOCTYPE html>
<html>
<head>
    <title>Add Class Schedule</title>
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
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="time"],
        input[type="date"],
        select {
            width: -webkit-fill-available;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        select {
            height: 40px;
        }
        input[type="submit"] {
            background-color: #0c9f13;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #2db133;
        }
    </style>
</head>
<body>
    <h2>Add Class Schedule</h2>
    <div class="container">
        <form action="process_schedule.php" method="POST">
        <label for="batch_name">Course Name:</label>
            <select id="course_select" name="course_select">
                <option value="">Select Course</option>
                <option value="CPA Marketing Premium Course">CPA Marketing Premium Course</option>
                <option value="Professional Data Entry">Professional Data Entry</option>
            </select>
            
            <label for="instructor_name">Instructor Name:</label>
            <select id="instructor" name="instructor">
                <option value="">Select Instructor</option>
                <option value="Ashraful Islam">Ashraful Islam</option>
                <option value="Sadiqul Islam">Sadiqul Islam</option>
                <option value="Hasibur Rahman">Hasibur Rahman</option>
                <option value="Nazmul Islam">Nazmul Islam</option>
                <option value="Reyad Naj">Reyad Naj</option>
            </select>
            
            
            <label for="batch_name">Batch Number:</label>
            <input type="text" id="batch_name" name="batch_name" required><br>

            <label for="class_number">Class Number:</label>
            <select id="class_number" name="class_number">
                <option value="">Select Class No</option>
                <option value="Class 1">Class 1</option>
                <option value="Support Class 1">Support Class 1</option>
                <option value="Class 2">Class 2</option>
                <option value="Support Class 2">Support Class 2</option>
                <option value="Class 3">Class 3</option>
                <option value="Support Class 3">Support Class 3</option>
                <option value="Class 4">Class 4</option>
                <option value="Support Class 4">Support Class 4</option>
                <option value="Class 5">Class 5</option>
                <option value="Support Class 5">Support Class 5</option>
                <option value="Class 6">Class 6</option>
                <option value="Support Class 6">Support Class 6</option>
                <option value="Class 7">Class 7</option>
                <option value="Support Class 7">Support Class 7</option>
                <option value="Class 8">Class 8</option>
                <option value="Support Class 8">Support Class 8</option>
                <option value="Class 9">Class 9</option>
                <option value="Support Class 9">Support Class 9</option>
                <option value="Class 10">Class 10</option>
                <option value="Support Class 10">Support Class 10</option>
                <option value="Class 11">Class 11</option>
                <option value="Support Class 11">Support Class 11</option>
                <option value="Class 12">Class 12</option>
                <option value="Support Class 12">Support Class 12</option>
            </select>


            <label for="class_time">Class Time:</label>
            <input type="time" id="class_time" name="class_time" required><br>

            <label for="class_date">Class Date:</label>
            <input type="date" id="class_date" name="class_date" required><br>

           <br>

            <input type="submit" value="Add Schedule">
        </form>
        
    </div>
</body>
</html>
