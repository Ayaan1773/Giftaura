<?php
include('connection.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (isset($_SESSION['email'])) {
    $stmt = $con->prepare("SELECT product_id, quantity FROM cart WHERE user_email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $_SESSION['cart'][$row['product_id']]['quantity'] = $row['quantity'];
    }
    $stmt->close();
}
if (empty($_SESSION['cart'])) {
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
        .proceed-btn a {
    color: white !important;
    text-decoration: none !important; /* Optional: To remove the underline */
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
                    <div class="breadcrumb-text product-more">
                        <a href="home.php"><i class="fa fa-home"></i> Home</a>
                        <a href="shop.php">Shop</a>
                        <span>Shopping Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th class="p-name">Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th><i class="ti-close"></i></th>
                        </tr>
                    </thead>
                   <tbody>
    <?php
    $total = 0;
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $item) {
            // Ensure necessary keys exist
            if (
                !isset($item['image_path'], $item['name'], $item['price'], $item['quantity']) || 
                empty($item['image_path']) || 
                !file_exists('card_images/' . $item['image_path'])
            ) {
                continue; // Skip this item if data is missing or invalid
            }

            $imagePath = 'card_images/' . $item['image_path'];
            $itemTotal = $item['price'] * $item['quantity'];
            $total += $itemTotal;
    ?>
            <tr>
                <td class="cart-pic">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                </td>
                <td class="cart-title">
                    <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                </td>
                <td class="p-price">
                    $<?php echo number_format($item['price'], 2); ?>
                </td>
                <td class="qua-col">
                    <div class="quantity">
                        <div class="pro-qty">
                            <span class="dec qtybtn">-</span>
                            <input type="text"
                                   value="<?php echo $item['quantity']; ?>"
                                   data-id="<?php echo $item['id']; ?>"
                                   class="quantity-input" readonly>
                            <span class="inc qtybtn">+</span>
                        </div>
                    </div>
                </td>
                <td class="total-price">
                    $<?php echo number_format($itemTotal, 2); ?>
                </td>
                <td class="close-td">
                    <a href="#" class="remove-btn" data-id="<?php echo $item['id']; ?>">
                        <i class="ti-close"></i>
                    </a>
                </td>
            </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Your cart is empty.</td></tr>";
    }
    ?>
</tbody>
                </table>
            </div>
            <div class="proceed-checkout">
    <ul>
        <li class="subtotal">Subtotal <span>$<?php echo number_format($total, 2); ?></span></li>
        <li class="cart-total">Total <span>$<?php echo number_format($total, 2); ?></span></li>
    </ul>
    <?php if (empty($_SESSION['cart'])): ?>
        <!-- If the cart is empty, show the "Go to Shop" button -->
        <a href="shop.php" class="proceed-btn">GO TO SHOP</a>
    <?php else: ?>
        <!-- If the cart is not empty, show the "Proceed to Checkout" button -->
        <form id="checkout-form" action="" method="POST">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <button type="submit" class="proceed-btn"><a href="check-out.php">PROCEED TO CHECKOUT</a></button>
        </form>
    <?php endif; ?>
</div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->

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
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery.zoom.min.js"></script>
    <script src="js/jquery.dd.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
     <script>
   document.addEventListener("DOMContentLoaded", function () {
    function updateCart(productId, quantity) {
        fetch("update-cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: productId, quantity: quantity })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        // Update cart details
        document.querySelector(".cart-total span").innerText = "$" + data.total.toFixed(2);
        document.querySelector(".subtotal span").innerText = "$" + data.total.toFixed(2);
        document.getElementById("cart-quantity").innerText = data.quantity;

    } else {
        alert(data.message); // Notify user about stock limit
        if (data.availableStock) {
            quantityInput.value = data.availableStock; // Reset quantity to available stock
        }
    }
})
.catch(error => console.error('Error:', error));
    }

    // Add event listeners to increment and decrement buttons
    document.querySelectorAll(".qtybtn").forEach(button => {
        button.addEventListener("click", function () {
            const isIncrement = this.classList.contains("inc");
            const quantityInput = this.closest(".pro-qty").querySelector(".quantity-input");
            const productId = quantityInput.getAttribute("data-id");
            let currentQuantity = parseInt(quantityInput.value);

            // Increment or decrement quantity based on button clicked
            if (isIncrement) {
                currentQuantity++;
            } else if (currentQuantity > 1) {
                currentQuantity--;
            }

            // Update input field value
            quantityInput.value = currentQuantity;

            // Send AJAX request to update quantity on the server
            updateCart(productId, currentQuantity);

            // Update item total on the client side
            const price = parseFloat(this.closest("tr").querySelector(".p-price").innerText.replace('$', ''));
            const totalPrice = currentQuantity * price;
            this.closest("tr").querySelector(".total-price").innerText = "$" + totalPrice.toFixed(2);

            // Update subtotal and total on the client side
            let subtotal = 0;
            document.querySelectorAll(".total-price").forEach(function(element) {
                subtotal += parseFloat(element.innerText.replace('$', ''));
            });
            document.querySelector(".subtotal span").innerText = "$" + subtotal.toFixed(2);
            document.querySelector(".cart-total span").innerText = "$" + subtotal.toFixed(2);
        });
    });

    // Add event listeners to remove buttons
    document.querySelectorAll(".remove-btn").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const productId = this.getAttribute("data-id");

            // Send AJAX request to remove item from cart
            updateCart(productId, 0);

            // Update subtotal and total on the client side after removing the item
            const row = this.closest("tr");
            const totalPrice = parseFloat(row.querySelector(".total-price").innerText.replace('$', ''));
            row.remove();

            let subtotal = 0;
            document.querySelectorAll(".total-price").forEach(function(element) {
                subtotal += parseFloat(element.innerText.replace('$', ''));
            });
            document.querySelector(".subtotal span").innerText = "$" + subtotal.toFixed(2);
            document.querySelector(".cart-total span").innerText = "$" + subtotal.toFixed(2);
        });
    });
});




</script>
</body>

</html>