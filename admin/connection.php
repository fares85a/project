<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "survey_system";

// Create a PDO instance
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);