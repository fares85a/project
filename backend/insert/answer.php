<?php
session_start();
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name']) || !isset($_SESSION['user_email']) || empty($_SESSION['user_email']) || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get survey ID
    $survey_id = $_POST['survey_id'];

    // Prepare SQL statement to insert answers
    $stmt = $conn->prepare("INSERT INTO `answers` (`survey_id`, `user_id`, `question_id`, `selected_option`) VALUES (:survey_id, :user_id, :question_id, :selected_option)");


    $score = 0;
    $false_ans = 0;
    $lastID = array();

    // Loop through each question and insert the selected option
    foreach ($_POST['answers'] as $question_id => $selected_option) {
        // Bind parameters for answers table
        $stmt->bindParam(':survey_id', $survey_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':selected_option', $selected_option);

        // Fetching Correct Answers
        $stmt_q = $conn->prepare("SELECT correct_answer FROM questions WHERE id = :question_id");
        $stmt_q->bindParam(':question_id', $question_id);
        $stmt_q->execute();
        $correct_answer_row = $stmt_q->fetch(PDO::FETCH_ASSOC);

        if ($correct_answer_row && $correct_answer_row["correct_answer"] === $selected_option) {
            $score++;
        } else {
            $false_ans++;
        }

        try {
            $stmt->execute();
            $lastInsertedIDs[] = $conn->lastInsertId();
        } catch (PDOException $e) {
            $errorMessage = "Error inserting record.";
        }
    }

    // Calculate the final score
    $totalQuestions = count($_POST['answers']);
    $finalScore = ($totalQuestions > 0) ? (($score / $totalQuestions) * 100) : 0;


    // Prepare SQL statement to insert result
    $stmt_result = $conn->prepare("INSERT INTO `results` (`answer_id`, `user_id`, `survey_id`, `score`, `correct_answers`, `false_answers` ) VALUES (:answer_id , :user_id, :survey_id, :score, :correct_answers, :false_answers )");
    // Bind parameters for results table
    $stmt_result->bindParam(':answer_id', $lastID);
    $stmt_result->bindParam(':user_id', $_SESSION['user_id']);
    $stmt_result->bindParam(':survey_id', $survey_id);
    $stmt_result->bindParam(':score', $finalScore);
    $stmt_result->bindParam(':correct_answers', $score);
    $stmt_result->bindParam(':false_answers', $false_ans);

    try {
        $stmt_result->execute();
        $stmt_avg = $conn->prepare("SELECT AVG(score) AS score FROM results");
        $stmt_avg->execute();
        $avg_row = $stmt_avg->fetch(PDO::FETCH_ASSOC);
        $avg = $avg_row["score"];

        $stmt_update_avg = $conn->prepare("UPDATE `users` SET `score` = :score WHERE id = :id");
        $stmt_update_avg->bindParam(':score', $avg);
        $stmt_update_avg->bindParam(':id', $_SESSION['user_id']);
        $stmt_update_avg->execute();

        $successMessage = "Survey completed successfully.";
        header("Location: ../../index.php?id=$survey_id&success=" . urlencode($successMessage));
        exit;
    } catch (PDOException $e) {
        // Set error message and redirect
        $errorMessage = $e->getMessage();
        header("Location: ../../survey.php?id=$survey_id&error=" . urlencode($errorMessage));
        exit;
    }
} else {
    // Set error message and redirect
    $errorMessage = "Un-defined method.";
    header("Location: ../../manage-questions.php?error=" . urlencode($errorMessage));
    exit;
}
?>