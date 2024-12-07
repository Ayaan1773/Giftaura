<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

$con = mysqli_connect($servername, $username, $password, $db_name);

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $con->prepare("SELECT id, password FROM employee WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { // Plain password check
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            
            // Increment employee count in the database
            $update_count_query = "UPDATE employee_count SET count = count + 1 WHERE id = 1";
            mysqli_query($con, $update_count_query);
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Set the overall background */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Centered login card container */
        .login-container {
            background: #ffffff; /* White background for card */
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Dark header for the card */
        .login-header {
            background-color: #343a40; /* Dark gray */
            color: #ffffff;
            padding: 15px 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }

        .login-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        /* Form container inside the card */
        .login-body {
            padding: 20px;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.2);
        }

        /* Submit button */
        .btn-primary {
            background-color: #343a40; /* Dark gray to match header */
            border: none;
            font-size: 16px;
            font-weight: 500;
            height: 45px;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #495057; /* Slightly lighter gray on hover */
        }

        /* Error message styling */
        .text-danger {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
<div class="login-container">
        <div class="login-header">
            <h2>Employee Login</h2>
        </div>
        <div class="login-body">
            <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
        <p class="text-center mt-3">
            Don't have an account? <a href="register.php">Create one</a>.
        </p>
    </div>

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
