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
    <!-- Lightbox CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <style>
        .bi-pic{
            width: 290px;
        }
        .bi-text h4{
            font-size:25px;
        }
        /* Reduce margin or padding of the blog sidebar */
.blog-sidebar {
    margin-left: 0; /* Remove margin if any */
    padding-left: 0; /* Remove any left padding */
    max-width:450px;
}

/* Alternatively, you can set negative margin to pull it left */
.blog-sidebar {
    margin-left: 20px;  /* This pulls the sidebar slightly to the left */
}

.blog-item{
padding-left:80px;
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
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1">
                    <div class="blog-sidebar">
                        <div class="recent-post">
                            <h4>Recent Posts</h4>
                            <div class="recent-blog">
                                <!-- Recent Post 1 -->
                                <a href="images\11perume.png" data-lightbox="recent-gallery" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="images\11perume.png" alt="">
                                    </div>
                                    <div class="rb-text">
                                        <h6>Exclusive And Luxury Perfume Collection</h6>
                                        <p>Perfume <span>- Nov 25, 2024</span></p>
                                    </div>
                                </a>
                                <!-- Recent Post 2 -->
                                <a href="images\handbag.jpg" data-lightbox="recent-gallery" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="images\handbag.jpg" alt="">
                                    </div>
                                    <div class="rb-text">
                                        <h6>Exclusive And Luxury Handbag Collection</h6>
                                        <p>Handbag <span>- Mar 19, 2024</span></p>
                                    </div>
                                </a>
                                <!-- Recent Post 3 -->
                                <a href="images/Advance watches.jpg" data-lightbox="recent-gallery" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="images/Advance watches.jpg" alt="">
                                    </div>
                                    <div class="rb-text">
                                        <h6>Exclusive And Luxury Watches Collection</h6>
                                        <p>Accessories <span>- May 19, 2024</span></p>
                                    </div>
                                </a>
                                <!-- Recent Post 4 -->
                                <a href="images/blogpenandstationary.jpg" data-lightbox="recent-gallery" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="images/blogpenandstationary.jpg" alt="">
                                    </div>
                                    <div class="rb-text">
                                        <h6>Exclusive And Luxury Stationary Collection</h6>
                                        <p>Stationery <span>- Dec 2, 2023</span></p>
                                    </div>
                                </a>
                                <!-- Recent Post 5 -->
                                <a href="images/wallet22.png" data-lightbox="recent-gallery" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="images/wallet22.png" alt="">
                                    </div>
                                    <div class="rb-text">
                                        <h6>Exclusive And Luxury Wallets Collection</h6>
                                        <p>Accessories <span>- Feb 19, 2024</span></p>
                                    </div>
                                </a>
                                <!-- Recent Post 6 -->
                                <a href="images\premium shoes.jpg" data-lightbox="recent-gallery" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="images\premium shoes.jpg" alt="">
                                    </div>
                                    <div class="rb-text">
                                        <h6>Exclusive And Luxury Shoes Collection</h6>
                                        <p>Accessories <span>- Oct 29, 2024</span></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="row">
                        <!-- Blog Post 1 -->
                        <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="images\11perume.png" data-lightbox="blog-gallery">
                                        <img src="images\11perume.png" alt="" height="260px">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="blog-details.php">
                                        <h4>Exclusive And Luxury Perfume Collection</h4>
                                    </a>
                                    <p>Perfume<span>-Nov 25, 2024</span></p>
                                </div>
                            </div>
                        </div>
                        <!-- Blog Post 2 -->
                        <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="images\handbag.jpg" data-lightbox="blog-gallery">
                                        <img src="images\handbag.jpg" alt="" height="260px">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="blog-details.php">
                                        <h4>Exclusive And Luxury Handbag Collection</h4>
                                    </a>
                                    <p>Handbags <span>- Mar 19, 2024</span></p>
                                </div>
                            </div>
                        </div>
                        <!-- Blog Post 3 -->
                        <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="images\Advance watches.jpg" data-lightbox="blog-gallery">
                                        <img src="images\Advance watches.jpg" alt="" height="260px">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="blog-details.php">
                                        <h4>Exclusive And Luxury <br> Watches Collection</h4>
                                    </a>
                                    <p>Watches<span>- May 19, 2024</span></p>
                                </div>
                            </div>
                        </div>
                        <!-- Blog Post 4 -->
                        <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="images/blogpenandstationary.jpg" data-lightbox="blog-gallery">
                                        <img src="images/blogpenandstationary.jpg" alt="" height="260px">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="blog-details.php">
                                        <h4>Exclusive And Luxury Stationary Collection</h4>
                                    </a>
                                    <p>Stationery <span>- Dec 2, 2023</span></p>
                                </div>
                            </div>
                        </div>
                         <!-- Blog Post 5 -->
                         <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="images/wallet22.png" data-lightbox="blog-gallery">
                                        <img src="images/wallet22.png" alt="" height="260px">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="blog-details.php">
                                        <h4>Exclusive And Luxury Wallet Collection</h4>
                                    </a>
                                    <p>Watches<span>-  Feb 19, 2024</span></p>
                                </div>
                            </div>
                        </div>
                         <!-- Blog Post 6 -->
                         <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <div class="bi-pic">
                                    <a href="images\premium shoes.jpg" data-lightbox="blog-gallery">
                                        <img src="images\premium shoes.jpg" alt="" height="260px">
                                    </a>
                                </div>
                                <div class="bi-text">
                                    <a href="blog-details.php" >
                                        <h4>Exclusive And Luxury Shoes Collection</h4>
                                    </a>
                                    <p>Watches<span>-  Oct 29, 2024</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

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
    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
</body>

</html>