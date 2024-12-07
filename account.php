<?php
include('connection.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user details from orders table
$query = "SELECT * FROM orders WHERE email_address = '$email'";
$result = mysqli_query($con, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
    if (!$user) {
        echo "No user found with email: $email.<br>";
    }
} else {
    echo "Error fetching user details: " . mysqli_error($con) . "<br>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form data safely
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);

    // Update the database
    $update_query = "
        UPDATE orders 
        SET first_name = '$first_name', last_name = '$last_name', phone = '$phone', address = '$address', city = '$city' 
        WHERE email_address = '$email'
    ";

    if (mysqli_query($con, $update_query)) {
        echo "<script>alert('Details updated successfully!');</script>";

        // Reload user data after successful update
        $result = mysqli_query($con, $query);
        if ($result) {
            $user = mysqli_fetch_assoc($result);
        } else {
            echo "Error fetching updated details: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "Error updating details: " . mysqli_error($con) . "<br>";
    }
}
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
        .user-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: 'Muli', sans-serif;
        }

        .user-details-table th,
        .user-details-table td {
            padding: 10px 15px;
            text-align: left;
        }

        .user-details-table th {
            background-color: #E7AB3C;
            color: white;
            font-weight: bold;
        }

        .user-details-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .user-details-table input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .user-details-table input:focus {
            border-color: #E7AB3C;
            outline: none;
            box-shadow: 0 0 5px rgba(231, 171, 60, 0.5);
        }

        .form-error {
            color: red;
            font-size: 0.85em;
            margin-top: 5px;
            display: block;
        }

        .change-password-btn {
            background-color: #E7AB3C;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-family: 'Muli', sans-serif;
        }

        .change-password-btn:hover {
            background-color: #cf952e;
        }
        h2 {
            font-size: 32px;
            color: #333;
            font-weight: 700;
            text-align: center;
            margin-top:15px;
        }
        .orders-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 50px auto;
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
        <div class="container">
            <h2 class="mb-4">Order Details</h2>

            <!-- Inline Editing Form -->
            <form method="POST" action="" onsubmit="return validateForm()">
                <table class="user-details-table">
                    <tr>
                        <td>First Name</td>
                        <td>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                            <span class="form-error" id="error-first_name"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                            <span class="form-error" id="error-last_name"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Email (Read-Only)</td>
                        <td>
                            <input type="text" value="<?php echo htmlspecialchars($user['email_address']); ?>" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                            <span class="form-error" id="error-phone"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                            <span class="form-error" id="error-address"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td>
                            <input type="text" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            <span class="form-error" id="error-city"></span>
                        </td>
                    </tr>
                </table>
                <button type="submit" class="change-password-btn">Save Changes</button>
            </form>
        </div>
    </div>
<br>
<br>
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
    <script>
        function validateForm() {
            let isValid = true;
            const errorFields = {
                first_name: "First name is required.",
                last_name: "Last name is required.",
                phone: "Phone is required and must be 10 digits.",
                address: "Address is required.",
                city: "City is required."
            };

            Object.keys(errorFields).forEach(field => {
                const input = document.getElementsByName(field)[0];
                const errorElement = document.getElementById(`error-${field}`);
                errorElement.textContent = ""; // Clear previous error

                if (!input.value.trim()) {
                    isValid = false;
                    errorElement.textContent = errorFields[field];
                }

                if (field === "phone" && input.value && !/^\d{10}$/.test(input.value)) {
                    isValid = false;
                    errorElement.textContent = "Phone must be 10 digits.";
                }
            });

            return isValid;
        }
    </script>
</body>

</html>
