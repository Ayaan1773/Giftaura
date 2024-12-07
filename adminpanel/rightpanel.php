<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

// Connect to the database
$con = mysqli_connect($servername, $username, $password, $db_name);
// Fetch all return requests
$notification_query = "SELECT * FROM orders WHERE return_status = 'Requested' ORDER BY id DESC";
$notification_result = $con->query($notification_query);

// Count return requests
$unread_count = $notification_result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        <button class="search-trigger"><i class="fa fa-search"></i></button>
                        <div class="form-inline">
                            <form class="search-form">
                                <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                                <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>

                        <div class="dropdown for-notification">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell"></i>
        <span class="count bg-danger"><?php echo $unread_count; ?></span>
    </button>
    <div class="dropdown-menu" aria-labelledby="notification">
        <p class="red">You have <?php echo $unread_count; ?> Notification(s)</p>
        <?php
        if ($unread_count > 0) {
            while ($notification = $notification_result->fetch_assoc()) {
                echo '<a class="dropdown-item media bg-flat-color-1" href="show_user_orders.php?order_id=' . $notification['id'] . '">';
                echo '<i class="fa fa-info"></i>';
                echo '<p>Return request for Order ID: ' . htmlspecialchars($notification['unique_order_id']) . '</p>';
                echo '</a>';
            }
        } else {
            echo '<p class="dropdown-item">No new notifications</p>';
        }
        ?>
    </div>
</div>

                        
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">

                            <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </div>

                    <div class="language-select dropdown" id="language-select">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"  id="language" aria-haspopup="true" aria-expanded="true">
                            <i class="flag-icon flag-icon-us"></i>
                        </a>
                    </div>

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

</body>
</html>