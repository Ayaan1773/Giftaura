<?php
session_start();
include('connection.php');

// Check if the email is available in the session
if (!isset($_SESSION['email'])) {
    echo "User not logged in.";
    exit;
}

// Get the email from the session
$user_email = $_SESSION['email'];
$success_message = $error_message = '';


// Handle order cancelation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    // Update order status to 'Order Cancelled'
    $cancel_query = "UPDATE orders SET order_status = 'Order Cancelled' WHERE id = ? AND order_status = 'Order placed'";
    $stmt = $con->prepare($cancel_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $success_message = "Order successfully cancelled.";
    } else {
        $error_message = "Order cannot be cancelled.";
    }
}

// Handle return request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return_request'])) {
    $order_id = $_POST['order_id'];

    // Update return status
    $return_query = "UPDATE orders SET return_status = 'Requested' WHERE id = ? AND order_status = 'Delivered'";
    $stmt = $con->prepare($return_query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $success_message = "Return request submitted successfully.";
    } else {
        $error_message = "Unable to submit return request.";
    }

    // Redirect to prevent resubmission
    header("Location: orders.php");
    exit;
}

// Fetch order details for the given user email
$query = "SELECT * FROM orders WHERE email_address = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fashi Template">
    <meta name="keywords" content="Fashi, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GiftAura.</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/themify-icons.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        /* Styling for Order Details Table */
        .orders-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 50px auto;
        }

        h2 {
            font-size: 32px;
            color: #333;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #191919;
            color: white;
            font-weight: bold;
        }

        table td {
            background-color: #f9f9f9;
            color: #333;
        }

        table tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        table tr:hover {
            background-color: #e0e0e0;
        }

        table td {
            font-size: 14px;
            font-weight: bold;
            color: #666;
            word-wrap: break-word;
        }

        .orders-container table {
            margin-top: 30px;
        }

        .orders-container p {
            text-align: center;
            font-size: 18px;
            color: #555;
        }

        .status {
            color: green;
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <?php
    include('navbar.php')
    ?>
    <!-- Header End -->

    <div class="orders-container my-5">
        <h2 class="mb-4">Order Details</h2>
        <?php if (isset($success_message)) echo "<p class='text-success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='text-danger'>$error_message</p>"; ?>
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Unique Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Payment Method</th>
                        <th>Final Price</th>
                        <th>Order Status</th>
                        <th>Actions</th>
                        <th>Returns</th>
                        <th>Feedback</th>


                    </tr>
                </thead>
                <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
    <td><?php echo htmlspecialchars($row['unique_order_id']); ?></td>
    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
    <td><?php echo htmlspecialchars($row['final_price']); ?></td>
    
    <td>
        <?php 
        if ($row['order_status'] == 'Order placed') {
            $status_class = 'text-success';
        } elseif ($row['order_status'] == 'Order Shipped') {
            $status_class = 'text-success';
        } elseif ($row['order_status'] == 'Out for delivery') {
            $status_class = 'text-success';
        } elseif ($row['order_status'] == 'Order Cancelled') {
            $status_class = 'text-danger';
        } else {
            $status_class = 'text-muted';
        }
        ?>
        <span class="<?php echo $status_class; ?>">
            <?php echo htmlspecialchars($row['order_status']); ?>
        </span>
    </td>
    <td>
        <?php if ($row['order_status'] == 'Order placed') { ?>
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">Cancel Order</button>
            </form>
        <?php } else { ?>
            <span class="text-muted">Cancellation Unavailable</span>
        <?php } ?>
    </td>

    <td>
    <?php if ($row['order_status'] == 'Delivered') { ?>
        <?php if ($row['return_status'] == 'None') { ?>
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="return_request" class="btn btn-warning btn-sm">Request Return</button>
            </form>
        <?php } elseif ($row['return_status'] == 'Requested') { ?>
            <span class="text-info">Return Requested</span>
        <?php } elseif ($row['return_status'] == 'Approved') { ?>
            <div class="alert alert-success">
        Return request has been approved.
    </div>        <?php } elseif ($row['return_status'] == 'Declined') { ?>
        <div class="alert alert-danger">
        Return request has been declined.
    </div>        <?php } ?>
    <?php } else { ?>
        <span class="text-muted">Return Unavailable</span>
    <?php } ?>
</td>
<!-- Feedback Section -->
<td>
                                <?php if ($row['order_status'] == 'Delivered') { ?>
                                    <a href="feedback.php?order_id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Submit Feedback</a>
                                <?php } else { ?>
                                    <span class="text-muted">Feedback Unavailable</span>
                                <?php } ?>
                            </td>
</tr>
    <?php } ?>
</tbody>
            </table>
        <?php } else { ?>
            <p>No orders found </p>
        <?php } ?>
    </div>

    <!-- Partner Logo Section Begin -->
    <?php
    include('partner-logo.php')
    ?>
    <!-- Partner Logo Section End -->

    <!-- Footer Section Begin -->
    <?php
    include('footer.php')
    ?>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.zoom.min.js"></script>
    <script src="js/jquery.dd.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    
</body>

</html>
