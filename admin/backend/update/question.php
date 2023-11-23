<?php
session_start();
if (!isset($_SESSION['name']) || empty($_SESSION['name']) || !isset($_SESSION['email']) || empty($_SESSION['email']) || !isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include("../../../connection.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("UPDATE `questions` SET
                            `name` = :name,
                            `option_1` = :option_1,
                            `option_2` = :option_2,
                            `option_3` = :option_3,
                            `option_4` = :option_4,
                            `correct_answer` = :correct_answer
                            WHERE `id` = :question_id");

    // Bind parameters for the question update
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':option_1', $_POST['option_1']);
    $stmt->bindParam(':option_2', $_POST['option_2']);
    $stmt->bindParam(':option_3', $_POST['option_3']);
    $stmt->bindParam(':option_4', $_POST['option_4']);
    $stmt->bindParam(':correct_answer', $_POST['correct_answer']);
    $stmt->bindParam(':question_id', $_POST['id']);

    $id = $_POST['id'];

    try {
        if ($stmt->execute()) {
            // Set success message and redirect
            $successMessage = "Question updated successfully.";
            header("Location: ../../manage-questions.php?id=" . $_POST["survey_id"] . "&success=" . urlencode($successMessage));
            exit;
        } else {
            // Set error message and redirect
            $errorMessage = "Error updating record.";
            header("Location: ../../update-question.php?id=" . $_POST["survey_id"] . "&error=" . urlencode($errorMessage));
            exit;
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            $errorMessage = "Email address already exists.";
            header("Location: ../../update-question.php?id=" . $_POST["survey_id"] . "&error=" . urlencode($errorMessage));
            exit;
        } else {
            $errorMessage = $e->errorInfo[2];
            header("Location: ../../update-question.php?id=" . $_POST["survey_id"] . "&error=" . urlencode($errorMessage));
            exit;
        }
    }


} else {
    // Set error message and redirect
    $errorMessage = "Un-defined method.";
    header("Location: ../../update-survey.php?error=" . urlencode($errorMessage));
    exit;
}