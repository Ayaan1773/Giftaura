<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['btn'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // Remove or set SMTPDebug to 0 to disable output on the screen
        $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'abdullahmusharraf576@gmail.com';                     //SMTP username
        $mail->Password = 'gxel vxyv zynv ucez';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('abdullahmusharraf576@gmail.com', 'Abdullah');
        $mail->addAddress($email);               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
       
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('Welcome.jpg');    //Optional name

        //Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = 'Thank You for Reaching Out – We’ll Get Back to You Soon!';
        $mail->Body = 'Dear ' . $name . '<br><br>' .
        
        'Thank you for reaching out to us! We’ve received your comment, and one of our staff members will get in touch with you shortly to assist with your query.<br><br>' .
        
        'If your question requires immediate attention, feel free to reply to this email, and we will prioritize your request.<br><br>' .
        
        'We appreciate your patience and look forward to serving you!<br><br>' .
        
        'Best regards,<br>' .
        'The Giftaura Team';
        
        


        $mail->send();
        
    } catch (Exception $email) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve email input from the form
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Email is valid, you can save it to the database or send an email
        // Display success message
        echo "<script>alert('Thank you for contacting us!');</script>";
    } 
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
                        <span>Contact</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Map Section Begin -->
    <div class="map spad">
        <div class="container">
            <div class="map-inner">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d48158.305462977965!2d-74.13283844036356!3d41.02757295168286!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2e440473470d7%3A0xcaf503ca2ee57958!2sSaddle%20River%2C%20NJ%2007458%2C%20USA!5e0!3m2!1sen!2sbd!4v1575917275626!5m2!1sen!2sbd"
                    height="610" style="border:0" allowfullscreen="">
                </iframe>
                <div class="icon">
                    <i class="fa fa-map-marker"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Map Section Begin -->

    <!-- Contact Section Begin -->
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="contact-title">
                        <h4>Contacts Us</h4>
                        <p>Contrary to popular belief, choosing the perfect gift or stationery isn't
                             always an easy task. It requires a blend of thoughtfulness, creativity, 
                             and an understanding of the recipient's style. At GiftAura, we offer a 
                             curated selection of unique gifts and premium stationery designed 
                             to bring joy and inspiration to every occasion. Whether you're looking
                              for a personalized gift or elegant stationery, we have something special just for you</p>
                    </div>
                    <div class="contact-widget">
                        <div class="cw-item">
                            <div class="ci-icon">
                                <i class="ti-location-pin"></i>
                            </div>
                            <div class="ci-text">
                                <span>Address:</span>
                                <p>Block L North Nazimabad</p>
                            </div>
                        </div>
                        <div class="cw-item">
                            <div class="ci-icon">
                                <i class="ti-mobile"></i>
                            </div>
                            <div class="ci-text">
                                <span>Phone:</span>
                                <p>+92 33.122.812</p>
                            </div>
                        </div>
                        <div class="cw-item">
                            <div class="ci-icon">
                                <i class="ti-email"></i>
                            </div>
                            <div class="ci-text">
                                <span>Email:</span>
                                <p>giftaura@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="contact-form">
                        <div class="leave-comment">
                            <h4>Leave A Comment</h4>
                            <p>Our team will get in touch with you soon to help
                                 you find the perfect gift or answer any 
                                 questions you have about our stationery collections</p>
                            <form action="#" class="comment-form" method ="post">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="text"name ="username"  placeholder="YourName">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text"name ="email"  placeholder="YourEmail">
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea placeholder="Your message" name ="message"></textarea>
                                        <button type="submit"name = "btn" class="site-btn">Send message</button>
                                         

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

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
</body>

</html>