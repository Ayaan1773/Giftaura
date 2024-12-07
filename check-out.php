<?php
include('connection.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
} else {
    $total = 0; // Default value in case of no POST data
}
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($con, $_POST['payment_method']) : '';
    

if (empty($payment_method)) {
    echo "<script>alert('Please select a payment method.'); window.location.href = 'check-out.php';</script>";
    exit;
}

    // Initialize total price
    $total_price = 0;

    // Fetch cart items for the current user
    $cart_query = "SELECT * FROM cart WHERE user_email = '$email'";
    $cart_result = mysqli_query($con, $cart_query);

    if (mysqli_num_rows($cart_result) > 0) {
        // Process each cart item
        while ($item = mysqli_fetch_assoc($cart_result)) {
            $product_id = $item['product_id'];
            $unique_product_id = $item['unique_product_id'];
            $product_name = $item['name'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $product_total = $quantity * $price;

            $total_price += $product_total;
            

            // Generate unique 16-digit order ID
            $delivery_type = ($payment_method === 'cod') ? '1' : '2'; // 1 for COD, 2 for online
            $unique_order_id = $delivery_type . str_pad($unique_product_id, 7, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);

            // Insert order details into the database
            $query = "INSERT INTO orders (first_name, last_name, email_address, phone, address, city, unique_order_id, product_name, quantity, payment_method, final_price)
          VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$unique_order_id', '$product_name', '$quantity', '$payment_method', '$product_total')";
$result = mysqli_query($con, $query);

if (!$result) {
    echo "<script>alert('Failed to place order for product: $product_name. Please try again.');</script>";
    exit;
}
        }
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo "Your cart is empty!";
            exit();
        }
        
        $cart = $_SESSION['cart'];
        $orderSuccess = true;
        
        foreach ($cart as $item) {
            $productId = $item['id'];
            $quantityOrdered = $item['quantity'];
        
            // Fetch the current stock
            $stockQuery = "SELECT stock FROM cards WHERE id = $productId";
            $stockResult = mysqli_query($con, $stockQuery);
        
            if (!$stockResult) {
                echo "Error checking stock: " . mysqli_error($con);
                $orderSuccess = false;
                break;
            }
        
            $product = mysqli_fetch_assoc($stockResult);
            $currentStock = (int) $product['stock'];
        
            // Check if stock is sufficient
            if ($quantityOrdered > $currentStock) {
                echo "Sorry, only $currentStock units of {$item['name']} are available.";
                $orderSuccess = false;
                break;
            }
        
            // Deduct stock
            $newStock = $currentStock - $quantityOrdered;
            $updateStockQuery = "UPDATE cards SET stock = $newStock WHERE id = $productId";
        
            if (!mysqli_query($con, $updateStockQuery)) {
                echo "Error updating stock for {$item['name']}: " . mysqli_error($con);
                $orderSuccess = false;
                break;
            }
        }

        // Clear the user's cart from the database
$clear_cart_query = "DELETE FROM cart WHERE user_email = '$email'";
mysqli_query($con, $clear_cart_query);

// Clear the cart from the session
if ($orderSuccess) {
    unset($_SESSION['cart']);
    header("Location: shop.php"); // Redirect to success page
    exit();
} else {
    echo "There was an issue with your order. Please try again.";
} // This will clear the cart from the session


        // Redirect to confirmation page
        echo "<script>document.getElementById('thankYouPopup').style.display = 'flex';</script>";
    } else {
        echo "<script>alert('Your cart is empty!'); window.location.href = 'shop.php';</script>";
    }
}
$stmt = $con->prepare("UPDATE cart SET added_at = NULL WHERE user_email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$stmt->close();
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
         .error-message {
            font-size: 12px;
            color: red;
            margin-top: 5px;
            display: block;
        }

        .site-btn.place-btn {
            margin-top: 20px;
            background-color: #E7AB3C !important;
            color: white;
            border: none;
        }
        /* Button Styling (existing button styles) */
.order-btn {
    text-align: center;
    margin-top: 20px;
}

.site-btn.place-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.site-btn.place-btn:hover {
    background-color: #0056b3;
}

/* Popup background styling */
/* Improved Popup Styling */
.popup {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6); /* Darker overlay for focus */
    z-index: 1000;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.3s ease-in-out; /* Smooth fade-in effect */
}

/* Popup Content */
.popup-content {
    background: #fff; /* Clean white background */
    padding: 30px 20px;
    text-align: center;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
    width: 90%;
    max-width: 400px;
    animation: scaleUp 0.3s ease-in-out; /* Scale-up animation */
}

/* Success Tick Image */
.popup-tick {
    width: 80px;
    height: 80px;
    margin-bottom: 15px;
    animation: bounce 1s ease infinite; /* Gentle bounce animation */
}

/* Heading */
.popup-content h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
    font-weight: bold;
    font-family: 'Muli', sans-serif;
}

/* Continue Shopping Button */
.continue-shopping-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 12px 25px;
    background: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Muli', sans-serif;
    transition: all 0.3s ease;
}

.continue-shopping-btn:hover {
    background: #0056b3; /* Slightly darker blue on hover */
    box-shadow: 0 4px 10px rgba(0, 91, 179, 0.3); /* Glow effect on hover */
}

/* Keyframes for Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes scaleUp {
    from {
        transform: scale(0.8);
    }
    to {
        transform: scale(1);
    }
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}



    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <?php include('navbar.php'); ?>
    <!-- Header End -->

    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="shop.php">Shop</a>
                        <span>Check Out</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Checkout Section Begin -->
    <section class="checkout-section spad">
        <div class="container">
        <form action="check-out.php" method="POST" class="checkout-form" onsubmit="validateForm(event)">
        <div class="row">
                    <div class="col-lg-6">
                        <h4>Billing Details</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="fir">First Name<span>*</span></label>
                                <input type="text" id="fir" name="first_name">
                                <span class="error-message" id="error-first_name"></span>
                            </div>
                            <div class="col-lg-6">
                                <label for="last">Last Name<span>*</span></label>
                                <input type="text" id="last" name="last_name">
                                <span class="error-message" id="error-last_name"></span>
                            </div>
                            <div class="col-lg-12">
                                <label for="email">Email Address<span>*</span></label>
                                <input type="email" id="email" name="email">
                                <span class="error-message" id="error-email"></span>
                            </div>
                            <div class="col-lg-12">
                                <label for="phone">Phone<span>*</span></label>
                                <input type="text" id="phone" name="phone">
                                <span class="error-message" id="error-phone"></span>
                            </div>
                            <div class="col-lg-12">
                                <label for="address">Address<span>*</span></label>
                                <input type="text" id="address" name="address">
                                <span class="error-message" id="error-address"></span>
                            </div>
                            <div class="col-lg-12">
                                <label for="city">City<span>*</span></label>
                                <input type="text" id="city" name="city">
                                <span class="error-message" id="error-city"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="place-order">
                            <h4>Your Order</h4>
                            <div class="order-total">
                                <ul class="order-table">
                                    <li>Product <span>Total</span></li>
                                    <?php
                                    if (isset($_SESSION['cart']) && count($_SESSION['cart']) >= 0) {
                                        foreach ($_SESSION['cart'] as $item) {
                                            $productTotal = $item['quantity'] * $item['price'];
                                            $total += $productTotal;
                                            ?>
                                            <li class="fw-normal">
                                                <?php echo htmlspecialchars($item['name']) . " x " . $item['quantity']; ?>
                                                <span>$<?php echo number_format($productTotal, 2); ?></span>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        echo "<li>Your cart is empty.</li>";
                                    }
                                    ?>
                                    <li class="fw-normal">Subtotal <span>$<?php echo number_format($total, 2); ?></span></li>
                                    <li class="total-price">Total <span>$<?php echo number_format($total, 2); ?></span></li>
                                </ul>
                                <div class="payment-method">
    <label for="payment-method">Select Payment Method<span>*</span></label>
    <select id="payment-method" class="form-control" name="payment_method" onchange="togglePaymentMethod()">
        <option value="">-- Select Payment Method --</option>
        <option value="online">Online Payment</option>
        <option value="cod">Cash on Delivery</option>
    </select>
    <span class="error-message" id="error-payment-method"></span>

</div>

                                <!-- Credit Card Form -->
<div id="credit-card-form" style="display: none; margin-top: 20px;">
    <div class="card-header">
        <strong class="card-title">Credit Card</strong>
    </div>
    <div class="card-body">
        <div id="pay-invoice">
            <div class="card-body">
                <div class="card-title">
                    <h3 class="text-center">Pay Invoice</h3>
                </div>
                <hr>
                <div class="payment-pic text-center">
                            <img src="img/payment-method.png" alt="">
                        </div>
                
                <div class="form-group">
                    <label for="cc-name" class="control-label mb-1">Name on Card</label>
                    <input id="cc-name" name="cc-name" type="text" class="form-control" aria-required="true" aria-invalid="false">
                    <span class="error-message" id="error-cc-name"></span>

                </div>
                <div class="form-group">
                    <label for="cc-number" class="control-label mb-1">Card Number</label>
                    <input id="cc-number" name="cc-number" type="text" class="form-control">
                    <span class="error-message" id="error-cc-number"></span>

                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="cc-exp" class="control-label mb-1">Expiration</label>
                            <input id="cc-exp" name="cc-exp" type="text" class="form-control" placeholder="MM / YY">
                            <span class="error-message" id="error-cc-exp"></span>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="cc-cvc" class="control-label mb-1">Security Code</label>
                            <input id="cc-cvc" name="cc-cvc" type="text" class="form-control" placeholder="CVC">
                            <span class="error-message" id="error-cc-cvc"></span>

                        </div>
                    </div>
                    
                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Place Order Button -->
                <div class="order-btn">
                                    <button type="button" id="placeOrderBtn" class="site-btn place-btn">Place
                                        Order</button>
                                </div>

                                <!-- Popup -->
                                <div id="thankYouPopup" class="popup">
                                    <div class="popup-content">
                                        <img src="images/tick-2.png" alt="Success" class="popup-tick">
                                        <h2>Thank You for Shopping!</h2>
                                    </div>
                                </div>
            </form>
        </div>
       
    </section>
    <!-- Checkout Section End -->

    <!-- Footer Section Begin -->
    <?php include('footer.php'); ?>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.zoom.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    function togglePaymentMethod() {
        const paymentMethod = document.getElementById('payment-method').value;
        const creditCardForm = document.getElementById('credit-card-form');

        if (paymentMethod === 'online') {
            creditCardForm.style.display = 'block';
        } else {
            creditCardForm.style.display = 'none';
        }
    }

    function validateForm(event) {
    event.preventDefault(); // Prevent form submission until validation passes

    let isValid = true;

    // Clear all previous error messages
    const errorSpans = document.querySelectorAll('.error-message');
    errorSpans.forEach((span) => {
        span.textContent = '';
    });

    // Required fields validation
    const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city'];
    requiredFields.forEach((field) => {
        const input = document.getElementsByName(field)[0];
        const errorElement = document.getElementById(`error-${field}`);
        if (!input.value.trim()) {
            isValid = false;
            errorElement.textContent = `${field.replace('_', ' ')} is required.`;
        }
    });

    // Email validation
    const email = document.getElementsByName('email')[0].value.trim();
    const emailError = document.getElementById('error-email');
    if (email && !/^\S+@\S+\.\S+$/.test(email)) {
        isValid = false;
        emailError.textContent = 'Invalid email address.';
    }

    // Phone validation
    const phone = document.getElementsByName('phone')[0].value.trim();
    const phoneError = document.getElementById('error-phone');
    if (phone && !/^\d{10}$/.test(phone)) {
        isValid = false;
        phoneError.textContent = 'Invalid phone number. Must be 10 digits.';
    }

    // Payment method validation
    const paymentMethod = document.getElementById('payment-method').value;
    const paymentMethodError = document.getElementById('error-payment-method');
    if (!paymentMethod) {
        isValid = false;
        paymentMethodError.textContent = 'Payment method must be selected.';
    }

    // Credit card validation if "online payment" is selected
    if (paymentMethod === 'online') {
        const cardName = document.getElementById('cc-name').value.trim();
        const cardNumber = document.getElementById('cc-number').value.trim();
        const cardExp = document.getElementById('cc-exp').value.trim();
        const cardCvc = document.getElementById('cc-cvc').value.trim();

        if (!cardName) {
            isValid = false;
            document.getElementById('error-cc-name').textContent = 'Name on card is required.';
        }
        if (!/^\d{16}$/.test(cardNumber)) {
            isValid = false;
            document.getElementById('error-cc-number').textContent = 'Invalid card number. Must be 16 digits.';
        }
        if (!/^\d{2}\/\d{2}$/.test(cardExp)) {
            isValid = false;
            document.getElementById('error-cc-exp').textContent = 'Invalid expiration date. Use MM/YY format.';
        }
        if (!/^\d{3}$/.test(cardCvc)) {
            isValid = false;
            document.getElementById('error-cc-cvc').textContent = 'Invalid CVC. Must be 3 digits.';
        }
    }

    // If form is valid, show the popup and submit the form
    if (isValid) {
        const popup = document.getElementById('thankYouPopup');
        popup.style.display = 'flex'; // Show popup

        // Submit the form after a slight delay
        setTimeout(() => {
            document.querySelector('.checkout-form').submit(); // Submit form programmatically
        }, 1000); // 2 seconds delay
    }
}
    // Attach event listener to the "Place Order" button
    document.getElementById('placeOrderBtn').addEventListener('click', validateForm);

    // Close the popup when clicking outside it
    document.getElementById('thankYouPopup').addEventListener('click', function (e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
</script>

</body>

</html>