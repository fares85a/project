<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extract the values

    $id = $data['id'];
    $name = $data['name'];
    $contact_number = $data['contact_number'];
    $email = $data['email'];
    $gender = $data['gender'];
    $comment = $data['comment'];
    $score = $data['score'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <?php include("includes/head.php") ?>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <?php include("./includes/header.php") ?>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <?php include("./includes/aside.php") ?>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="container-fluid  dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Users</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./index.php"
                                                class="breadcrumb-link">Users</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Update User</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- basic form -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <?php if ($successMessage): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $successMessage; ?>
                                </div>
                                <?php endif; ?>

                                <!-- Display error message as Bootstrap alert -->
                                <?php if ($errorMessage): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $errorMessage; ?>
                                </div>
                                <?php endif; ?>


                                <form action="./backend/update/user.php" method="POST" id="basicform"
                                    data-parsley-validate="">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" value="<?= $name ?>"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="contact_number">Contact Number:</label>
                                        <input type="text" id="contact_number" value="<?= $contact_number ?>"
                                            name="contact_number" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" value="<?= $email ?>"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                    </div>



                                    <h5>Gender</h5>
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="gender" value="Male"
                                            <?php echo ($gender == "Male") ? "checked" : ""; ?>
                                            class="custom-control-input">
                                        <span class="custom-control-label">Male </span>
                                    </label>
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="gender" value="Female" class="custom-control-input"
                                            <?php echo ($gender == "Female") ? "checked" : ""; ?>>
                                        <span class="custom-control-label">Female</span>
                                    </label>

                                    <div class="form-group">
                                        <label for="score">Score:</label>
                                        <input type="number" id="score" name="score" value="<?= $score ?>"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="comment">Comment:</label>
                                        <textarea id="comment" name="comment"
                                            class="form-control"><?= $comment ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 pl-0">
                                            <p class="pl-3">
                                                <button type="submit" class="btn btn-space btn-primary">
                                                    Update User
                                                </button>
                                            </p>
                                        </div>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end basic form -->
                    <!-- ============================================================== -->
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php
            include("./includes/footer.php")
                ?>

            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <?php include("./includes/js.php") ?>

</body>

</html>