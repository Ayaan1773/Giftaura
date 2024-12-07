<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);
// Fetch data from the database
$query = "SELECT * FROM chat_history";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
    /* General Table Styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        color: #212529;
        font-size: 16px;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        padding: 12px;
    }

    .table tbody td {
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: center;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }

    /* Responsive Table Adjustments */
    @media (max-width: 768px) {
        .table {
            font-size: 14px;
        }

        .table thead {
            display: none;
        }

        .table tbody td {
            display: block;
            text-align: left;
            border: none;
            padding: 8px 12px;
            position: relative;
        }

        .table tbody td:before {
            content: attr(data-label);
            font-weight: bold;
            text-transform: uppercase;
            position: absolute;
            left: 0;
            top: 8px;
        }

        .table tbody tr {
            margin-bottom: 1rem;
            display: block;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem;
        }
    }

    /* Header Styling */
    h2 {
        font-size: 1.8rem;
        margin-bottom: 1rem;
        text-align: center;
        color: #333;
    }

    /* Remove Bullet Points Globally (if applicable) */
    ul, ol {
        list-style: none;
        padding-left: 0;
    }
</style>
</head>
<body>
<!-- Left Panel -->
<?php include('leftpanel.php'); ?>
<!-- Left Panel -->

<!-- Right Panel -->
<?php include('rightpanel.php'); ?>
<!-- Right Panel -->

<div class="container my-5">
    <h2 class="mb-4">Support</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Sender Type</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['sender_type']; ?></td>
                    <td><?php echo $row['message']; ?></td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
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
