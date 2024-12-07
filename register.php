<?php 
include('connection.php'); 
session_start(); 

$error_message = ""; // Initialize error message variable

if (isset($_POST['btn'])) { 
    $email = $_POST['email']; 
    $pass = $_POST['pass']; 

    // Check if email already exists 
    $check_query = mysqli_query($con, "SELECT * FROM login WHERE email='$email'"); 

    if (mysqli_num_rows($check_query) > 0) { 
        // Email already exists 
        $error_message = "Email already exists"; 
    } else { 
        // Insert new record 
        $query = mysqli_query($con, "INSERT INTO login (id, email, pass, role) VALUES (NULL, '$email', '$pass', 'user')"); 
        // Redirect to login page after successful registration 
        header("Location: login.php"); 
        exit(); 
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
         .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .invalid-input {
            border: 2px solid red !important;
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
                        <span>Register</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Register Section Begin -->
    <div class="register-login-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                <div class="register-form">
    <h2>Register</h2>
    
    
    <form method="post" id="registerForm" onsubmit="return validateForm()">
        <div class="group-input">
            <label for="email">Email Address *</label>
            <input type="text" id="email" name="email" class="<?= $error_message ? 'invalid-input' : '' ?>">
            <?php if ($error_message): ?>
        <div class="error" style="color:red;"><?= $error_message; ?></div>
    <?php endif; ?>

        </div>
        <div class="group-input">
    <label for="password">Password *</label>
    <div style="position: relative;">
        <input type="password" id="password" name="pass">
        <i class="fa fa-eye toggle-password" data-target="password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
    </div>
    <div id="password-error" class="error"></div>
</div>
<div class="group-input">
    <label for="confirm-password">Confirm Password *</label>
    <div style="position: relative;">
        <input type="password" id="confirm-password">
        <i class="fa fa-eye toggle-password" data-target="confirm-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
    </div>
    <div id="confirm-password-error" class="error"></div>
</div>

        <button type="submit" name="btn" class="site-btn register-btn">REGISTER</button>
    </form>
    
    <div class="switch-login">
        <a href="login.php" class="or-login">Or Login</a>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Register Section End -->
    
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
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm-password');

    const emailError = document.getElementById('email-error');
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('confirm-password-error');

    let isValid = true;

    // Email regex pattern
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Reset previous errors
    if (emailError) emailError.textContent = '';
    if (passwordError) passwordError.textContent = '';
    if (confirmPasswordError) confirmPasswordError.textContent = '';

    // Remove red border from previous invalid inputs
    email.classList.remove('invalid-input');
    password.classList.remove('invalid-input');
    confirmPassword.classList.remove('invalid-input');

    // Email validation
    if (!email.value.trim()) {
        emailError.textContent = 'Email cannot be empty';
        email.classList.add('invalid-input');
        isValid = false;
    } else if (!emailPattern.test(email.value.trim())) {
        emailError.textContent = 'Invalid email format';
        email.classList.add('invalid-input');
        isValid = false;
    }

    // Password validation
    if (!password.value.trim()) {
        passwordError.textContent = 'Password cannot be empty';
        password.classList.add('invalid-input');
        isValid = false;
    } else if (password.value.trim().length < 6) {
        passwordError.textContent = 'Password must be at least 6 characters long';
        password.classList.add('invalid-input');
        isValid = false;
    }

    // Confirm password validation
    if (!confirmPassword.value.trim()) {
        confirmPasswordError.textContent = 'Please confirm your password';
        confirmPassword.classList.add('invalid-input');
        isValid = false;
    } else if (confirmPassword.value.trim() !== password.value.trim()) {
        confirmPasswordError.textContent = 'Passwords do not match';
        confirmPassword.classList.add('invalid-input');
        isValid = false;
    }

    return isValid;
}


// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);

        if (targetInput.type === 'password') {
            targetInput.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            targetInput.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
});

    </script>
</body>

</html>