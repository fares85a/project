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

    $stmt = $conn->prepare("SELECT * FROM `survey` WHERE `id` = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extract the values

    $id = $data['id'];
    $name = $data['name'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $description = $data['description'];
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
                                                class="breadcrumb-link">Survey</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Update Survey</li>
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


                                <form action="./backend/update/survey.php" method="POST" id="basicform"
                                    data-parsley-validate="">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <div class="form-group">
                                        <label for="name">Survey Name: <span style="color:red">*</span></label>
                                        <input type="text" id="name" required name="name" value="<?= $name ?>"
                                            class="form-control">
                                    </div>
                                    <div class="row">

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="start_date">Start Date:</label>
                                                <input type="date" id="start_date" name="start_date"
                                                    class="form-control" value="<?= $start_date ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="end_date">End Date:</label>
                                                <input type="date" id="end_date" value="<?= $end_date ?>"
                                                    name="end_date" class="form-control">
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
                                            class="form-control"><?= $description ?></textarea>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mb-3">
                                        <button type="submit" class="btn btn-curve btn-primary mt-4">
                                            Update
                                        </button>
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