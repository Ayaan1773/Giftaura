<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);
// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Fetch the visitor count
$visitor_query = "SELECT count FROM visitor_count WHERE id = 1";
$visitor_result = mysqli_query($con, $visitor_query);
$visitor_count = 0;

if ($row = mysqli_fetch_assoc($visitor_result)) {
    $visitor_count = $row['count'];
}
// Fetch the employee count
$employee_query = "SELECT count FROM employee_count WHERE id = 1";
$employee_result = mysqli_query($con, $employee_query);
$employee_count = 0;

if ($row = mysqli_fetch_assoc($employee_result)) {
    $employee_count = $row['count'];
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
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">


    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
       /* Flexbox Container for Side-by-Side Layout */
       .stats-container {
            display: flex;
            justify-content: space-between;
            gap: 30px; /* Adds space between the boxes */
            margin: 50px auto;
            padding: 20px;
        }

        /* Common Box Styles */
        .stats-box {
            width: 48%; /* Adjust width of each box */
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Visitor Box Styles */
        .visitor-heading,
        .employee-heading {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }

        .visitor-count h3,
        .employee-count h3 {
            font-size: 20px;
            font-weight: 600;
            color: #444;
        }

        .visitor-count span,
        .employee-count span {
            color: #007bff;
            font-weight: 700;
        }

        .visitor-chart,
        .employee-chart {
            margin-top: 20px;
            height: 300px;
        }
        @media (max-width: 768px) {
    .stats-container {
        flex-direction: column;
    }

    .stats-box {
        width: 100%;
        margin-bottom: 20px;
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

    <div class="content">
        <div class="container">
            <!-- Flexbox Container for Visitor and Employee Stats Side by Side -->
            <div class="stats-container">
                <!-- Visitor Stats Box -->
                <div class="visitor-container stats-box">
                    <h2 class="visitor-heading">Visitor Statistics</h2>
                    <div class="visitor-count">
                        <h3>Total Visitors: <span><?php echo $visitor_count; ?></span></h3>
                    </div>
                    <div class="visitor-chart">
                        <canvas id="visitorPieChart"></canvas>
                    </div>
                </div>

                <!-- Employee Stats Box -->
                <div class="employee-container stats-box">
                    <h2 class="employee-heading">Employee Statistics</h2>
                    <div class="employee-count">
                        <h3>Total Employees Logged In: <span><?php echo $employee_count; ?></span></h3>
                    </div>
                    <div class="employee-chart">
                        <canvas id="employeePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Visitor Pie Chart Data and Config
    const visitorData = {
        labels: ['Visitors'],
        datasets: [
            {
                label: 'Visitor Count',
                data: [<?php echo $visitor_count; ?>],
                backgroundColor: ['#e7ab3c'], // Change color as needed
                hoverBackgroundColor: ['#e7ab3c'],
                borderColor: ['#e7e7e7'],
                borderWidth: 1,
                hoverOffset: 4,
            },
        ],
    };

    const visitorConfig = {
        type: 'pie',
        data: visitorData,
        options: {
            plugins: {
                legend: {
                    display: false, 
                },
            },
        },
    };

    const visitorPieChart = new Chart(
        document.getElementById('visitorPieChart'),
        visitorConfig
    );

    // Employee Pie Chart Data and Config
    const employeeData = {
        labels: ['Employees'],
        datasets: [
            {
                label: 'Employee Count',
                data: [<?php echo $employee_count; ?>],
                backgroundColor: ['#28a745'], // Green color for employee count
                hoverBackgroundColor: ['#218838'],
                borderColor: ['#e7e7e7'],
                borderWidth: 1,
                hoverOffset: 4,
            },
        ],
    };

    const employeeConfig = {
        type: 'pie',
        data: employeeData,
        options: {
            plugins: {
                legend: {
                    display: false, 
                },
            },
        },
    };

    const employeePieChart = new Chart(
        document.getElementById('employeePieChart'),
        employeeConfig
    );
    </script>
</body>

</html>
