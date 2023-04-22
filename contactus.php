<?php include 'header.php';

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_EMAIL);
    if($name){
        $name = htmlspecialchars($_POST['$name']);
    }
    $visitor_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if($visitor_email){
        $visitor_email = htmlspecialchars($_POST['email']);
    }
    ;
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $email_from = '<an email you have access to>';

    $email_subject = 'New Form Submission';

    $email_body = "User Name: $name.\n" .
        "User Email: $visitor_email.\n" .
        "Subject: $subject.\n" .
        "User Message: $message .\n";

    $to = 'admin@investing.com';

    $headers = "From: $email_from \r\n";

    $headers .= "Reply-To: $visitor email \r\n";

    mail($to, $email_subject, $email_body, $headers);

    header("Location: ./index.php");
}
?>



<div class="contact-us">
    <div class="row">
        <div class="contact-col" id="contact-info">
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
        <div class="contact-col" id="contact-form">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <!-- TODO: LABELS -->
                <div>
                    <label for="name">Name: </label>
                    <input type="text" name="name" placeholder="Enter your name" required>
                </div>
                <div>
                    <label for="email">Email: </label>
                    <input type="email" name="email" placeholder="Enter email address" required>
                </div>
                <div>
                    <label for="subject">Subject: </label>
                    <input type="subject" name="subject" placeholder="Enter message subject" required>
                </div>
                <div class="msg-body">
                    <label for="message">How can we help?</label>
                    <textarea row="8" name="message" placeholder="Message" required></textarea>
                </div>

                <button type="submit" class="btn btn-submit" name="submit">Send Message</button>
            </form>
        </div>
    </div>
    <div class="location">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2445.20349554435!2d0.13226225087864682!3d52.203349367301215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8718baab59a11%3A0x482b8c6a0d818d1c!2sAnglia%20Ruskin%20University%20Cambridge%20Campus!5e0!3m2!1sen!2suk!4v1678227913063!5m2!1sen!2suk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

</body>

</html>

<?php include './footer.php'; ?>