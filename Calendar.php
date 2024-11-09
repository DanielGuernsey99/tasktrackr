<?php
// Start session to access logged-in user's data
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "tasktrackr");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in by looking for the session variable
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Query to get the username from the database
    $sql = "SELECT username FROM users WHERE id = $user_id";
    $result = $conn->query($sql);
    $username = "";
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
    } else {
        $username = "Guest"; // Fallback if no username found
    }
} else {
    $username = "Guest"; // Fallback in case no user is logged in
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        @font-face {
            font-family: 'BitendDemo-Regular';
            src: url(BitendDEMO.otf);
        }
        * {
            font-family: 'BitendDemo-Regular';
        }
        .header {
            width: 100%;
            background-color: black;
            color: white;
            margin: 0px;
            display: flex;
            text-align: center;
            justify-content: center;
            align-items: center;
            font-size: 15px;
        }
        .header h1 {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        .container {
            height: 1119px;
            width: 100%;
        }
        .leftNav {
            height: 100%;
            width: 150px;
            background-color: #989898;
            border: 1px solid black;
            float: left;
        }
        .leftNav a {
            display: block;
            padding-left: 5px;
            margin-bottom: 7px;
            margin-top: 7px;
        }
        .bottomNav {
			display: flex;
            justify-content: center;
            border-top: 1px solid black;
            margin: 0px;
        }
        .mainContent {
            height: 100%;
            margin-top: 0px;
            background-color: #BEBEBE;
        }
        .footer {
            background-color: black;
            color: white;
            height: 50px;
            margin: 0px;
        }
        .footer h1 {
            height: 100%;
            margin: 0px;
            display: flex;
            text-align: center;
            justify-content: center;
            align-items: center;
            font-size: 20px;
        }
        .middleNav {
            display: flex;
            justify-content: center;
            border-top: 1px solid black;
        }
        .topNav {
            display: flex;
            justify-content: center;
            border-top: 1px solid black;
        }
        .profileNav {
			padding-left:45px;
        }
        .profileNav p{
			padding-left:5px;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
            border: 1px solid black;
            background-color: #989898;
        }
        .calendar-day {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .calendar-day-header {
            font-weight: bold;
        }
        .calendar-header {
            padding-top: 25px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .calendar-header a {
            font-size: 18px;
            text-decoration: none;
            color: black;
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .calendar-header a:hover {
            background-color: #ccc;
        }
        .task-completed {
            text-decoration: line-through;
            color: gray;
        }
        .task-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #e0e0e0;
            border-radius: 5px;
        }
        .task-details h3 {
            margin-top: 0;
        }
    </style>
    <script>
        // Function to toggle the task description below the calendar when a task is clicked
        function showTaskDetails(taskId) {
            console.log("Task ID clicked:", taskId); // Debugging

            // Hide any other open task details
            const allTaskDetails = document.querySelectorAll('.task-details');
            allTaskDetails.forEach(detail => {
                detail.style.display = "none";
            });

            // Fetch the task details using AJAX
            fetch('fetch_task_details.php?id=' + taskId)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        // Create or update the task details container
                        const taskDetailsHTML = `
                            <div class="task-details" id="task-details-${taskId}" style="display: block;">
                                <h3>${data.task_name}</h3>
                                <p><strong>Description:</strong> ${data.task_description}</p>
                                <p><strong>Due Date:</strong> ${data.task_date}</p>
                                <p><strong>Status:</strong> ${data.is_completed ? "Completed" : "Not Completed"}</p>
                            </div>
                        `;

                        // Insert the task details below the calendar
                        const taskDetailsDiv = document.getElementById('task-details-container');
                        taskDetailsDiv.innerHTML = taskDetailsHTML;
                    }
                })
                .catch(error => console.error('Error fetching task details:', error));
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>tasktrackr</h1>
    </div>

    <div class="container">
        <div class="leftNav">
            <div class="profileNav">
                <p><?php echo htmlspecialchars($username); ?></p>
				<a href="login/logout.php">Logout</a>
            </div>
            <div class="topNav">
                <a href="http://localhost/tasktrackr/home.php"><u>Home</u></a>
            </div>
            <div class="middleNav">
                <a href="http://localhost/tasktrackr/Calendar.php"><u>Calendar</u></a>
            </div>
            <div class="bottomNav">
                <a href="http://localhost/tasktrackr/Members.php"><u>Members</u></a>
            </div>
        </div>

        <div class="mainContent">
            <div class="calendarContainer">
                <?php
                // Fetch tasks for the current month that are not completed
                $conn = new mysqli("localhost", "root", "", "tasktrackr");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get the current month and year, or navigate through GET parameters
                $month = isset($_GET['month']) ? $_GET['month'] : date("m");
                $year = isset($_GET['year']) ? $_GET['year'] : date("Y");

                // Get the month and year for the navigation links
                $currentMonth = date("Y-m", strtotime("$year-$month-01"));

                // Fetch tasks for the current month that are not completed
                $sql = "SELECT * FROM tasks WHERE task_date LIKE '$currentMonth%' AND is_completed = 0 ORDER BY task_date";
                $result = $conn->query($sql);

                $tasks_by_date = [];
                while ($row = $result->fetch_assoc()) {
                    $tasks_by_date[$row['task_date']][] = $row;
                }

                $conn->close();
                ?>

                <!-- Calendar Header with Month Navigation -->
                <div class="calendar-header">
                    <a href="?month=<?php echo ($month == 1) ? 12 : $month - 1; ?>&year=<?php echo ($month == 1) ? $year - 1 : $year; ?>">Prev</a>
                    <div><?php echo date("F Y", strtotime("$year-$month-01")); ?></div>
                    <a href="?month=<?php echo ($month == 12) ? 1 : $month + 1; ?>&year=<?php echo ($month == 12) ? $year + 1 : $year; ?>">Next</a>
                </div>

                <!-- Calendar Grid -->
                <div class="calendar">
                    <?php
                    // Display the days of the week headers
                    $daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                    foreach ($daysOfWeek as $day) {
                        echo "<div class='calendar-day calendar-day-header'>$day</div>";
                    }

                    // Get the number of days in the current month
                    $daysInMonth = date("t", strtotime("$year-$month-01"));
                    $startDay = date("w", strtotime("$year-$month-01"));
                    $currentDay = 1;

                    // Create empty cells before the first day of the month
                    for ($i = 0; $i < $startDay; $i++) {
                        echo "<div class='calendar-day'></div>";
                    }

                    // Loop through each day in the month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dateString = "$year-$month-" . str_pad($day, 2, "0", STR_PAD_LEFT);
                        echo "<div class='calendar-day' onclick='showTaskDetails(\"$dateString\")'>$day";

                        // Check if there are any tasks for this day
                        if (isset($tasks_by_date[$dateString])) {
                            foreach ($tasks_by_date[$dateString] as $task) {
                                $taskName = $task['task_name'];
                                $taskId = $task['id'];  // Task ID
                                echo "<br><span class='task-name' onclick='showTaskDetails($taskId)'>$taskName</span>";
                            }
                        }

                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Task Details Section (Below the Calendar) -->
            <div id="task-details-container" style="margin-top: 20px;"></div>

        </div>
    </div>

    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>
</body>
</html>
