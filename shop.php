<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
include('connection.php');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch distinct categories dynamically
$categoryQuery = "SELECT DISTINCT category FROM cards WHERE category IS NOT NULL";
$categoryResult = mysqli_query($con, $categoryQuery);
if (!$categoryResult) {
    die("Category query failed: " . mysqli_error($con));
}

$categories = [];
while ($category = mysqli_fetch_assoc($categoryResult)) {
    $categories[] = $category['category'];
}

// Fetch products from the database
$query = "SELECT * FROM cards";
$result = mysqli_query($con, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

// Handle add to cart action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    $productQty = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 0;

    $query = "SELECT * FROM cards WHERE id = $productId";
    $productResult = mysqli_query($con, $query);
    $product = mysqli_fetch_assoc($productResult);

    if ($product) {
        $cartItem = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image_path' => $product['image_path'],
            'quantity' => $productQty,
        ];

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $productQty;
        } else {
            $_SESSION['cart'][$productId] = $cartItem;
        }

        header('Location: shopping-cart.php');
        exit();
    }
}

// Initialize filters
$filterCategory = isset($_GET['category']) && is_array($_GET['category']) ? $_GET['category'] : [];
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$filterPrice = isset($_GET['price']) ? $_GET['price'] : '';


// Base query
$filterQuery = "SELECT * FROM cards";
$conditions = [];

// Add category filter
if (!empty($filterCategory)) {
    $categoryFilters = array_map(function($category) use ($con) {
        return "'" . mysqli_real_escape_string($con, $category) . "'";
    }, $filterCategory);
    $conditions[] = "category IN (" . implode(',', $categoryFilters) . ")";
}

// Add search filter if applicable
if (!empty($search)) {
    $conditions[] = "name LIKE '%$search%'";
}
// Add price filter
if (!empty($filterPrice)) {
    $priceRange = explode('-', $filterPrice);
    if (count($priceRange) === 2) {
        $minPrice = (int)$priceRange[0];
        $maxPrice = (int)$priceRange[1];
        $conditions[] = "price BETWEEN $minPrice AND $maxPrice";
    }
}
// Append conditions to the query
if (!empty($conditions)) {
    $filterQuery .= " WHERE " . implode(' AND ', $conditions);
}

// Execute the filtered query
$result = mysqli_query($con, $filterQuery);
if (!$result) {
    die("Query failed: " . mysqli_error($con));
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
        /* Filter Sidebar Styles */
        .filter-sidebar {
    padding: 25px;
    border-radius: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

/* Title for filter section */
.filter-sidebar h5 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

/* Checkbox Styles */
.form-check {
    margin-bottom: 10px;
}

/* Label Styles */
.form-check-label {
    font-size: 16px;
    color: #555;
    font-weight: 400;
    margin-left: 10px;
}

/* Button styling */
.primary-btn {
    background-color: #e7ab3c; /* Original button color */
    color: #333; /* Dark text for contrast */
    font-size: 16px;
    font-weight: 600;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align:center;
}

.primary-btn:hover {
    background-color: #e0e0e0; /* Slightly darker shade on hover */
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
                        <span>Shop</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->
      <!-- Filter Section Begin -->
<section class="product-shop spad">
    <div class="container">
        <div class="row">
            <!-- Left Sidebar for Filter -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                <form action="shop.php" method="GET">
                <!-- Filter by Product Name -->
                        <h5>Filter by Categories</h5>
                        <?php foreach ($categories as $category) { ?>
        <div class="form-check">
            <input type="checkbox" name="category[]" 
                   value="<?php echo htmlspecialchars($category); ?>" 
                   class="form-check-input" 
                   id="filter-<?php echo htmlspecialchars($category); ?>"
                   <?php if (isset($_GET['category']) && in_array($category, (array) $_GET['category'])) echo 'checked'; ?>>
            <label class="form-check-label" for="filter-<?php echo htmlspecialchars($category); ?>">
                <?php echo htmlspecialchars($category); ?>
            </label>
        </div>
    <?php } ?>

                        <!-- Filter by Product Price -->
                        <h5>Filter by Price</h5>
                        <div class="form-check">
                            <input type="radio" name="price" value="0-50" class="form-check-input" id="price-0-50" <?php if ($filterPrice == '0-50') echo 'checked'; ?>>
                            <label class="form-check-label" for="price-0-50">$0 - $50</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="price" value="51-100" class="form-check-input" id="price-51-100" <?php if ($filterPrice == '51-100') echo 'checked'; ?>>
                            <label class="form-check-label" for="price-51-100">$51 - $100</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="price" value="101-200" class="form-check-input" id="price-101-200" <?php if ($filterPrice == '101-200') echo 'checked'; ?>>
                            <label class="form-check-label" for="price-101-200">$101 - $200</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="price" value="201-500" class="form-check-input" id="price-201-500" <?php if ($filterPrice == '201-500') echo 'checked'; ?>>
                            <label class="form-check-label" for="price-201-500">$201 - $500</label>
                        </div>

                        <button type="submit" class="primary-btn mt-3">Filter</button>
                    </form>
                    <!-- Clear Filters Button -->
                    <a href="shop.php" class="primary-btn mt-3">Clear Filters</a>
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- Product Display Area -->
            <div class="col-lg-9">
                <div class="product-list">
                    <div class="row">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($result)) { ?>
    <div class="col-lg-4 col-sm-6">
        <div class="product-item">
            <div class="pi-pic">
                <img src="card_images/<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='card_images/default.jpg';">
                <?php if ($product['stock'] > 0) { ?>
    <div class="sale pp-sale">In Stock</div>
    <ul>
        <?php if (isset($_SESSION['email'])) { ?>
            <li class="w-icon active">
                <a href="shop.php?action=add&id=<?php echo $product['id']; ?>">
                    <i class="icon_bag_alt"></i> Add to Cart
                </a>
            </li>
        <?php } else { ?>
            <li class="w-icon active">
                <a href="login.php"><i class="icon_bag_alt"></i> Login to Add</a>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <div class="sale pp-sale" style="background-color: #ff0000;">Sold Out</div>
    <ul>
        <li class="w-icon inactive" style="cursor: not-allowed; color: #999;">
            <i class="icon_close_alt2"></i> Sold Out
        </li>
    </ul>
<?php } ?>

            </div>
            <div class="pi-text">
                <a href="#">
                    <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                </a>
                <div class="product-price">
                    $<?php echo number_format($product['price'], 2); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php else: ?>
    <p style="text-align: center; font-size: 18px; color: #999;">No products found matching "<?php echo htmlspecialchars($search); ?>"</p>
<?php endif; ?>

                    </div>
                </div>
            </div>
            <!-- Product Display Area End -->
        </div>
    </div>
</section>
<!-- Product Shop Section End -->
    <!-- Partner Logo Section Begin -->
    <div class="partner-logo">
        <div class="container">
            <div class="logo-carousel owl-carousel">
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="img/logo-carousel/logo-1.png" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="img/logo-carousel/logo-2.png" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="img/logo-carousel/logo-3.png" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="img/logo-carousel/logo-4.png" alt="">
                    </div>
                </div>
                <div class="logo-item">
                    <div class="tablecell-inner">
                        <img src="img/logo-carousel/logo-5.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Partner Logo Section End -->

    <!-- Footer Section Begin -->
    <?php
    include('footer.php');
    
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

</body>
</html>
<?php mysqli_close($con); ?>
