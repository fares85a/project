<?php
session_start();
if (!isset($_SESSION['name']) && empty($_SESSION['name']) && !isset($_SESSION['email']) && empty($_SESSION['email']) && !isset($_SESSION['id']) && empty($_SESSION['id'])) {
    header("Location: login.php");
}
include("../connection.php");
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
                    <!-- ============================================================== -->
                    <!-- basic table  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">

                            <h5 class="card-header">
                                <form action="./backend/insert/survey.php" method="POST" id="basicform"
                                    data-parsley-validate="">
                                    <div class="form-group">
                                        <label for="name">Survey Name: <span style="color:red">*</span></label>
                                        <input type="text" id="name" required name="name" class="form-control">
                                    </div>
                                    <div class="row">

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="start_date">Start Date:</label>
                                                <input type="date" id="start_date" name="start_date"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="end_date">End Date:</label>
                                                <input type="date" id="end_date" name="end_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12   m-0">
                                            <small class="text-muted m-0">Leave empty if yo dont want to
                                                check dates</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Brief Description:<span
                                                style="color:red">*</span></label>
                                        <textarea name="description" required id="description"
                                            class="form-control"></textarea>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mb-3">
                                        <button type="submit" class="btn btn-curve btn-primary mt-4">
                                            <i class=" fab fa-wpforms"></i> Create Survey
                                        </button>
                                    </div>
                                </form>
                            </h5>
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
                                                <th>Start Date</th>
                                                <th>End</th>
                                                <th>Gender</th>
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
                                                        <?php echo !empty($row['start_date']) ? $row['start_date'] : "NULL"; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo !empty($row['end_date']) ? $row['end_date'] : "NULL"; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['description']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['date_added']; ?>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-warning"
                                                            href="./update-survey.php?id=<?= $row["id"] ?>">Update</a>
                                                        <a class="btn btn-info"
                                                            href="./manage-questions.php?id=<?= $row["id"] ?>">Manage
                                                            Questions</a>
                                                        <a class="btn btn-danger"
                                                            href="./backend/delete/survey.php?id=<?= $row["id"] ?>">Delete</a>
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