<?php include 'header.php';

// THIS DOES NOt WORK

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $ind = $_POST['ind'];
    $country = $_POST['country'];
    $price = $_POST['price'];
    $risk = $_POST['risk'];
    $sql = "INSERT INTO `products` (prod_type, prod_style, company, country, val) 
    VALUES ($ind,$risk,$name,$country,$price)";

    if ($mysqli->query($sql)) {
        echo '<script type="text/javascript">alert("success");</script>';
    } else {
        echo '<script type="text/javascript">alert("failure");</script>';
    }
}

?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

    <div class=invest-form-area" id="invest-name">
        <label for="name">Investment Name</label>
        <input type="text" name="name" placeholder="" required> <br>
    </div>

    <div class=invest-form-area" id="invest-type">
        <label for="type">Asset Type</label>
        <input type="text" name="type" placeholder="" required> <br>
    </div>

    <div class=invest-form-area" id="invest-ind">
        <label for="ind">Industry</label>
        <input type="text" name="ind" placeholder="" required> <br>
    </div>

    <div class=invest-form-area" id="invest-country">
        <label for="country">Country</label>
        <input type="text" name="country" placeholder="" required> <br>
    </div>

    <div class=invest-form-area" id="invest-price">
        <label for="price">Closing Price</label>
        <input type="text" name="price" placeholder="" required> <br>
    </div>

    <div class=invest-form-area" id="invest-risk">
        <label for="risk">Risk Rating</label>
        <input type="text" name="risk" placeholder="" required> <br>
    </div>


    <input type="submit" name="submit" value="Submit">
</form>

<?php include 'footer.php'; ?>