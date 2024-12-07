<?php
session_start();

// Hardcoded credentials (use database for production)
$valid_username = "admin";
$valid_password = "admin";

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Apply blur effect for the background */
        .blurred-background {
            filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: url('admin_panel_preview.jpg') no-repeat center center;
            background-size: cover;
        }

        /* Center the modal */
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .login-title {
            font-size: 24px;
            font-weight: bold;
        }

        .login-button {
            background-color: #343a40;
            color: white;
        }

        .login-button:hover {
            background-color: #495057;
        }
    </style>
</head>

<body>
    <div class="blurred-background"></div>

    <!-- Login Modal -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title login-title">Admin Login</h5>
            </div>
            <div class="modal-body">
                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-block login-button">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
