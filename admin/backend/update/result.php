<?php
session_start();
if (!isset($_SESSION['name']) || empty($_SESSION['name']) || !isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include("../../../connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("UPDATE `results` SET 
    `score` = :score, 
    `comment` = :comment
    WHERE `id` = :id");



    $id = $_POST['id'];
    $score = $_POST['score'];
    $comment = $_POST['comment'];

    // Bind parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':comment', $comment);

    try {
        if ($stmt->execute()) {
            // Set success message and redirect
            $successMessage = "Result updated successfully.";
            header("Location: ../../answers.php?success=" . urlencode($successMessage));
            exit;
        } else {
            // Set error message and redirect
            $errorMessage = "Error updating record.";
            header("Location: ../../update-result.php?id=" . $id . "&error=" . urlencode($errorMessage));
            exit;
        }
    } catch (PDOException $e) {

        $errorMessage = $e->errorInfo[2];
        header("Location: ../../update-result.php?id=" . $id . "&error=" . urlencode($errorMessage));
        exit;
    }


} else {
    // Set error message and redirect
    $errorMessage = "Un-defined method.";
    header("Location: ../../update-survey.php?error=" . urlencode($errorMessage));
    exit;
}