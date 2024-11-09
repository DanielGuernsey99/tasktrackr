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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f4f4f4;
            font-size: 16px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            background-color: #333;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px 0;
        }
        .header h1 {
            font-size: 24px;
        }
        .container {
            display: flex;
            height: calc(100vh - 150px);
        }
        .leftNav {
            width: 200px;
            background-color: #2c2c2c;
            color: white;
            padding: 15px;
        }
        .leftNav a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .leftNav a:hover {
            background-color: #444;
        }
        .mainContent {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            overflow-y: auto;
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
        .calendar-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .calendar-header a {
            padding: 8px 16px;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .calendar-header a:hover {
            background-color: #ddd;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            background-color: #eee;
            padding: 20px;
            border-radius: 5px;
        }
        .calendar-day {
            padding: 15px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .calendar-day:hover {
            background-color: #f5f5f5;
            transform: scale(1.05);
        }
        .calendar-day-header {
            font-weight: bold;
            color: #444;
        }
        .calendar-day span.task-name {
            display: block;
            margin-top: 5px;
            font-size: 14px;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }
        .calendar-day span.task-name:hover {
            text-decoration: underline;
        }
        .task-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .task-details h3 {
            margin-top: 0;
            font-size: 20px;
        }
        .task-details p {
            margin: 5px 0;
        }
        .task-completed {
            text-decoration: line-through;
            color: #888;
        }
        .profileNav p {
            font-weight: bold;
            margin-bottom: 10px;
        }
		.profileNav{
			border-bottom:1px solid black;
		}
    </style>
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
                <a href="home.php">Home</a>
            </div>
            <div class="middleNav">
                <a href="Calendar.php">Calendar</a>
            </div>
            <div class="bottomNav">
                <a href="Members.php">Members</a>
            </div>
        </div>

        <div class="mainContent">
            <div class="calendar-container">
                <?php
                // Fetch tasks for the current month that are not completed
                $conn = new mysqli("localhost", "root", "", "tasktrackr");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $month = isset($_GET['month']) ? $_GET['month'] : date("m");
                $year = isset($_GET['year']) ? $_GET['year'] : date("Y");

                $sql = "SELECT * FROM tasks WHERE task_date LIKE '$year-$month%' AND is_completed = 0 ORDER BY task_date";
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
                    $daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                    foreach ($daysOfWeek as $day) {
                        echo "<div class='calendar-day calendar-day-header'>$day</div>";
                    }

                    $daysInMonth = date("t", strtotime("$year-$month-01"));
                    $startDay = date("w", strtotime("$year-$month-01"));
                    $currentDay = 1;

                    for ($i = 0; $i < $startDay; $i++) {
                        echo "<div class='calendar-day'></div>";
                    }

                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $dateString = "$year-$month-" . str_pad($day, 2, "0", STR_PAD_LEFT);
                        echo "<div class='calendar-day' onclick='showTaskDetails(\"$dateString\")'>$day";

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
            <div id="task-details-container"></div>

        </div>
    </div>

    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>

    <script>
        function showTaskDetails(taskId) {
            console.log("Task ID clicked:", taskId); // Debugging

            const allTaskDetails = document.querySelectorAll('.task-details');
            allTaskDetails.forEach(detail => {
                detail.style.display = "none";
            });

            fetch('fetch_task_details.php?id=' + taskId)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        const taskDetailsHTML = `
                            <div class="task-details" id="task-details-${taskId}" style="display: block;">
                                <h3>${data.task_name}</h3>
                                <p><strong>Description:</strong> ${data.task_description}</p>
                                <p><strong>Due Date:</strong> ${data.task_date}</p>
                                <p><strong>Status:</strong> ${data.is_completed ? "Completed" : "Not Completed"}</p>
                            </div>
                        `;
                        const taskDetailsDiv = document.getElementById('task-details-container');
                        taskDetailsDiv.innerHTML = taskDetailsHTML;
                    }
                })
                .catch(error => console.error('Error fetching task details:', error));
        }
    </script>
</body>
</html>
