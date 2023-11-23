<?php
session_start();
if (!isset($_SESSION['name']) &&  empty($_SESSION['name']) && !isset($_SESSION['email']) &&  empty($_SESSION['email']) && !isset($_SESSION['id']) &&  empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM `admin` WHERE `id` = :id");
    $stmt->bindParam(':id', $id);
} else {
    $stmt = $conn->prepare("SELECT * FROM `admin` LIMIT 1");
}


$stmt->execute();

// Fetch the data
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Extract the values

$id = $data['id'];
$user_name = $data['user_name'];
$password = $data['password'];

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
                            <h2 class="pageheader-title">Manage Admin</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <!-- <li class="breadcrumb-item"><a href="./index.php" class="breadcrumb-link">Downloads</a></li> -->
                                        <li class="breadcrumb-item active" aria-current="page">Update Admin</li>
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
                                <?php if ($successMessage) : ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $successMessage; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Display error message as Bootstrap alert -->
                                <?php if ($errorMessage) : ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errorMessage; ?>
                                    </div>
                                <?php endif; ?>


                                <form action="./backend/update/admin.php" method="POST" enctype="multipart/form-data" id="basicform" data-parsley-validate="">
                                    <input type="hidden" name="id" value="<?= $id ?>">

                                    <div class="form-group">
                                        <label for="uname">User Name:</label>
                                        <input type="text" id="uname" name="uname" value="<?= $user_name ?>" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="pass">New Password:</label>
                                        <input type="password" id="pass" autocomplete="off" name="pass" class=" form-control">
                                    </div>



                                    <div class="row">
                                        <div class="col-sm-6 pl-0">
                                            <p class="pl-3">
                                                <button type="submit" class="btn btn-space btn-primary">
                                                    Update
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