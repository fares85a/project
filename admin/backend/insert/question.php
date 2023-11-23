<?php
session_start();
if (!isset($_SESSION['name']) || empty($_SESSION['name']) || !isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include("../../../connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO `questions` (`survey_id`,`name`, `option_1`, `option_2`, `option_3`, `option_4`, `correct_answer`) 
                            VALUES (:survey_id, :name, :option_1, :option_2, :option_3, :option_4, :correct_answer)");

    // Bind parameters
    $id = $_POST['id'];
    $stmt->bindParam(':survey_id', $_POST['id']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':option_1', $_POST['option_1']);
    $stmt->bindParam(':option_2', $_POST['option_2']);
    $stmt->bindParam(':option_3', $_POST['option_3']);
    $stmt->bindParam(':option_4', $_POST['option_4']);
    $stmt->bindParam(':correct_answer', $_POST['correct_answer']);


    try {
        if ($stmt->execute()) {
            // Set success message and redirect
            $successMessage = "Question added successfully.";
            header("Location: ../../manage-questions.php?id=$id&success=" . urlencode($successMessage));
            exit;
        } else {
            // Set error message and redirect
            $errorMessage = "Error inserting record.";
            header("Location: ../../manage-questions.php?id=$id&error=" . urlencode($errorMessage));
            exit;
        }
    } catch (PDOException $e) {
        // Set error message and redirect
        $errorMessage = "An error occurred.";
        header("Location: ../../manage-questions.php?id=$id&error=" . urlencode($errorMessage));
        exit;

    }


} else {
    // Set error message and redirect
    $errorMessage = "Un-defined method.";
    header("Location: ../../manage-questions.php?error=" . urlencode($errorMessage));
    exit;
}