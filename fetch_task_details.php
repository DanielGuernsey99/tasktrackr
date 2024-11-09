<?php
// Fetch task details based on task ID
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "tasktrackr");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch the task details
    $sql = "SELECT * FROM tasks WHERE id = $taskId"; // Make sure you're querying by 'id'
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch task details
        $task = $result->fetch_assoc();
        echo json_encode([
            'task_name' => $task['task_name'],
            'task_description' => $task['task_description'],
            'task_date' => $task['task_date']
        ]);
    } else {
        echo json_encode(null); // No task found
    }

    $conn->close();
}
?>
