<?php
// Include database configuration
include_once '../config/database.php';
include_once '../models/Project.php';

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Project object
$project = new Project($db);

// Create a new project (example)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $target_amount = $_POST['target_amount'];
    $payment_link = $_POST['payment_link'];
    $image = $_POST['image'];

    if ($project->create($name, $description, $target_amount, $payment_link, $image)) {
        echo "Project created successfully.";
    } else {
        echo "Unable to create project.";
    }
}
?>