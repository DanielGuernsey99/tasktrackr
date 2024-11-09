<?php
session_start();  // Make sure session is started

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username']; // Get the username from the session
} else {
    $username = "Guest"; // Fallback if no user is logged in
}
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
            margin: 0;
        }
        .container {
            display: flex;
            height: calc(100vh - 150px); /* Match the sizing used in calendar and members */
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
            padding: 20px; /* Ensure padding matches other pages */
            overflow-y: auto;
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
        .footer h1 {
            margin: 0;
        }
        .profileNav p {
            font-weight: bold;
            margin-bottom: 20px;
        }
        .mainContentHeader h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .task-form input, .task-form textarea, .task-form button {
            margin: 5px 0; /* Match with input styling from other pages */
            padding: 10px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .task-form button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
        }
        .task-form button:hover {
            background-color: #45a049;
        }
        .tasksDisplay {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .task-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            width: 250px; /* Adjust for consistency */
            padding: 20px;
            margin: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .task-card h3 {
            margin-top: 0;
            color: #333;
        }
        .task-card p {
            color: #666;
        }
        .task-card .due-date {
            font-size: 0.9em;
            color: #888;
        }
        .task-card .complete-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            text-align: center;
        }
        .task-card .complete-button:hover {
            background-color: #45a049;
        }
        .task-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .task-card.overdue {
            background-color: #ffdddd;
            border: 2px solid #ff4444;
        }
        .profileNav {
            border-bottom: 1px solid black;
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
                <p>
                    <?php
                    // Check if the user is logged in and display the username
                    if (isset($_SESSION['username'])) {
                        echo htmlspecialchars($_SESSION['username']);
                    } else {
                        echo "Guest";
                    }
                    ?>
                </p>
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
            <div class="mainContentHeader">
                <h1>Create Task</h1>
            </div>

            <!-- Task Creation Form -->
            <div class="task-form">
                <form action="home.php" method="POST">
                    <input type="text" name="task_name" placeholder="Task Name" required><br>
                    <textarea name="task_description" placeholder="Task Description" required></textarea><br>
                    <input type="date" name="task_date" required><br>
                    <button type="submit" name="submit_task">Create Task</button>
                </form>
            </div>

            <?php
            // Set the default time zone to handle time zone discrepancies
            date_default_timezone_set('America/New_York');  // Adjust to your desired time zone

            // Database connection
            $conn = new mysqli("localhost", "root", "", "tasktrackr");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Create Task
            if (isset($_POST['submit_task'])) {
                $task_name = $_POST['task_name'];
                $task_description = $_POST['task_description'];
                $task_date = $_POST['task_date'];

                // Insert the task into the database (no timezone conversion needed)
                $sql = "INSERT INTO tasks (task_name, task_description, task_date) VALUES ('$task_name', '$task_description', '$task_date')";
                if ($conn->query($sql) === TRUE) {
                    echo "Task created successfully!";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            // Fetch Tasks (Only tasks that are not completed)
            $sql = "SELECT id, task_name, task_description, task_date FROM tasks WHERE is_completed = 0 ORDER BY task_date";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='tasksDisplay'>";  // Start tasksDisplay container
                while ($row = $result->fetch_assoc()) {
                    // Directly display the date (no time zone conversion needed)
                    $formatted_date = $row['task_date'];

                    // Check if the task is overdue
                    $current_date = date('Y-m-d');  // Get today's date in 'Y-m-d' format
                    $is_overdue = ($formatted_date < $current_date); // Compare the dates

                    // Add a class 'overdue' if the task is overdue
                    $overdue_class = $is_overdue ? 'overdue' : '';

                    echo "<div class='task-card $overdue_class'>";
                    echo "<h3>" . htmlspecialchars($row['task_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['task_description']) . "</p>";
                    echo "<p class='due-date'>Due: " . $formatted_date . "</p>";

                    echo "<form method='POST'>
                            <input type='hidden' name='task_id' value='" . $row['id'] . "'>
                            <button type='submit' name='complete_task' class='complete-button'>Complete</button>
                          </form>";

                    echo "</div>";
                }
                echo "</div>";  // End tasksDisplay container
            } else {
                echo "<p>No tasks found!</p>";
            }

            $conn->close();
            ?>

        </div>
    </div>

    <div class="footer">
        <h1>© Copyright 2024 by tasktrackr</h1>
    </div>
</body>
</html>
