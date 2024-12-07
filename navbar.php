<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
        .ht-right {
            display: flex;
            align-items: center;
        }
        .profile-icon {
    position: relative;
    display: inline-block;
    margin-left: 20px;
    cursor: pointer;
}

.profile-icon img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid #f2f2f2;
    transition: transform 0.2s ease-in-out;
}

.profile-icon img:hover {
    transform: scale(1.1); /* Subtle zoom effect on hover */
}

.profile-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 50px;
    background-color: #fff;
    min-width: 180px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    z-index: 10;
    border: 1px solid #ddd;
}

.profile-dropdown a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.profile-dropdown a:hover {
    background-color: #f7d98c; /* Match your theme's yellow tone */
    color: #000;
}

.profile-dropdown::before {
    content: '';
    position: absolute;
    top: -10px;
    right: 15px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent #fff transparent;
    z-index: 5;
}

.profile-icon:hover .profile-dropdown {
    display: block;
}

.profile-dropdown a:first-child {
    font-weight: bold;
    color: #555; /* Highlight email for visibility */
    background-color: #f9f9f9;
}

.profile-dropdown a:first-child:hover {
    background-color: #f1f1f1;
    color: #333;
}
.cart-icon {
    position: relative;
    display: inline-block;
    font-size: 20px; /* Adjust for desired icon size */
    color: #333; /* Default icon color */
    margin-right: 20px;
    cursor: pointer;
    transition: color 0.2s ease-in-out;
}

.cart-icon:hover {
    color: #e7ab3c; /* Highlight color on hover */
}

.cart-icon i {
    font-size: 24px; /* Adjust icon size */
}

#cart-quantity {
    position: absolute;
    top: -8px; /* Adjust position as needed */
    right: -8px; /* Adjust position as needed */
    background-color: #e7ab3c; /* Match theme color */
    color: red;
    font-size: 10px; /* Adjust font size for better fit */
    width: 20px; /* Set fixed width */
    height: 20px; /* Set fixed height */
    display: flex; /* Use flexbox for centering */
    align-items: center; /* Center vertically */
    justify-content: center; /* Center horizontally */
    border-radius: 50%; /* Circular badge */
    font-weight: bold; /* Bold number */
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
    border:1px solid black;
}

.cart-icon a {
    text-decoration: none; /* Remove underline */
}

.cart-icon span {
    display: inline-block;
    }
    
    .inner-header .advanced-search {
	height: 50px;
	border:none;
    width:800px;
}
@media only screen and (min-width: 1200px) and (max-width: 1920px) {
    .inner-header .advanced-search .input-group button {
        right: 0.1px !important;
    }
}
.inner-header .advanced-search .input-group button {
    font-size: 16px;
    color: #ffffff;
    position: absolute;
    right: 1px !important;
    top: 1.2px !important;
    height: 48px !important;
    border: 1px solid #e7ab3c;
    background: #e7ab3c;
    padding: 12px 16px 12px;
    cursor: pointer;
}
.inner-header .advanced-search .input-group input {
	width: 100%;
	height: 100%;
	border: none;
	font-size: 16px;
	color: #918c8cb3 !important;
	padding-left: 20px;
}
    </style>
<body>
<header class="header-section">
        <div class="header-top">
            <div class="container">
            <div class="ht-left">
                    <div class="mail-service">
                        <i class=" fa fa-envelope"></i>
                        giftaura@gmail.com
                    </div>
                    <div class="phone-service">
                        <i class=" fa fa-phone"></i>
                        +92 33.122.812
                    </div>
                </div>
            <div class="ht-right">
                <?php if (isset($_SESSION['email'])): ?>
                    <!-- User is logged in -->
                    <div class="profile-icon">
                        <img src="images/png-transparent-man-avator-person-admin-administrator-face-blank-neutral.png" alt="Profile Icon"> <!-- Icon for logged-in users -->
                        <div class="profile-dropdown">
                            <a href="#"><?php echo $_SESSION['email']; ?></a>
                            <a href="account.php">Account</a>
                            <a href="orders.php">Orders</a>
                            <a href="Feedback.php">Feedback</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- User is not logged in -->
                    <div class="profile-icon">
                        <img src="images/logged-out-icon.png" alt="Profile Icon"> <!-- Icon for not logged-in users -->
                        <div class="profile-dropdown">
                            <a href="login.php">Login</a>
                            <a href="register.php"><b>Register</b></a>

                        </div>
                    </div>
                <?php endif; ?>
                    <div class="top-social">
                        <a href="#"><i class="ti-facebook"></i></a>
                        <a href="#"><i class="ti-twitter-alt"></i></a>
                        <a href="#"><i class="ti-linkedin"></i></a>
                        <a href="#"><i class="ti-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="inner-header">
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <div class="logo">
                            <a href="index.php">
                                <img src="img/newlogo.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7">
                        <div class="advanced-search">
                        <div class="input-group">
    <form action="shop.php" method="GET" style="display: flex; width: 100%;">
        <input type="text" name="search" placeholder="What do you need?" style="flex-grow: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px 0 0 4px;">
        <button type="submit" style="background-color: #e7ab3c; border: none; padding: 8px 12px; cursor: pointer; border-radius: 0 4px 4px 0;">
            <i class="fa fa-search "></i>
        </button>
    </form>
</div>
                        </div>
                    </div>
                    <div class="col-lg-3 text-right col-md-3">
                        <ul class="nav-right">
                            
                        <li class="cart-icon">
    <a href="shopping-cart.php">
        <i class="icon_bag_alt"></i>
        <span id="cart-quantity">
            <?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>
        </span>
    </a>
</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-item text-center">
            <div class="container">
                
                <nav class="nav-menu mobile-menu">
    <ul>
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">
            <a href="index.php">Home</a>
        </li>
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : '' ?>">
            <a href="shop.php">Shop</a>
        </li>
        <!-- <li class="<?= in_array(basename($_SERVER['PHP_SELF']), ['collection.php', 'men.php', 'women.php', 'kids.php']) ? 'active' : '' ?>">
            <a href="#">Collection</a>
            <ul class="dropdown">
                <li><a href="#">Men's</a></li>
                <li><a href="#">Women's</a></li>
                <li><a href="#">Kid's</a></li>
            </ul>
        </li> -->
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'blog.php') ? 'active' : '' ?>">
            <a href="blog.php">Blog</a>
        </li>
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : '' ?>">
            <a href="contact.php">Contact</a>
        </li>
        <li class="<?= in_array(basename($_SERVER['PHP_SELF']), ['blog-details.php', 'shopping-cart.php', 'check-out.php', 'faq.php', 'register.php', 'login.php']) ? 'active' : '' ?>">
            <a href="faq.php">Help</a>
            
        </li>
       
    </ul>
</nav>

                <div id="mobile-menu-wrap"></div>
            </div>
        </div>
    </header>
</body>
</html>