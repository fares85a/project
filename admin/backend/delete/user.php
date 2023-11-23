<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../../../connection.php");

// Check if the ID parameter is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL query for delete
    $stmt = $conn->prepare("DELETE FROM `users` WHERE `id` = :id");

    // Bind the ID parameter
    $stmt->bindParam(':id', $id);

    // Execute the statement
    $stmt->execute();

    // Check if the delete was successful
    if ($stmt->rowCount() > 0) {
        // Set success message and redirect
        $successMessage = "Record deleted successfully.";
        header("Location: ../../users.php?success=" . urlencode($successMessage));
        exit;
    } else {
        // Set error message and redirect
        $errorMessage = "Error deleting record.";
        header("Location: ../../users.php?error=" . urlencode($errorMessage));
        exit;
    }
}

header("Location: ../../users.php");
exit;