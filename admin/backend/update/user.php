<?php
session_start();
if (!isset($_SESSION['name']) || empty($_SESSION['name']) || !isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include("../../../connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['password']) && isset($_POST['password'])) {
        $stmt = $conn->prepare("UPDATE `users` SET 
                                `name` = :name, 
                                `contact_number` = :contact_number, 
                                `email` = :email, 
                                `password` = :password, 
                                `gender` = :gender, 
                                `score` = :score, 
                                `comment` = :comment 
                                WHERE `id` = :id");

        $password = $_POST['password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $hashedPassword);
    } else {
        $stmt = $conn->prepare("UPDATE `users` SET 
                                `name` = :name, 
                                `contact_number` = :contact_number, 
                                `email` = :email, 
                                `gender` = :gender, 
                                `score` = :score, 
                                `comment` = :comment 
                                WHERE `id` = :id");
    }

    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $score = $_POST['score'];
    $comment = $_POST['comment'];

    // Bind parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact_number', $contact_number);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':comment', $comment);

    try {
        if ($stmt->execute()) {
            // Set success message and redirect
            $successMessage = "User updated successfully.";
            header("Location: ../../users.php?success=" . urlencode($successMessage));
            exit;
        } else {
            // Set error message and redirect
            $errorMessage = "Error updating record.";
            header("Location: ../../update-user.php?id=" . $id . "&error=" . urlencode($errorMessage));
            exit;
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $errorMessage = "Email address already exists.";
            header("Location: ../../update-user.php?id=" . $id . "&error=" . urlencode($errorMessage));
            exit;
        } else {
            $errorMessage = "An error occurred.";
            header("Location: ../../update-user.php?id=" . $id . "&error=" . urlencode($errorMessage));
            exit;
        }
    }


} else {
    // Set error message and redirect
    $errorMessage = "Un-defined method.";
    header("Location: ../../update-user.php?error=" . urlencode($errorMessage));
    exit;
}