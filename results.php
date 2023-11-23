<?php
session_start();
if (!isset($_SESSION['user_name']) && empty($_SESSION['user_name']) && !isset($_SESSION['user_email']) && empty($_SESSION['user_email']) && !isset($_SESSION['user_id']) && empty($_SESSION['user_id'])) {
    header("Location: login.php");
}
include("./connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';




$stmt = $conn->prepare("SELECT r.*, s.name as survey_name
FROM results r
JOIN survey s ON r.survey_id = s.id WHERE r.user_id = :user_id 
");
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
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
                            <h2 class="pageheader-title">Result</h2>
                            <div class="page-breadcrumb">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <?php
                $stmt_update_avg = $conn->prepare("SELECT `score`,`comment` FROM `users`  WHERE id = :id");
                $stmt_update_avg->bindParam(':id', $_SESSION['user_id']);
                $stmt_update_avg->execute();
                $data_avg = $stmt_update_avg->fetch(PDO::FETCH_ASSOC);
                echo "<h4 >Total Score: <span class='text-warning'>" . $data_avg["score"] . "</span></h4> ";
                echo "<h4 >Comment: <span class='text-info'>" . $data_avg["comment"] . "</span></h4> ";
                ?>
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

                    foreach ($rows as $k => $survey) {




                        ?>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        Survey Name:
                                        <span class="text-muted">
                                            <?= $survey['survey_name'] ?>
                                        </span>
                                    </h3>
                                    <p class="card-text">
                                        Total Score is:
                                        <?= $survey["score"] ?>%
                                    </p>
                                    <div class="col-12">
                                        <div id="c3chart_pie_<?= $k ?>"></div>
                                        <p class="text-center p-0 m-0">Total questions :
                                            <?= $survey['correct_answers'] + $survey['false_answers'] ?>
                                        </p>
                                        <?php if (!empty($survey['comment'])) { ?>
                                            <p class="text-center p-0 m-0">Comment :
                                                <?= $survey['comment'] ?>
                                            </p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php

                    }

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
    <script>
        <?php
        foreach ($rows as $k => $survey) {

            ?>
            var chart = c3.generate({
                bindto: "#c3chart_pie_<?= $k ?>",
                data: {
                    columns: [
                        ['Correct Answers', <?= $survey['correct_answers'] ?>],
                        ['False Answers', <?= $survey['false_answers'] ?>]
                    ],
                    type: 'pie',

                    colors: {
                        data1: '#71748d',
                        data2: '#242849'


                    }
                },
                pie: {
                    label: {
                        format: function (value, ratio, id) {
                            return d3.format('')(value);
                        }
                    }
                }
            });
            <?php

        }

        ?>
    </script>
</body>

</html>