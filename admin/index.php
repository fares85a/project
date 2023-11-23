<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';



$stmt = $conn->prepare("SELECT
(SELECT COUNT(*) FROM users) AS user_count,
(SELECT COUNT(DISTINCT user_id) FROM answers) AS user_answer;");
$stmt->execute();

$rows = $stmt->fetch(PDO::FETCH_ASSOC);


$stmt_p = $conn->prepare("SELECT SUM(correct_answers + false_answers) AS total_answers ,SUM(`correct_answers`) AS correct_answers,SUM(`false_answers`) AS false_answers FROM results");
$stmt_p->execute();

$rows_p = $stmt_p->fetch(PDO::FETCH_ASSOC);
$totalAnswers = $rows_p["total_answers"];
$correctAnswers = $rows_p["correct_answers"];
$falseAnswers = $rows_p["false_answers"];

// Calculate percentages
$correctPercentage = ($totalAnswers > 0) ? ($correctAnswers / $totalAnswers) * 100 : 0;
$falsePercentage = ($totalAnswers > 0) ? ($falseAnswers / $totalAnswers) * 100 : 0;

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
                            <h2 class="pageheader-title">Downloads</h2>

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
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Users Particiption </h5>
                            <div class="card-body ">
                                <div id="c3chart_donut"></div>

                            </div>
                        </div>
                    </div>

                    <div class=" col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header">Correct Answers </h5>
                            <div class="card-body">
                                <div id="c3chart_pie"></div>
                                <p class="text-center">Total question answered :
                                    <?= $totalAnswers ?? 0 ?>
                                </p>
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
    <script>
    var chart = c3.generate({
        bindto: "#c3chart_donut",
        data: {
            columns: [
                ['Users Not Participated', <?= $rows["user_count"] - $rows["user_answer"] ?>],
                ['Users Participated', <?= $rows["user_answer"] ?>],
            ],
            type: 'donut',
            onclick: function(d, i) {
                console.log("onclick", d, i);
            },
            onmouseover: function(d, i) {
                console.log("onmouseover", d, i);
            },
            onmouseout: function(d, i) {
                console.log("onmouseout", d, i);
            },

            colors: {
                data1: '#5969ff',
                data2: '#ff407b'


            }
        },
        donut: {
            title: "Total Users: <?= $rows["user_count"] ?>"
        }


    });


    var chart = c3.generate({
        bindto: "#c3chart_pie",
        data: {
            columns: [
                ['Correct Answer', <?= round($correctPercentage) ?? 0 ?>],
                ['False Answers', <?= round($falsePercentage) ?? 0 ?>]
            ],
            type: 'pie',

            colors: {
                data1: '#5969ff',
                data2: '#ff407b'


            }
        },
        pie: {
            label: {
                format: function(value, ratio, id) {
                    return d3.format('')(value);
                }
            }
        }
    });
    </script>
</body>

</html>