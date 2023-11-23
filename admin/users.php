<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';



$stmt = $conn->prepare("SELECT * FROM `users`");
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- basic table  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="./add-user.php" class="btn btn-curve btn-primary mt-4"><i
                                        class=" far fa-user"></i> Add New User </a>
                            </div>
                            <h5 class="card-header">Latest Record</h5>
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered first">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Score</th>
                                                <th>Date Added</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rows as $k => $row): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $k + 1 ?>
                                                </td>

                                                <td>
                                                    <?php echo $row['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['contact_number']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['email']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['gender']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['score'] ?? "N/A"; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['date_added']; ?>
                                                </td>
                                                <td>
                                                    <a class="btn btn-warning"
                                                        href="./update-user.php?id=<?= $row["id"] ?>">Update</a>
                                                    <a class="btn btn-danger"
                                                        href="./backend/delete/user.php?id=<?= $row["id"] ?>">Delete</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Score</th>
                                                <th>Date Added</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end basic table  -->
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