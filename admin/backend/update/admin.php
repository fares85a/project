<?php
session_start();
if (!isset($_SESSION['name']) &&  empty($_SESSION['name']) && !isset($_SESSION['email']) &&  empty($_SESSION['email']) && !isset($_SESSION['id']) &&  empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../../../connection.php");
$id = $_POST['id'];
$name = $_POST['uname'];
if (!empty($name)) {
    if (!empty($_POST['pass'])) {
        $plainPassword = $_POST['pass'];
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE `admin` SET 
                    `user_name` = :name,
                    `password` = :pass
                    WHERE `id` = :id");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':pass', $hashedPassword); // Use the hashed password
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE `admin` SET 
                    `user_name` = :name
                    WHERE `id` = :id");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
} else {
    $errorMessage = "Name must required";
    header("Location: ../../manage-admin.php?id=$id&error=" . urlencode($errorMessage));
    exit;
}


if ($stmt->rowCount() > 0) {
    $successMessage = "Record updated successfully.";
    header("Location: ../../manage-admin.php?success=" . urlencode($successMessage));
    exit;
} else {
    $errorMessage = "Error updating record.";
    header("Location: ../../manage-admin.php?id=$id&error=" . urlencode($errorMessage));
    exit;
}