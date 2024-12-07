<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);

// Check if the ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data
    $query = $con->prepare("SELECT * FROM employee WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $emp = $result->fetch_assoc();
    $query->close();

    if (!$emp) {
        echo "Employeee not found!";
        exit;
    }
}

// Update the record
if (isset($_POST['update'])) {
    $name = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare the SQL query
    $stmt = $con->prepare("UPDATE employee SET username=?, password=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $pass, $id);

    if ($stmt->execute()) {
        header("Location: employee-details.php");
        exit;
    } else {
        echo "Update failed: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">


    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
    /* Card Styling */
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
        padding: 15px;
        border-bottom: 1px solid #ddd;
        text-align: center;
        font-size: 1.25rem;
    }

    .card-body {
        padding: 20px;
    }

    .card-footer {
        background-color: #f8f9fa;
        padding: 15px;
        border-top: 1px solid #ddd;
        text-align: right;
    }

    /* Form Input Styling */
    .form-control {
        border-radius: 6px;
        border: 1px solid #ccc;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 20px;
        transition: border-color 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    /* Button Styling */
    .btn {
        font-size: 16px;
        padding: 10px 15px;
        border-radius: 6px;
        transition: background-color 0.3s ease-in-out, transform 0.2s;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-group {
            margin-bottom: 15px;
        }

        .btn {
            font-size: 14px;
            padding: 8px 12px;
        }

        .card-header, .card-footer {
            text-align: center;
        }
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

<div class="container my-5">
    <h2>Edit Employee Details</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo $emp['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <textarea class="form-control" name="password" required><?php echo $emp['password']; ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="employee-details.php" class="btn btn-secondary">Back</a>
    </form>
</div>
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
