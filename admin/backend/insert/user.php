<?php
session_start();
if (!isset($_SESSION['name']) || empty($_SESSION['name']) || !isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include("../../../connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO `users` (`name`, `contact_number`, `email`, `password`, `gender`) 
    VALUES (:name, :contact_number, :email, :password, :gender)");

    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact_number', $contact_number);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':gender', $gender);


    try {
        if ($stmt->execute()) {
            // Set success message and redirect
            $successMessage = "User added successfully.";
            header("Location: ../../users.php?success=" . urlencode($successMessage));
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