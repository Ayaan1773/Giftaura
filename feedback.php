<?php
session_start();
include('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User not logged in.";
    exit;
}

// Initialize variables
$user_email = $_SESSION['email'];
$order_id = $_GET['order_id'] ?? null; // Use null if no order_id is passed
$has_feedback = false;
$success_message = $error_message = '';

// Check if feedback already exists for this order
if ($order_id) {
    $check_feedback_query = "SELECT feedback FROM orders WHERE id = ? AND email_address = ?";
    $stmt = $con->prepare($check_feedback_query);
    $stmt->bind_param("is", $order_id, $user_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($feedback);
        $stmt->fetch();
        if (!empty($feedback)) {
            $has_feedback = true;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
    $feedback = $_POST['feedback'];

    if (!empty($feedback) && $order_id) {
        if ($has_feedback) {
            $error_message = "You have already submitted feedback for this product.";
        } else {
            // Update the feedback in the database
            $feedback_query = "UPDATE orders SET feedback = ? WHERE id = ? AND email_address = ?";
            $stmt = $con->prepare($feedback_query);
            $stmt->bind_param("sis", $feedback, $order_id, $user_email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success_message = "Feedback submitted successfully.";
                $has_feedback = true; // Update the status after successful submission
            } else {
                $error_message = "Unable to submit feedback. Ensure the order exists.";
            }
        }
    } else {
        $error_message = "Feedback cannot be empty or invalid order selected.";
    }
}

// Fetch user feedback and admin replies
$query = "SELECT product_name, feedback, admin_reply FROM orders WHERE email_address = ? AND feedback IS NOT NULL";
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
    <title>Fashi | Template</title>

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
        .table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-collapse: separate;
    border-radius: 10px;
    overflow: hidden;
}

.table th, .table td {
    padding: 15px;
    text-align: left;
    vertical-align: middle;
    border-bottom: 1px solid #ddd;
    font-size: 14px;
}

.table thead th {
    background-color: #191919; /* Dark header to match the theme */
    color: #fff;
    text-transform: uppercase;
    font-weight: bold;
}

.table tbody tr:nth-child(even) {
    background-color: #f1f1f1; /* Alternate row color */
}

.table tbody tr:hover {
    background-color: #f7e8d4; /* Light highlight on hover */
}

.table .text-success {
    font-weight: bold;
    color: #28a745 !important; /* Green for admin replies */
}

.table .text-muted {
    color: #6c757d !important; /* Muted gray for no replies */
}

.table td:first-child, .table th:first-child {
    border-left: 0;
}

.table td:last-child, .table th:last-child {
    border-right: 0;
}
.orders-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 50px auto;
        }
h2{
    font-size: 32px;
            color: #333;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
}
h3{
    font-size: 28px;
            color: #333;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
}
.btn{
    background-color:#e7ab3c;
    color:white;
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

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="#"><i class="fa fa-home"></i> Home</a>
                        <span>Feedback</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

   

    <div class="orders-container my-5">
        <h2>Your Feedback</h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Feedback Form -->
        <!-- Feedback Form -->
        <?php if (!$has_feedback): ?>
        <form action="feedback.php?order_id=<?= htmlspecialchars($order_id); ?>" method="POST">
            <div class="form-group">
                <label for="feedback">Write Your Feedback:</label>
                <textarea name="feedback" id="feedback" class="form-control" rows="4" placeholder="Enter your feedback here..."></textarea>
            </div>
            <button type="submit" name="submit_feedback" class="btn btn-warning">Submit Feedback</button>
        </form>
        <?php else: ?>
            <div class="alert alert-info">You have already submitted feedback for this product.</div>
        <?php endif; ?> <!-- This line was missing -->

        <h3 class="mt-5">Your Feedback History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Your Feedback</th>
                    <th>Admin Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_name']); ?></td>
                        <td><?= htmlspecialchars($row['feedback']); ?></td>
                        <td>
                            <?php if ($row['admin_reply']): ?>
                                <span class="text-success"><?= htmlspecialchars($row['admin_reply']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">No reply yet</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
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
    <script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>