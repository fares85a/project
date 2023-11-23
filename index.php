<?php
session_start();
if (!isset($_SESSION['user_name']) && empty($_SESSION['user_name']) && !isset($_SESSION['user_email']) && empty($_SESSION['user_email']) && !isset($_SESSION['user_id']) && empty($_SESSION['user_id'])) {
    header("Location: login.php");
}
include("./connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';



$stmt = $conn->prepare("SELECT * FROM `survey`");
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
                            <h2 class="pageheader-title">Survey</h2>
                            <div class="page-breadcrumb">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">

                    <div class="col-12">
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
                    </div>
                    <!-- ============================================================== -->
                    <!-- basic table  -->
                    <!-- ============================================================== -->
                    <?php
                    $flag = 0;

                    if ($stmt->rowCount() === 0) {
                        echo ' <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <p>No surveys found.</p>
                                </div>';
                    } else {

                        foreach ($rows as $survey) {
                            $stmt = $conn->prepare("SELECT COUNT(*) FROM answers WHERE user_id = :user_id AND survey_id = :survey_id");
                            $stmt->bindParam(':user_id', $_SESSION['user_id']);
                            $stmt->bindParam(':survey_id', $survey['id']);
                            $stmt->execute();
                            $response_count = $stmt->fetchColumn();

                            if ($response_count == 0) {
                                ?>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                <?= $survey['name'] ?>
                                            </h3>
                                            <p class="card-text">
                                                <?= $survey['description'] ?>
                                            </p>
                                            <a href="survey.php?id=<?= $survey["id"] ?>" class="btn btn-primary">Start Survey</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                $flag++;
                            }
                        }
                    }

                    if ($flag === count($rows)) {
                        echo ' <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                    <p>No surveys found.</p>
                                </div>';
                    }
                    $conn = null;
                    ?>
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