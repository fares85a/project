<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("./connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM `questions` WHERE `survey_id` = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                            <h2 class="pageheader-title">Survey</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./index.php"
                                                class="breadcrumb-link">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Survey</li>
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


                                <form action="./backend/insert/answer.php" method="POST" id="basicform">
                                    <input type="hidden" name="survey_id" value="<?= $id ?>">

                                    <!-- Display survey questions -->
                                    <h5>Survey Questions</h5>
                                    <?php foreach ($data as $question): ?>
                                    <div class="form-group">
                                        <h5 for="<?= $question['name'] ?>">
                                            <?= $question['name'] ?>:
                                        </h5>

                                        <!-- Options as radio buttons -->
                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" value="<?= $i ?>" name="answers[<?= $question['id'] ?>]"
                                                class="custom-control-input">
                                            <span class="custom-control-label">
                                                <?= $question['option_' . $i] ?>
                                            </span>
                                        </label>
                                        <?php endfor; ?>
                                    </div>
                                    <?php endforeach; ?>

                                    <div class="row">
                                        <div class="col-sm-6 pl-0">
                                            <p class="pl-3">
                                                <button type="submit" class="btn btn-space btn-primary">
                                                    Submit Answers
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