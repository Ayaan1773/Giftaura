<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";


$con = mysqli_connect($servername, $username, $password, $db_name);
$query=mysqli_query($con,"select * from login");
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GiftAura Admin</title>
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
        /* Table Styling */
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    border-collapse: collapse;
}

.table th, .table td {
    text-align: center;
    vertical-align: middle;
    border: 1px solid #dee2e6;
    padding: 8px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: bold;
    text-transform: uppercase;
}

.table tr:nth-child(even) {
    background-color: #f2f2f2; /* Alternating row colors */
}

.table-hover tbody tr:hover {
    background-color: #e9ecef; /* Highlight row on hover */
}

/* Button Styling */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 4px;
    text-transform: uppercase;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 4px;
    text-transform: uppercase;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
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
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Users Data</strong>
                            </div>
                            <div class="card-body">
                            <table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Password</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($data = mysqli_fetch_assoc($query)) {
            echo '<tr>
                <td>' . $data['id'] . '</td>
                <td>' . $data['email'] . '</td>
                <td>' . $data['pass'] . '</td>
                <td>
                    <a href="tables-data-edit.php?abc=' . $data['id'] . '" class="btn btn-primary">Edit</a>
                </td>
                <td>
                    <form method="post">
                        <button type="submit" class="btn btn-danger" onclick="deletes(this)" data-id="' . $data['id'] . '">Delete</button>
                    </form>
                </td>
            </tr>';
        }
        ?>
    </tbody>
</table>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>

    function deletes(button){
        if(confirm("Are You Sure You Want To Delete?")){
            var id=button.getAttribute('data-id');
            $.ajax({
                url:'tables-data-delete.php',
                type:'post',
                data:{ id: id }

            })
        }
    }
</script>
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
