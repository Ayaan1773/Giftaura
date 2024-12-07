<?php
include('connection.php'); // Ensure this file connects to your database
session_start();

if (isset($_POST['btn'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);

    if (!empty($email) && !empty($pass)) {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM login WHERE email = ? AND pass = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Increment visitor count
            $visitor_query = "UPDATE visitor_count SET count = count + 1 WHERE id = 1";
            mysqli_query($con, $visitor_query);

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header("Location: adminpanel/index.php");
            } elseif ($user['role'] == 'employee') {
                header("Location: employeepanel/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            echo "<script>alert('Invalid email or password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
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
        /* Error message styling */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        /* Red border for invalid inputs */
        input.input-error {
            border: 2px solid red !important;
        }

        /* Centering the form */
        .register-login-section {
            padding: 50px 0;
        }

        .login-form {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-btn {
            width: 100%;
            border: none;
            color: white;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .login-btn:hover {
            background-color: #f36f39;
        }
        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #888;
        }

        .toggle-password:hover {
            color: #333;
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
                        <span>Login</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Form Section Begin -->

    <!-- Register Section Begin -->
    <!-- Login Section -->
    <div class="register-login-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="login-form">
                        <h2>Login</h2>
                        <form id="loginForm" method="post">
                            <div class="group-input">
                                <label for="email">Email address *</label>
                                <input type="text" id="email" name="email" placeholder="Enter your email">
                                <span class="error-message" id="emailError"></span>
                            </div>
                            <div class="group-input">
                                <label for="password">Password *</label>
                                <div class="password-container">
                                    <input type="password" id="password" name="pass" placeholder="Enter your password">
                                    <i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
                                </div>
                                <span class="error-message" id="passwordError"></span>
                            </div>
                            <button type="submit" name="btn" class="site-btn login-btn">Sign In</button>
                        </form>
                        <div class="switch-login">
                            <a href="register.php" class="or-login">Or Create An Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <!-- Custom JavaScript -->
     <script>
         // Toggle Password Visibility
         function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
     </script>
    <script>
        
        document.addEventListener("DOMContentLoaded", function () {
            const loginForm = document.getElementById("loginForm");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

            // Email validation regex pattern
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Form submit event listener
            loginForm.addEventListener("submit", function (event) {
                event.preventDefault();

                let isValid = true;

                // Email validation
                if (emailInput.value.trim() === "") {
                    emailError.textContent = "Email field can't be empty";
                    emailError.style.display = "block";
                    emailInput.classList.add("input-error");
                    isValid = false;
                } else if (!emailPattern.test(emailInput.value)) {
                    emailError.textContent = "Please enter a valid email address";
                    emailError.style.display = "block";
                    emailInput.classList.add("input-error");
                    isValid = false;
                } else {
                    emailError.style.display = "none";
                    emailInput.classList.remove("input-error");
                }

                // Password validation
                if (passwordInput.value.trim() === "") {
                    passwordError.textContent = "Password field can't be empty";
                    passwordError.style.display = "block";
                    passwordInput.classList.add("input-error");
                    isValid = false;
                } else {
                    passwordError.style.display = "none";
                    passwordInput.classList.remove("input-error");
                }

                // Submit the form if valid
                
                    // Uncomment the following line when connecting with PHP server
                    // loginForm.submit();
                }
            });
        });
    </script>
</body>

</html>