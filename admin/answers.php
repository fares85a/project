<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';



$stmt = $conn->prepare("SELECT r.*, s.name as survey_name, u.name as user_name FROM results r JOIN survey s ON r.survey_id = s.id JOIN users u ON r.user_id = u.id");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $results now contains the fetched data with survey name and user name

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
                            <h2 class="pageheader-title">Answers / Scores</h2>
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
                                                <th>Survey</th>
                                                <th>User</th>
                                                <th>Score (%)</th>
                                                <th>Correct Answer</th>
                                                <th>False Answer</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($results as $k => $row): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $k + 1 ?>
                                                    </td>

                                                    <td>
                                                        <?php echo $row['survey_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['user_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['score']; ?>%
                                                    </td>
                                                    <td>
                                                        <?php echo $row['correct_answers']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['false_answers']; ?>
                                                    </td>


                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="./update-result.php?id=<?= $row["id"] ?>">Update</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Survey</th>
                                                <th>User</th>
                                                <th>Score</th>
                                                <th>Correct Answer</th>
                                                <th>False Answer</th>
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