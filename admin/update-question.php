<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';


$stmt = $conn->prepare("SELECT * FROM `questions` WHERE id = :id");
if (isset($_GET["id"])) {
    $stmt->bindParam(":id", $_GET["id"]);
    $stmt->execute();
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
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
                            <h2 class="pageheader-title">Manage Questions</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./index.php"
                                                class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Manage Questions</li>
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

                    <div class="  col-md-12 col-sm-12 col-12">
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
                                <form action="./backend/update/question.php" method="POST" id="basicform"
                                    data-parsley-validate="">
                                    <input type="hidden" value="<?= $_GET["id"] ?>" name="id">
                                    <input type="hidden" value="<?= $_GET["survey_id"] ?>" name="survey_id">
                                    <div class="form-group">
                                        <label for="name">Question Title / Name:</label>
                                        <input type="text" id="name" name="name" value="<?= $rows["name"] ?>"
                                            class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="option_1">Option 1:</label>
                                        <input type="text" id="option_1" required name="option_1"
                                            value="<?= $rows["option_1"] ?>" class=" form-control">

                                    </div>

                                    <div class="form-group">
                                        <label for="option_1">Option 2:</label>
                                        <input type="text" id="option_2" value="<?= $rows["option_2"] ?>" required
                                            name="option_2" class="form-control">

                                    </div>

                                    <div class="form-group">
                                        <label for="option_1">Option 3:</label>
                                        <input type="text" id="option_3" name="option_3"
                                            value="<?= $rows["option_3"] ?>" class="form-control">

                                    </div>
                                    <div class="form-group">
                                        <label for="option_1">Option 4:</label>
                                        <input type="text" id="option_4" value="<?= $rows["option_4"] ?>"
                                            name="option_4" class="form-control">

                                    </div>

                                    <div class="form-group">
                                        <label for="answer">Correct Answer:</label>
                                        <select class="custom-select d-block w-100" id="answer" required=""
                                            name="correct_answer">
                                            <option value="" disabled>Choose correct answer</option>
                                            <option value="1" <?php if ($rows["correct_answer"] == 1)
                                                echo 'selected'; ?>>
                                                Option 1
                                            </option>
                                            <option value="2" <?php if ($rows["correct_answer"] == 2)
                                                echo 'selected'; ?>>
                                                Option 2
                                            </option>
                                            <option value="3" <?php if ($rows["correct_answer"] == 3)
                                                echo 'selected'; ?>>
                                                Option 3
                                            </option>
                                            <option value="4" <?php if ($rows["correct_answer"] == 4)
                                                echo 'selected'; ?>>
                                                Option 4
                                            </option>
                                        </select>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6 pl-0">
                                            <p class="pl-3">
                                                <button type="submit" class="btn btn-space btn-primary">
                                                    Update Question
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