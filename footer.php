<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['btn'])) {
    $email = $_POST['email'];

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
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Welcome to GiftAura! Stay Updated with Our Latest Stationery and Gift Offers';
        $mail->Body = '<b>Hello,

Thank you for subscribing to GiftAura! We’re excited to keep you in the loop with the latest arrivals, special offers, and exclusive updates on all things stationery and gifts.

Expect to see new collections, seasonal trends, and limited-time deals—all crafted to bring a little extra joy to your shopping experience. Whether you’re looking for the perfect gift or the latest in unique stationery, we’ve got something special for you.

If you have any questions or need assistance, feel free to reach out to our support team at giftaura@gmail.com

Stay tuned for all the exciting updates headed your way!

Warm regards,
Ayaan
GiftAura</b>';

        $mail->send();
        
    } catch (Exception $email) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .footer-logo{
            margin-top:-12px !important;
        }
    </style>
</head>
<body>
<footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-left">
                        <div class="footer-logo">
                            <a href="index.php"><img src="images/footerlogo.png" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: Block L North Nazimabad</li>
                            <li>Phone: +92 33.122.812</li>
                            <li>Email: giftaura@gmail.com</li>
                        </ul>
                        <div class="footer-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <div class="footer-widget">
                        <h5>Information</h5>
                        <ul>
                            <li><a href="faq.php">FAQ</a></li>
                            <li><a href="check-out.php">Checkout</a></li>
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="#">Services</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-widget">
                        <h5>My Account</h5>
                        <ul>
                            <li><a href="account.php">My Account</a></li>
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="shopping-cart.php">Shopping Cart</a></li>
                            <li><a href="shop.php">Shop</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="newslatter-item">
                        <h5>Join Our Newsletter Now</h5>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form method="post" class="subscribe-form">
                            <input type="text" name="email" placeholder="Enter Your Mail">
                            <button type="submit" name="btn">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-reserved">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-text">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </div>
                        <div class="payment-pic">
                            <img src="img/payment-method.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>