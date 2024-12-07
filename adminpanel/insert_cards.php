<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['add_card'])) {
    // Get form data
    $code = $_POST['product_code'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category=$_POST['category'];
    $folder = "../card_images/";
    $img = basename($_FILES['img']['name']);
    $final = $folder . $img;

    // Check for duplicate product name or image
    $stmt = $con->prepare("SELECT * FROM cards WHERE name = ? OR image_path = ?");
    $stmt->bind_param("ss", $name, $final);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "A product with the same name or image already exists!";
        exit;
    }

    // Generate unique product ID
    $uniqueProductID = generateUniqueProductID($con, $code);

    // Validate image
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['img']['type'], $allowedTypes)) {
        echo "Invalid image type!";
        exit;
    }

    // Insert product data using prepared statement
    $stmt = $con->prepare("INSERT INTO cards (product_code, unique_product_id, name, description, price, stock, category, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdiss", $code, $uniqueProductID, $name, $description, $price, $stock,$category, $final,);

    if ($stmt->execute()) {
        // Move uploaded image to the target folder
        move_uploaded_file($_FILES['img']['tmp_name'], $final);
    } else {
        echo "Register Fail: " . $stmt->error;
    }
    $stmt->close();
}

// Function to generate unique product ID
function generateUniqueProductID($con, $productCode) {
    // Retrieve the last unique product ID with the given product code
    $stmt = $con->prepare("SELECT unique_product_id FROM cards WHERE product_code = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $productCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $lastProduct = $result->fetch_assoc();

    if ($lastProduct) {
        // Extract and increment the last product number
        $lastNumber = intval(substr($lastProduct['unique_product_id'], 2));  // get the numeric part
        $newNumber = $lastNumber + 1;
    } else {
        // Start from 1 if no products exist with this product code
        $newNumber = 1;
    }

    // Format the new number to be exactly 5 digits
    $formattedNumber = str_pad($newNumber, 5, '0', STR_PAD_LEFT);

    // Combine the product code with the formatted number to get the unique product ID
    return $productCode . $formattedNumber;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GiftAura Admin</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">


    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <style>
        /* Card Shadow */
        .card {
            border-radius: 10px;
            border: none;
            background-color: #f8f9fa;
        }

        /* Form Labels */
        .form-label {
            font-weight: 500;
            color: #333;
        }

        /* Input Fields */
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px;
        }

        select.form-control {
    width: 100%;
    height: 50px;
}

        /* Button Styling */
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Image Preview */
        #preview-container {
            margin-top: 10px;
            display: none;
        }

        #preview-container img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        /* Responsive Layout */
        @media (max-width: 576px) {
            .form-control {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 0.9rem;
            }
        }
    </style>

</head>
<body>
     <!-- Left Panel -->
     <?php
    include('leftpanel.php');
    ?>
    <!-- Left Panel -->

    <!-- Right Panel -->
    <?php
    include('rightpanel.php');
    ?>
    <!-- Right Panel -->

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Add New Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <!-- Product Code -->
                            <div class="mb-3">
                                <label for="product_code" class="form-label">Product Code</label>
                                <input type="text" class="form-control" name="product_code" maxlength="2" required id="product_code" placeholder="e.g., BG">
                            </div>
                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Product Category</label>
                                <select class="form-control" name="category" id="category" required>
                                    <option value="">Select a category</option>
                                    <option value="Bags">Bags</option>
                                    <option value="Perfumes">Perfumes</option>
                                    <option value="Watches">Watches</option>
                                    <option value="Stationery">Stationery</option>
                                    <option value="Shoes">Shoes</option>
                                    <option value="Wallet">Wallet</option>
                                </select>
                            </div>
                            <!-- Product Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name" required>
                            </div>
                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter product description..."></textarea>
                            </div>
                            <!-- Price and Stock -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" class="form-control" name="price" step="0.01" id="price" placeholder="e.g., 299.99" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input type="number" class="form-control" name="stock" id="stock" placeholder="Enter stock quantity" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="img" class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="img" id="img" accept="image/*" required onchange="previewImage(event)">
                                <div id="preview-container">
                                    <img id="preview" alt="Image Preview">
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" name="add_card" class="btn btn-primary">
                                    <i class="fa fa-plus-circle"></i> Add Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Image Preview Script
        function previewImage(event) {
            const previewContainer = document.getElementById('preview-container');
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            previewContainer.style.display = 'block';
        }
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>

</body>
</html>
