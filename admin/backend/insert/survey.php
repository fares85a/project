<?php
session_start();
if (!isset($_SESSION['name']) || empty($_SESSION['name']) || !isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include("../../../connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO `survey` (`name`, `start_date`, `end_date`, `description`) 
    VALUES (:name, :start_date, :end_date, :description)");

    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':description', $description);


    try {
        if ($stmt->execute()) {
            // Set success message and redirect
            $successMessage = "Survey added successfully.";
            header("Location: ../../surveys.php?success=" . urlencode($successMessage));
            exit;
        } else {
            // Set error message and redirect
            $errorMessage = "Error inserting record.";
            header("Location: ../../add-user.php?error=" . urlencode($errorMessage));
            exit;
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $errorMessage = "Email address already exists.";
            header("Location: ../../add-user.php?error=" . urlencode($errorMessage));
            exit;
        } else {
            $errorMessage = "An error occurred.";
            header("Location: ../../add-user.php?error=" . urlencode($errorMessage));
            exit;
        }
    }


} else {
    // Set error message and redirect
    $errorMessage = "Un-defined method.";
    header("Location: ../../add-user.php?error=" . urlencode($errorMessage));
    exit;
}