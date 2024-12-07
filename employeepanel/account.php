<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php");
    exit;
}

// Fetch logged-in user's details
$loggedInUsername = $_SESSION['username'];
$stmt = $con->prepare("SELECT id, username, password FROM employee WHERE username = ?");
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
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
        margin-bottom: 1.5rem;
        color: #212529;
        font-size: 16px;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        padding: 16px;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Input Fields Styling */
    table td input {
        width: 100%;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #f8f9fa;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    table td input:focus {
        border-color: #80bdff;
        background-color: #ffffff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Buttons Styling */
    table td button {
        padding: 8px 16px;
        font-size: 14px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    table td button:hover {
        background-color: #0056b3;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Page Header */
    h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        text-align: center;
        color: #343a40;
        font-weight: bold;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .table {
            font-size: 14px;
        }

        .table tbody td {
            text-align: left;
            display: block;
        }

        .table tbody td:before {
            content: attr(data-label);
            font-weight: bold;
            display: inline-block;
            width: 30%;
            text-transform: uppercase;
        }

        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px;
        }
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
    <h2 class="mb-4">My Account</h2>
    <table class="table table-bordered">
        <tr>
            <th>Field</th>
            <th>Value</th>
            <th>Actions</th>
        </tr>
        <tr>
            <td>Username</td>
            <td><input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>"></td>
            <td><button class="btn btn-primary btn-sm save" data-field="username">Save</button></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="text" id="password" value="<?php echo htmlspecialchars($user['password']); ?>"></td>
            <td><button class="btn btn-primary btn-sm save" data-field="password">Save</button></td>
        </tr>
    </table>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.save').click(function() {
        const field = $(this).data('field');
        const value = $('#' + field).val();

        $.ajax({
            url: 'update-account.php',
            method: 'POST',
            data: { field: field, value: value },
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    alert(res.message);
                } catch (error) {
                    alert("Unexpected response: " + response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error);
                alert('An error occurred: ' + error);
            }
        });
    });
});
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
