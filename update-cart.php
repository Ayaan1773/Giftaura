<?php
include('connection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($data['id']) || empty($data['quantity']) && $data['quantity'] !== 0) {
    $response['message'] = 'Product ID and quantity are required.';
    echo json_encode($response);
    exit;
}

$productId = intval($data['id']);
$quantity = intval($data['quantity']);

if (!isset($_SESSION['email'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$userEmail = $_SESSION['email'];

// Fetch product details from the `cards` table
$product_query = $con->prepare("SELECT unique_product_id, name, price, stock FROM cards WHERE id = ?");
$product_query->bind_param("i", $productId);
$product_query->execute();
$product_query->bind_result($uniqueProductId, $productName, $productPrice, $productStock);
$product_query->fetch();
$product_query->close();

if (!$productName) {
    $response['message'] = 'Product not found.';
    echo json_encode($response);
    exit;
}

if ($quantity > $productStock) {
    $response['message'] = "Requested quantity exceeds available stock. Available stock: $productStock.";
    $response['availableStock'] = $productStock;
    echo json_encode($response);
    exit;
}

if ($quantity == 0) {
    // Remove item from the cart when quantity is 0
    $stmt = $con->prepare("DELETE FROM cart WHERE product_id = ? AND user_email = ?");
    $stmt->bind_param("is", $productId, $userEmail);
    $stmt->execute();
    $stmt->close();

    unset($_SESSION['cart'][$productId]); // Remove product from session

    $cart_total_query = $con->prepare("SELECT SUM(price * quantity) AS total FROM cart WHERE user_email = ?");
    $cart_total_query->bind_param("s", $userEmail);
    $cart_total_query->execute();
    $cart_total_query->bind_result($cartTotal);
    $cart_total_query->fetch();
    $cart_total_query->close();

    $response['success'] = true;
    $response['message'] = 'Item removed from cart successfully.';
    $response['total'] = $cartTotal ?? 0;
    echo json_encode($response);
    exit;
}

// Update or add the item to the cart if quantity > 0
$stmt = $con->prepare("INSERT INTO cart (user_email, product_id, unique_product_id, name, price, quantity, added_at) 
    VALUES (?, ?, ?, ?, ?, ?, NOW()) 
    ON DUPLICATE KEY UPDATE quantity = ?, added_at = NOW()");
$stmt->bind_param("sissdii", $userEmail, $productId, $uniqueProductId, $productName, $productPrice, $quantity, $quantity);

if ($stmt->execute()) {
    $_SESSION['cart'][$productId] = [
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice,
        'quantity' => $quantity,
        'image_path' => $_SESSION['cart'][$productId]['image_path'] ?? '',
    ];

    $cart_total_query = $con->prepare("SELECT SUM(price * quantity) AS total FROM cart WHERE user_email = ?");
    $cart_total_query->bind_param("s", $userEmail);
    $cart_total_query->execute();
    $cart_total_query->bind_result($cartTotal);
    $cart_total_query->fetch();
    $cart_total_query->close();

    $response['success'] = true;
    $response['message'] = 'Cart updated successfully.';
    $response['total'] = $cartTotal ?? 0;
    $response['itemTotal'] = $quantity * $productPrice;
} else {
    $response['message'] = 'Error updating cart: ' . $stmt->error;
}

$stmt->close();

echo json_encode($response);
?>
