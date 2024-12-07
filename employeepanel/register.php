<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

$con = mysqli_connect($servername, $username, $password, $db_name);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $stmt = $con->prepare("SELECT id FROM employee WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->get_result();

    if ($stmt->affected_rows > 0) {
        $error = "Username already exists!";
    } else {
        // Insert new user
        $stmt = $con->prepare("INSERT INTO employee (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        .btn {
            background-color: #333;
            color: #fff;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            width: 100%;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #555;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #d9534f;
            text-align: center;
        }

        .register-container p {
            margin-top: 1rem;
            color: #555;
        }

        .register-container a {
            color: #007bff;
            text-decoration: none;
        }

        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
<div class="register-container">
        <h2>Create Account</h2>
        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="text-center">
            Already have an account? <a href="login.php">Log in</a>.
        </p>
    </div>
</body>

</html>
