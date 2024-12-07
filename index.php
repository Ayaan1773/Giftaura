<?php
session_start();

include('connection.php');

// Check if the user is logged in
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
function getDatabaseConnection() {
    $servername = "localhost"; // Replace with your database server
    $username = "root"; // Replace with your database username
    $password = ""; // Replace with your database password
    $dbname = "giftaura"; // Replace with your database name

    // Create a new connection
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    return $con;
}
function generateBotResponse($user_input) {
    $user_input = strtolower(trim($user_input));

    // Initialize session variable for conversation state if not set
    if (!isset($_SESSION['conversation_state'])) {
        $_SESSION['conversation_state'] = null;
    }

    // Gift Aura responses based on the current state
    switch ($_SESSION['conversation_state']) {
        case null: // Initial state
            if (strpos($user_input, 'hello') !== false || strpos($user_input, 'hi') !== false) {
                $_SESSION['conversation_state'] = 'main_menu';
                return "Hi, I'm here to help you explore Gift Aura! Please select an option below:
                <ul>
                    <li>1. Gift Customization Queries</li>
                    <li>2. Order and Delivery Support</li>
                    <li>3. Account & Login Assistance</li>
                </ul>";
            }
            break;

        case 'main_menu': // Main menu state
            if ($user_input === '1') {
                $_SESSION['conversation_state'] = 'gift_customization';
                return "Looking to create a special gift? Let us know how we can assist you:
                <ul>
                    <li>1. Custom Name Engravings</li>
                    <li>2. Wrapping or Personalization Issues</li>
                    <li>0. Back to Main Menu</li>
                </ul>";
            } elseif ($user_input === '2') {
                $_SESSION['conversation_state'] = 'order_support';
                return "Order-related concerns? Please pick one:
                <ul>
                    <li>1. Order Not Delivered</li>
                    <li>2. Received a Damaged Item</li>
                    <li>3. Need Help with Tracking</li>
                    <li>0. Back to Main Menu</li>
                </ul>";
            } elseif ($user_input === '3') {
                $_SESSION['conversation_state'] = 'account_assistance';
                return "Let’s make sure your account is up and running. Please select an issue:
                <ul>
                    <li>1. Login Issues</li>
                    <li>2. Account Not Verified</li>
                    <li>3. Editing or Updating Account Details</li>
                    <li>0. Back to Main Menu</li>
                </ul>";
            }
            break;

        case 'account_assistance': // Account assistance submenu
        case 'order_support': // Order support submenu
        case 'gift_customization': // Gift customization submenu
            if ($user_input === '0') {
                $_SESSION['conversation_state'] = 'main_menu';
                return "Returning to the main menu. Please select an option:
                <ul>
                    <li>1. Gift Customization Queries</li>
                    <li>2. Order and Delivery Support</li>
                    <li>3. Account & Login Assistance</li>
                </ul>";
            }
            
            // Existing submenu logic...
            if ($_SESSION['conversation_state'] === 'account_assistance') {
                if ($user_input === '1') {
                    return "For Login Issues please visit <a href='login.php'>Login</a>.";
                } elseif ($user_input === '2') {
                    return "For Account Verification, please contact GiftAura support, please visit <a href='contact.php'>Contact Us</a>.";
                } elseif ($user_input === '3') {
                    return "To update your account details, go to your profile or visit <a href='account.php'>Update Account Details</a>.";
                }
            } elseif ($_SESSION['conversation_state'] === 'order_support') {
                if ($user_input === '1') {
                    return "If Order Not Delivered, please contact GiftAura support at <a href='contact.php'>Contact Us</a>.";
                } elseif ($user_input === '2') {
                    return "If you received a damaged item, request a return at <a href='orders.php'>Return Request</a>.";
                } elseif ($user_input === '3') {
                    return "Track your order at <a href='orders.php'>Track Order Status</a>.";
                }
            } elseif ($_SESSION['conversation_state'] === 'gift_customization') {
                if ($user_input === '1') {
                    return "Unfortunately, custom name engravings are not available.";
                } elseif ($user_input === '2') {
                    return "We currently cannot assist with wrapping or personalization issues.";
                }
            }
            break;

        default:
            return "Sorry, I didn't understand that. Could you rephrase or select an option from the menu?";
    }

    // Fallback if no valid option was chosen
    return "Sorry, I didn't understand that. Please select an option from the menu.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure user is logged in
    if (!$email) {
        echo "<div class='message-content bot-msg'>Please <a href='login.php'>log in</a> to use the chat feature.</div>";
        exit;
    }

    $user_input = trim($_POST['user_input']);
    saveChatMessage($email, 'user', $user_input);
    $bot_response = generateBotResponse($user_input);
    saveChatMessage($email, 'bot', $bot_response);

    // Output the new messages to update the chat
    echo "<div class='message-content sender-msg'>" . htmlspecialchars($user_input) . "</div>";
    echo "<div class='message-content bot-msg'>" . $bot_response . "</div>";
    exit;
}


// Fetch chat history
$chat_history = $email ? fetchChatHistory($email) : [];

// Save chat message to the database
function saveChatMessage($email, $sender_type, $message) {
    $con = getDatabaseConnection();
    $stmt = $con->prepare("INSERT INTO chat_history (email, sender_type, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $sender_type, $message);
    $stmt->execute();
    $stmt->close();
    $con->close();
}

// Fetch chat history from the database
function fetchChatHistory($email) {
    $con = getDatabaseConnection();
    $stmt = $con->prepare("SELECT sender_type, message FROM chat_history WHERE email = ? ORDER BY created_at ASC");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $chat_history = [];
    while ($row = $result->fetch_assoc()) {
        $chat_history[] = $row;
    }

    $stmt->close();
    $con->close();

    return $chat_history;
}
date_default_timezone_set('Asia/Karachi'); 

// Initialize $showAlert to avoid undefined variable warnings
$showAlert = false;

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
    $con = getDatabaseConnection();

    // Fetch the last added time of an item in the cart for the user
    $stmt = $con->prepare("SELECT MAX(added_at) FROM cart WHERE user_email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->bind_result($lastAddedTime);
    $stmt->fetch();
    $stmt->close();

    if ($lastAddedTime) {
        $currentTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $lastAddedTimeObj = new DateTime($lastAddedTime, new DateTimeZone('Asia/Karachi'));

        // Calculate the time difference
        $interval = $currentTime->diff($lastAddedTimeObj);

        // Check if 10 seconds have passed
        if ($interval->i == 0 && $interval->s >= 10) {
            $showAlert = true;
        }
    }
}

// Use $showAlert safely, even if the user is logged out
if ($showAlert) {
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
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
         /* General page styles */
          /* Chat window container */
         /* Chat window container */
         .chat-window {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            max-width: 100%;
            height: 400px;
            background-color: #fff;
            border-radius: 8px;
            display: none; /* Hidden initially */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            flex-direction: column;
            overflow: hidden;
        }

        /* Chat header */
        .chat-header {
            background-color: #e7ab3c;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* Message display area */
        .message-display {
            height: 300px;
            overflow-y: auto;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }

        /* Individual message */
        .message-content {
            margin: 8px 0;
            padding: 10px;
        }

        /* User message (orange background) */
        .sender-msg {
    display: inline-block; /* Adjusts width based on content */
    margin-top: 0.125rem;
    padding: 10px; /* Adds padding around text */
    background-color: #e7ab3c; /* Orange background */
    color: white; /* White text color */
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Arial, sans-serif;
    font-size: 14px;
    border-radius: 1.25rem 1.25rem 0px 1.25rem; /* Rounded corners */
    text-align: left; /* Align text inside the bubble */
    max-width: 80%; /* Prevents the box from being too wide */
    word-wrap: break-word; /* Wraps long words to fit inside the bubble */
    margin-left: auto; /* Aligns the box to the right */
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow */
}

/* Bot message */
.bot-msg {
    margin-top: 0.125rem;
    min-width: 3.25rem;
    transition: border-radius 0.15s linear 0.15s, opacity 0.3s linear, background-color 0.2s linear;
    overflow: hidden;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Arial, sans-serif;
    border-color: rgb(233, 235, 237);
    background-color: rgb(244, 246, 248);
    color: rgb(0, 0, 0);
    max-width: calc(100% - 6.25rem);
    border-radius: 1.25rem 1.25rem 1.25rem 0px;
    padding: 10px; /* Add padding for proper text alignment */
    font-size: 14px;
    word-wrap: break-word; /* Handles long words */
}
        /* Input and send button */
        .chat-input {
            display: flex;
            padding: 10px;
            background-color: #f9f9f9;
        }

        input[type="text"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .send-button {
            padding: 10px 15px;
            background-color: #e7ab3c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 10px;
        }

        .send-button:hover {
            background-color: #d68910;
        }

        /* Chat icon at the bottom right */
        .chat-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #e7ab3c;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }
        /* Toggle icon style */
.toggle-icon {
    float: right;
    font-size: 18px;
    cursor: pointer;
    margin-right: 10px;
    transition: transform 0.3s ease;
}

/* Rotate icon when chat is open */
.chat-window.open .toggle-icon {
    transform: rotate(180deg);
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
     <?php if ($showAlert): ?>
    <script type="text/javascript">
        alert("Items In Your Cart! Proceed to Checkout");
    </script>
<?php endif; ?>

    <!-- Header End -->
     <!-- Chat Icon -->
     <div class="chat-icon" onclick="toggleChatWindow()">💬</div>

<!-- Chat Window -->
<div class="chat-window" id="chatWindow">
<div class="chat-header">
    GiftAura Support
    <span class="toggle-icon" onclick="toggleChatWindow()">▲</span>
</div>    <div class="message-display" id="messageDisplay">
        <?php if ($email): ?>
            <?php foreach ($chat_history as $chat): ?>
    <div class="message-content <?= $chat['sender_type'] == 'user' ? 'sender-msg' : 'bot-msg' ?>">
        <?php if ($chat['sender_type'] == 'user'): ?>
            <?= htmlspecialchars($chat['message']) ?>
        <?php else: ?>
            <?= $chat['message'] ?> <!-- Allow bot messages to render HTML -->
        <?php endif; ?>
    </div>
<?php endforeach; ?>
        <?php else: ?>
            <div class="message-content bot-msg">
                Please <a href="login.php">log in</a> to use the chat feature.
            </div>
        <?php endif; ?>
    </div>
    <?php if ($email): ?>
        <form id="chatForm" class="chat-input">
            <input type="text" name="user_input" placeholder="Type your message..." required>
            <button type="submit" class="send-button">Send</button>
        </form>
    <?php endif; ?>
</div>


    <!-- Hero Section Begin -->
    <section class="hero-section">
    <div class="hero-items owl-carousel">
        <div class="single-hero-items set-bg" data-setbg="img/hero-1.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <span>Gifts, Stationery</span>
                        <h1>Exclusive Offers</h1>
                        <p>Explore our latest collection of unique gifts and premium stationery, perfect for every occasion!</p>
                        <a href="shop.php" class="primary-btn">Shop Now</a>
                    </div>
                </div>

                <div class="off-card">
                    <h2>Sale <span>50%</span></h2>
                </div>
            </div>
        </div>
        <div class="single-hero-items set-bg" data-setbg="img/hero-2.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <span>Gifts, Stationery</span>
                        <h1>Seasonal Specials</h1>
                        <p>Find the perfect gift or stylish stationery for your loved ones with our handpicked collections.</p>
                        <a href="shop.php" class="primary-btn">Shop Now</a>
                    </div>
                </div>
                <div class="off-card">
                    <h2>Sale <span>50%</span></h2>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
    <div class="banner-section spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="single-banner">
                        <img src="images/premiumwatches.jpg" alt="" height="200px">
                        <div class="inner-text">
                            <h4 style="background: rgba(0, 0, 0, 0.5) !important; color: #ffffff !important;">Watches</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-banner">
                        <img src="images/premium pen.jfif" alt="" height="200px">
                        <div class="inner-text">
                            <h4 style="background: rgba(0, 0, 0, 0.5) !important; color: #ffffff !important;">Pen</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-banner">
                        <img src="images/premium perfumes.jpg" alt="" height="200px">
                        <div class="inner-text">
                            <h4 style="background: rgba(0, 0, 0, 0.5) !important; color: #ffffff !important;">Perfumes
                            </h4>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
    </div>
    </div>
    <!-- Banner Section End -->

     <!-- Women Banner Section Begin -->
     <section class="women-banner spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="product-large set-bg" data-setbg="images/RalphLaurenOccsonDressing2024.jfif">
                    <h2>Men</h2>
                        <a href="shop.php">Discover More</a>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1">
                    <div class="filter-control">
                       
                    </div>
                    <div class="product-slider owl-carousel">
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images/premiumwatches.jpg" alt="" height ="300px" width ="250px">
                                <div class="sale">Sale</div>
                                <div class="icon">
                                
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Watch</div>
                                <a href="shop.php">
                                    <h5>Fancy Watch</h5>
                                </a>
                                <div class="product-price">
                                    
                                    <span>$60.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images\Dior Sauvage.jpg" alt="" height ="200px">
                                <div class="icon">
                                   
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Perfume</div>
                                <a href="shop.php">
                                    <h5>Premium Perfumes</h5>
                                </a>
                                <div class="product-price">
                                    $70.00
                                </div>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images\premium handbag.jpg" alt="">
                                <div class="icon">
                                    
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Hand Bags</div>
                                <a href="#">
                                    <h5>Pure Handbags</h5>
                                </a>
                                <div class="product-price">
                                    $90.00
                                </div>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images\premium pen.jpg" alt="">
                                <div class="icon">
                                   
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Pen</div>
                                <a href="#">
                                    <h5>Advance pen</h5>
                                </a>
                                <div class="product-price">
                                    $30.00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Women Banner Section End -->

     <!-- Deal Of The Week Section Begin-->
     <section class="deal-of-week set-bg spad" data-setbg="img/time-bg.jpg">
    <div class="container">
    <div class="col-lg-6 text-center">
        <div class="section-title">
            <h2>Deal Of The Week</h2>
            <p>Discover our exclusive deal on premium gifts and stationery items,<br /> perfect for any occasion!</p>
            <div class="product-price">
                $20.00
                <span>/ Stationery Set</span>
            </div>
        </div>
        <div class="countdown-timer" id="countdown">
            <div class="cd-item">
                <span>05</span>
                <p>Days</p>
            </div>
            <div class="cd-item">
                <span>12</span>
                <p>Hrs</p>
            </div>
            <div class="cd-item">
                <span>40</span>
                <p>Mins</p>
            </div>
            <div class="cd-item">
                <span>52</span>
                <p>Secs</p>
            </div>
        </div>
        <a href="shop.php" class="primary-btn">Shop Now</a>
    </div>
</div>

    </section>
    <!-- Deal Of The Week Section End -->

    <!-- Man Banner Section Begin -->
    <section class="man-banner spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <!-- <div class="filter-control">
                        <ul>
                            <li class="active">Clothings</li>
                            <li>HandBag</li>
                            <li>Shoes</li>
                            <li>Accessories</li>
                        </ul>
                    </div> -->
                    <div class="product-slider owl-carousel">
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images/premiumshoes.jpg" alt="">
                                <div class="sale">Sale</div>
                                <div class="icon">
                                    
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Shoes</div>
                                <a href="shop.php">
                                    <h5>Multiple Collection</h5>
                                </a>
                                <div class="product-price">
                                    $30.00
                                    <span>$10.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images/Advance perfume.jpg" alt="">
                                <div class="icon">
                                    
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Perfumes</div>
                                <a href="shop.php">
                                    <h5>Luxury perfume</h5>
                                </a>
                                <div class="product-price">
                                    $34.00
                                </div>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images/Advance hand bags.jpg" alt="">
                                <div class="icon">
                                    
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Handbag collection</div>
                                <a href="shop.php">
                                    <h5>Handbag</h5>
                                </a>
                                <div class="product-price">
                                    $20.00
                                </div>
                            </div>
                        </div>
                        <div class="product-item">
                            <div class="pi-pic">
                                <img src="images\Advance watches.jpg" alt="">
                                <div class="icon">
                                    <i class="icon_heart_alt"></i>
                                </div>
                                <ul>
            <li class="w-icon active text-center !important">
                <!-- Adding the onclick event here -->
                <a href="javascript:void(0);" onclick="goToShopPage()">
                    <i class="icon_bag_alt"></i>&nbspView more
                </a>
            </li>
        </ul>
                            </div>
                            <div class="pi-text">
                                <div class="catagory-name">Watches collection</div>
                                <a href="shop.php">
                                    <h5>Watch</h5>
                                </a>
                                <div class="product-price">
                                    $34.00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1">
                <div class="product-large set-bg" data-setbg="img/products/man-large.jpg">
                        <h2>Men</h2>
                        <a href="shop.php">Discover More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Man Banner Section End -->


    <!-- Instagram Section Begin -->
    <div class="instagram-photo">
        <div class="insta-item set-bg" data-setbg="images/handbag/charlesdeluvio-Ghrv69I9mPw-unsplash.jpg">
            <div class="inside-text">
                <i class="ti-instagram"></i>
                <h5><a href="#">giftaura</a></h5>
            </div>
        </div>
        <div class="insta-item set-bg" data-setbg="images/wallet/emil-kalibradov-wUTYYnC541o-unsplash.jpg">
            <div class="inside-text">
                <i class="ti-instagram"></i>
                <h5><a href="#">giftaura</a></h5>
            </div>
        </div>
        <div class="insta-item set-bg" data-setbg="images/wallet/{6FF72DEB-D089-4A50-A2B1-B329EBF338CD}.png.jpg">
            <div class="inside-text">
                <i class="ti-instagram"></i>
                <h5><a href="#">giftaura</a></h5>
            </div>
        </div>
        <div class="insta-item set-bg" data-setbg="images/wallet/pen.png">
            <div class="inside-text">
                <i class="ti-instagram"></i>
                <h5><a href="#">giftaura</a></h5>
            </div>
        </div>
        <div class="insta-item set-bg" data-setbg="images/wallet/shoes.png.jpg">
            <div class="inside-text">
                <i class="ti-instagram"></i>
                <h5><a href="#">giftaura</a></h5>
            </div>
        </div>
        <div class="insta-item set-bg" data-setbg="images/wallet/perfume.png.jpg">
            <div class="inside-text">
                <i class="ti-instagram"></i>
                <h5><a href="#">giftaura</a></h5>
            </div>
        </div>
    </div>
    <!-- Instagram Section End -->


    <!-- Latest Blog Section Begin -->
    <section class="latest-blog spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>From The Blog</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single-latest-blog">
                        <a href="blog.php">
                            <img src="images/handbag/charlesdeluvio-Ghrv69I9mPw-unsplash.jpg" alt="" style="width: 300px; height: 300px;"></a>
                        <div class="latest-text">
                            <div class="tag-list">
                                <div class="tag-item">
                                    <i class="fa fa-calendar-o"></i>
                                    May 4,2019
                                </div>
                                <div class="tag-item">
                                    <i class="fa fa-comment-o"></i>
                                    5
                                </div>
                            </div>
                            <a href="#">
                                <h4>The Bag </h4>
                            </a>
                            <p>Experience the perfect blend of style and functionality
                         with this beautifully crafted bag. Designed to suit any occasion. </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-latest-blog">
                        <a href="blog.php"> <img src="images/wallet/Sailor 110th Anniversary Fountain Pen - Premium (Limited Edition) - Broad.jfif" alt="" style="width: 300px; height: 300px;"></a>
                        <div class="latest-text">
                            <div class="tag-list">
                                <div class="tag-item">
                                    <i class="fa fa-calendar-o"></i>
                                    May 4,2025
                                </div>
                                <div class="tag-item">
                                    <i class="fa fa-comment-o"></i>
                                    5
                                </div>
                            </div>
                            <a href="#">
                                <h4>Premium pen </h4>
                            </a>
                            <p>"Introducing a premium stationery collection that blends timeless elegance with everyday functionality.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single-latest-blog">
                        <a href="blog.php"><img src="images\Gift Bags.jpg" alt="" style="width: 300px; height: 300px;"></a>
                        <div class="latest-text">
                            <div class="tag-list">
                                <div class="tag-item">
                                    <i class="fa fa-calendar-o"></i>
                                    jan ,23,2019
                                </div>
                                <div class="tag-item">
                                    <i class="fa fa-comment-o"></i>
                                    5
                                </div>
                            </div>
                            <a href="#">
                                <h4>How To Handbags</h4>
                            </a>
                            <p>This handbag combines elegance and practicality,
                                 crafted with premium materials to withstand 
                                 daily wear and tear. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="benefit-items">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-benefit">
                            <div class="sb-icon">
                                <img src="img/icon-1.png" alt="">
                            </div>
                            <div class="sb-text">
                                <h6>Free Shipping</h6>
                                <p>For all order over 99$</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="single-benefit">
                            <div class="sb-icon">
                                <img src="img/icon-2.png" alt="">
                            </div>
                            <div class="sb-text">
                                <h6>Delivery On Time</h6>
                                <p>If good have prolems</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="single-benefit">
                            <div class="sb-icon">
                                <img src="img/icon-1.png" alt="">
                            </div>
                            <div class="sb-text">
                                <h6>Secure Payment</h6>
                                <p>100% secure payment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Blog Section End -->

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
        function toggleChatWindow() {
    const chatWindow = document.getElementById('chatWindow');
    const isVisible = chatWindow.style.display === 'flex';
    
    chatWindow.style.display = isVisible ? 'none' : 'flex';
    
    // Toggle the class for rotating the icon
    if (!isVisible) {
        chatWindow.classList.add('open');
    } else {
        chatWindow.classList.remove('open');
    }
}

        <?php if ($email): ?>
        // This handles the submission of the chat form via AJAX.
        const chatForm = document.getElementById('chatForm');
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            const formData = new FormData(chatForm); // Collect form data

            fetch('', {
                method: 'POST',
                body: formData, // Send form data
            })
            .then(response => response.text()) // Get the response as text
            .then(data => {
                // Append the new message and refresh the chat window
                const messageDisplay = document.getElementById('messageDisplay');
                messageDisplay.innerHTML += data; // Add the new message to the chat
                messageDisplay.scrollTop = messageDisplay.scrollHeight; // Scroll to the bottom
                chatForm.reset(); // Reset form input field
            });
        });
<?php endif; ?>
    </script>

    <script>
    // Function to redirect to the desired page
    function goToShopPage() {
        window.location.href = 'shop.php'; // Change to your target page
    }
</script>
<script>
        function checkCartAlert() {
    fetch('index.php', {
        method: 'GET',
        credentials: 'include', // Ensures cookies (sessions) are sent
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.alert) {
            } else {
                console.log('No alert:', data);
            }
        })
        .catch(error => console.error('Error:', error));
}
    </script>
</body>

</html>
