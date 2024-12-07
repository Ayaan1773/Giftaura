<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Database connection
$con = mysqli_connect($servername, $username, $password, $db_name);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_GET['abc'];
echo $id;

// Fetch user data
$query = mysqli_query($con, "SELECT * FROM login WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

// Update operation
if (isset($_POST["editbtn"])) {
    $email = $_POST["useremail"];
    $pass = $_POST["userpassword"];

    // Update query
    $update_query = "UPDATE login SET email='$email', pass='$pass' WHERE id='$id'";
    if (mysqli_query($con, $update_query)) {
        echo "Updated Successfully";
        header("Location: tables-data.php");
        exit;
    } else {
        echo "Update Failed: " . mysqli_error($con);
    }
}

// Delete operation
if (isset($_POST["delbtn"])) {
    $delete_query = "DELETE FROM login WHERE id='$id'";
    if (mysqli_query($con, $delete_query)) {
        header('Location: tables-data.php');
        exit;
    } else {
        echo "Delete Failed: " . mysqli_error($con);
    }
}
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sufee Admin - HTML5 Admin Template</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">


    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
        /* Style for consistent card header */
.card-header {
    background-color: #f8f9fa;
    color: #333;
    font-weight: bold;
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

/* Consistent spacing for forms */
.form-control {
    margin-bottom: 15px;
}

.card-footer {
    background-color: #f8f9fa;
    padding: 10px;
    border-top: 1px solid #ddd;
}
    </style>
</head>

<body>
     <!-- Left Panel -->
     <?php
    include('leftpanel.php');
    ?>
    <!-- Left Panel -->

    <!-- Right Panel -->
    <?php
    include('rightpanel.php');
    ?>
    <!-- Right Panel -->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Table</a></li>
                            <li class="active">Data table</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
    <div class="animated fadeIn">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Edit Users Data</strong>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="nf-email" class="form-control-label">Email</label>
                                <input type="email" id="nf-email" name="useremail" value="<?php echo $data['email'] ?>" placeholder="Enter Email.." class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nf-password" class="form-control-label">Password</label>
                                <input type="password" id="nf-password" name="userpassword" value="<?php echo $data['pass'] ?>" placeholder="Enter Password.." class="form-control" required>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm" name="editbtn">
                                    <i class="fa fa-dot-circle-o"></i> Update
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm" name="delbtn">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script src="vendors/jquery/dist/jquery.min.js"></script>
<script src="vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
<script src="assets/js/dashboard.js"></script>
<script src="vendors/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>

</body>

</html>