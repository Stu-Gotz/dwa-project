<?php include 'header.php'; 

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $visitor_email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $email_from = '<an email you have access to>';
    
    $email_subject = 'New Form Submission';
    
    $email_body = "User Name: $name.\n".
                    "User Email: $visitor_email.\n".
                    "Subject: $subject.\n".
                    "User Message: $message .\n";
    
    $to = 'customersupport@gmail.com';
    
    $headers = "From: $email_from \r\n";
    
    $headers .= "Reply-To: $visitor email \r\n";
    
    mail($to,$email_subject,$email_body,$headers);
    
    header("Location: ./index.php");
}
 ?>



<section class="contact-us">

    <div class="row">
        <div class="contact-col">
            <div>
                <i class="fa fa-home"></i>
                <span>
                    <h2>East Road, Cambridge</h2>
                    <p>Cambridgeshire, CB1 1PT, UK</p>
                </span>
            </div>
            <div>
                <i class="fa fa-phone"></i>
                <span>
                    <h2>+44 0786 164 2050</h2>
                    <p>Monday to Friday, 8AM-6PM</p>
                </span>
            </div>
            <div>
                <i class="fa fa-envelope"></i>
                <span>
                    <h2>example@example.com</h2>
                    <p>Email us your query</p>
                </span>
            </div>
        </div>
        <div class="contact-col">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <!-- TODO: LABELS -->
                <input type="text" name="name" placeholder="Enter your name" required>
                <input type="email" name="email" placeholder="Enter email address" required>
                <input type="subject" name="subject" placeholder="Enter message subject" required>
                <textarea row="8" name="message" placeholder ="Message" required></textarea>
                <input type="submit" class="hero-btn red-btn" value="Send Message" name="submit">
            </form>
        </div>
    </div>
</section>
<section class="location">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2445.20349554435!2d0.13226225087864682!3d52.203349367301215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8718baab59a11%3A0x482b8c6a0d818d1c!2sAnglia%20Ruskin%20University%20Cambridge%20Campus!5e0!3m2!1sen!2suk!4v1678227913063!5m2!1sen!2suk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>
</body>
</html>

<!------------Idea Creator Page------------>