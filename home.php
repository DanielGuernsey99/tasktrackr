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
        .mainContentHeader {
            margin: 0px;
            display: flex;
            justify-content: center;
            padding-top: 25px;
        }
        .mainContentHeader h1 {
            margin: 0px;
        }
        .task-list {
            margin-top: 20px;
            padding-left: 10px;
        }
        .task-item {
            margin: 5px 0;
        }
        .task-form input,
        .task-form select,
        .task-form button {
            margin: 5px;
        }
        .complete-button {
            background-color: green;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .complete-button:hover {
            background-color: darkgreen;
        }
        .mainContent {
            text-align: center;
        }
		.tasksDisplay{
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			padding: 20px;
		}
		.task-card {
			background-color: #f4f4f4;
			border-radius: 8px;
			width: 250px;
			padding: 20px;
			margin: 15px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
                <a href="home.php"><u>Home</u></a>
            </div>
            <div class="middleNav">
                <a href="Calendar.php"><u>Calendar</u></a>
            </div>
            <div class="bottomNav">
                <a href="Members.php"><u>Members</u></a>
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

            // Mark Task as Completed
            if (isset($_POST['complete_task'])) {
                $task_id = $_POST['task_id'];

                // Update the task status to completed
                $sql = "UPDATE tasks SET is_completed = 1 WHERE id = $task_id";
                if ($conn->query($sql) === TRUE) {
                    echo "Task marked as completed!";
                } else {
                    echo "Error: " . $conn->error;
                }
            }

            // Fetch Tasks (Only tasks that are not completed)
            $sql = "SELECT id, task_name, task_description, task_date FROM tasks WHERE is_completed = 0 ORDER BY task_date";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div class='tasksDisplay'>";  // Start tasksDisplay container
                while ($row = $result->fetch_assoc()) {
                    // Directly display the date (no time zone conversion needed)
                    $formatted_date = $row['task_date'];  // Since it's already in the correct format

                    echo "<div class='task-card'>";
                    echo "<h3>" . htmlspecialchars($row['task_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['task_description']) . "</p>";
                    echo "<p class='due-date'>Due: " . $formatted_date . "</p>";

                    // Complete button for each task
                    echo "<form method='POST'>
                            <input type='hidden' name='task_id' value='" . $row['id'] . "'>
                            <button type='submit' name='complete_task' class='complete-button'>Complete</button>
                          </form>";

                    echo "</div>";
                }
                echo "</div>";  // End tasksDisplay container
            } else {
                // No tasks found, truncate the table
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
