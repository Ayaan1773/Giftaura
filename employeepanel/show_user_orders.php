<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);

// Fetch data from the database
$query = "SELECT * FROM orders";
$result = mysqli_query($con, $query);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    // Only allow updates if the order is not cancelled
    $check_query = "SELECT order_status FROM orders WHERE id = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $check_result = $stmt->get_result();
    $order = $check_result->fetch_assoc();

    if ($order['order_status'] != 'Order Cancelled') {
        $update_query = "UPDATE orders SET order_status = ? WHERE id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("si", $order_status, $order_id);
        $stmt->execute();
    } else {
        echo "<script>alert('Order has been cancelled and cannot be updated.');</script>";
    }
}
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
    /* General table styling */
    table.table {
        width: 100%;
        margin: 20px 0;
        border-collapse: separate;
        border-spacing: 0;
        border: none;
        background-color: #ffffff;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    /* Table header styling */
    table.table thead th {
        background-color: #f1f4f9;
        color: #333;
        font-weight: 600;
        padding: 15px;
        text-align: center;
        border-bottom: 2px solid #e0e6ed;
    }

    /* Table body styling */
    table.table tbody td {
        padding: 12px 15px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        color: #555;
    }

    /* Alternate row coloring */
    table.table tbody tr:nth-child(even) {
        background-color: #f8fbfd;
    }

    /* Hover effect on rows */
    table.table tbody tr:hover {
        background-color: #eaf3ff;
        transition: background-color 0.3s ease-in-out;
    }

    /* Styling buttons */
    button.btn {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Form select styling */
    .form-select {
    width: auto; /* Allows the width to adapt based on content */
    min-width: 120px; /* Ensures the box has enough space */
    padding: 5px 10px; /* Adds padding for better spacing */
    font-size: 14px; /* Makes text easily readable */
    white-space: nowrap; /* Prevents text from wrapping */
    text-align: center; /* Centers the text inside the box */
    border: 1px solid #ced4da; /* Consistent border styling */
    border-radius: 5px; /* Rounded corners for a modern look */
}

    /* Return and feedback styles */
    td span.text-success {
        color: #28a745;
        font-weight: 600;
    }

    td span.text-danger {
        color: #dc3545;
        font-weight: 600;
    }

    td span.text-muted {
        color: #6c757d;
        font-style: italic;
    }

    textarea.form-control {
        resize: none;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
        font-size: 14px;
        background-color: #fdfdfd;
    }

    /* Rounded table corners */
    table.table thead th:first-child {
        border-top-left-radius: 10px;
    }

    table.table thead th:last-child {
        border-top-right-radius: 10px;
    }

    table.table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 10px;
    }

    table.table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        table.table {
            font-size: 13px;
        }

        thead th, tbody td {
            padding: 8px;
        }

        .form-select, button.btn {
            font-size: 12px;
            padding: 6px;
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

<div class="container my-5 mx-2">
<h2 class="mb-4">Manage Orders</h2>
<table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>City</th>
                <th>Unique Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Payment Method</th>
                <th>Final Price</th>
                <th>Order Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['email_address']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['city']; ?></td>
                    <td><?php echo $row['unique_order_id']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><?php echo $row['final_price']; ?></td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <select name="order_status" class="form-select">
                                <option value="Order placed" <?php if ($row['order_status'] == 'Order placed') echo 'selected'; ?>>Order placed</option>
                                <option value="Out for delivery" <?php if ($row['order_status'] == 'Out for delivery') echo 'selected'; ?>>Out for delivery</option>
                                <option value="Delivered" <?php if ($row['order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Update</button>
                        </form>
                    </td>
                    
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
