<?php
session_start();

// Check Session
if (isset($_SESSION['name']) && !empty($_SESSION['name']) && isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: index.php");
}

// Connection
require("../connection.php");

// Variables
$nameErr = $passErr = $name = $pass = $queryErr = $UserName = "";

// Validation
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['name'])) {
        $nameErr = "Name is required.";
    } else {
        $name = check_input($_POST['name']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['password'])) {
        $passErr = "Password is required.";
    } else {
        $pass = check_input($_POST['password']);
    }
}

function check_input($par)
{
    $data = htmlspecialchars($par);
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

// Query
if (isset($name) && !empty($name) && isset($pass) && !empty($pass)) {
    $query = "SELECT * FROM `admin` WHERE `user_name` = :name OR `email` = :email ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $name);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        if (password_verify($pass, $data['password'])) {
            $_SESSION['name'] = $data['user_name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['id'] = $data['id'];
            header("Location: index.php");
            exit;
        } else {
            $passErr = "Incorrect password!";
        }
    } else {
        $queryErr = "No user found.";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="./assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/libs/css/style.css">
    <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center">
                <a href="./index.html"><b>Admin Login</b></a>
                <span class="splash-description">Please enter your user information.</span>
            </div>
            <div class="card-body">
                <?php
                if ($nameErr && $passErr) {
                    echo ('<div class="alert alert-danger" role="alert">' . $nameErr . '<br>' . $passErr . '</div>');
                    $nameErr = $passErr = "";
                }
                if ($passErr && !$nameErr) {
                    echo ('<div class="alert alert-danger" role="alert">' . $passErr . '</div>');
                    $passErr = "";
                }
                if ($nameErr && !$passErr) {
                    echo ('<div class="alert alert-danger" role="alert">' . $nameErr . '</div>');
                    $nameErr = "";
                }

                if ($queryErr && !$nameErr && !$passErr) {
                    echo ('<div class="alert alert-danger" role="alert">' . $queryErr . '</div>');
                    $queryErr = '';
                }
                ?>
                <form action="<?php echo (htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="name" id="username" type="text"
                            placeholder="Username" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="password" id="password" type="password"
                            placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span
                                class="custom-control-label">Remember Me</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                </form>
            </div>

        </div>
    </div>

    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="./assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>