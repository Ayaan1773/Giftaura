<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);

// Check if the ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data
    $query = $con->prepare("SELECT * FROM cards WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $card = $result->fetch_assoc();
    $query->close();

    if (!$card) {
        echo "Product not found!";
        exit;
    }
}

// Update the record
if (isset($_POST['update_card'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $img = basename($_FILES['img']['name']);
    $final = "../card_images/" . $img;

    // Prepare the SQL query
    if (!empty($img)) {
        $stmt = $con->prepare("UPDATE cards SET name=?, description=?, price=?, stock=?, category=?, image_path=? WHERE id=?");
        $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $category, $final, $id);
        move_uploaded_file($_FILES['img']['tmp_name'], $final);
    } else {
        $stmt = $con->prepare("UPDATE cards SET name=?, description=?, price=?, stock=?, category=? WHERE id=?");
        $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $category, $id);
    }

    if ($stmt->execute()) {
        header("Location: show_cards.php");
    } else {
        echo "Update failed: " . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
/* Container Styling */
.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: auto;
}

/* Form Styling */
form {
    width: 100%;
}

form .form-label {
    font-weight: bold;
    color: #333333;
}

form .form-control {
    border-radius: 5px;
    border: 1px solid #ced4da;
    box-shadow: none;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

form .form-control:focus {
    border-color: #007bff;
    box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.5);
}

/* Select Styling */
form select.form-control {
    background-color: #ffffff;
    border-radius: 5px;
}

/* Image Styling */
form img {
    display: inline-block;
    border-radius: 5px;
    margin-top: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

/* Button Styling */
form .btn {
    font-size: 16px;
    padding: 10px 15px;
    border-radius: 5px;
    transition: all 0.2s ease-in-out;
}

form .btn-primary {
    background-color: #007bff;
    border: none;
}

form .btn-primary:hover {
    background-color: #0056b3;
}

form .btn-secondary {
    background-color: #6c757d;
    border: none;
}

form .btn-secondary:hover {
    background-color: #5a6268;
}

/* Small Text Styling */
form small {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    form .btn {
        width: 100%;
        margin-bottom: 10px;
    }

    form img {
        display: block;
        margin: 10px auto;
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

<div class="container my-5">
    <h2>Edit Product Card</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $card['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" required><?php echo $card['description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" class="form-control" name="price" value="<?php echo $card['price']; ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" class="form-control" name="stock" value="<?php echo $card['stock']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Product Category</label>
            <select class="form-control" id="name" name="category" id="category"  value="<?php echo $card['category']; ?>" required>
        <option value="">Select a category</option>
        <option value="Bags">Bags</option>
        <option value="Perfumes">Perfumes</option>
        <option value="Watches">Watches</option>
        <option value="Stationery">Stationery</option>
        <option value="Shoes">Shoes</option>
        <option value="Wallet">Wallet</option>

    </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" class="form-control" name="img">
            <small>Current Image: <img src="<?php echo $card['image_path']; ?>" width="50" height="50"></small>
        </div>
        <button type="submit" name="update_card" class="btn btn-primary">Update</button>
        <a href="show_cards.php" class="btn btn-secondary">Back</a>
    </form>
</div>
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
