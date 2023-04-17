<?php include 'header.php';

//keep pages private from non-registered users
if(!isset($_SESSION['userid'])){
    header('Location: ./login.php');
  }

if (isset($_POST['submit'])) {
    $abbr = htmlspecialchars($_POST['abbr']);
    $name = htmlspecialchars($_POST['name']);
    $type = htmlspecialchars($_POST['type']);
    $subtype = htmlspecialchars($_POST['subtype']);
    $ind1 = htmlspecialchars($_POST['ind1']);
    $ind2 = htmlspecialchars($_POST['ind2']);
    $country = htmlspecialchars($_POST['country']);
    $price = htmlspecialchars($_POST['price']);
    $risk = htmlspecialchars($_POST['risk']);
    $region = htmlspecialchars($_POST['region']);
    $ticker = htmlspecialchars($_POST['ticker']);
    $isin = htmlspecialchars($_POST['isin']);
    $issuer = htmlspecialchars($_POST['issuer']);
    $exchange = htmlspecialchars($_POST['exch']);
    $curr = htmlspecialchars($_POST['curr']);
    $denom = htmlspecialchars($_POST['denom']);
    $cldate = htmlspecialchars($_POST['cldate']);
    $issdate = htmlspecialchars($_POST['issdate']);
    $matdate = htmlspecialchars($_POST['matdate']);
    $coupon = htmlspecialchars($_POST['coupon']);


    $sql = "INSERT INTO `products` (`abbr`, `name`, `type`, `subtype`, 
    `country`, `sector1`, `sector2`, `region`, `ticker`, `isin`, `issuer`,
    `exchange`, `currency`, `denom`, `closing_price`, `closing_date`, 
    `issue_date`, `mature_date`, `coupon`, `risk`, `status`) 
    VALUES ('$abbr','$name','$type','$subtype','$country', '$ind1', '$ind2', 
    '$region', '$ticker', '$isin', '$issuer', '$exchange', '$curr', '$denom',
    '$price', '$cldate', '$issdate', '$matdate', '$coupon', '$risk', 'pending')";

    if ($mysqli->execute_query($sql)) {
        echo '<script type="text/javascript">alert("success");</script>';
    } else {
        echo '<script type="text/javascript">alert("failure");</script>';
    }
}

?>
<h2 id="invest-heading">Submit a New Investment Opportunity</h2>
<form id="creation-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="fields">
        <div class="invest-form-area" id="invest-abbr">
            <label for="abbr">Abbreviation: </label>
            <input class="invest-text" type="text" name="abbr" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-name">
            <label for="name">Investment Name: </label>
            <input class="invest-text" type="text" name="name" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-type">
            <label for="type">Asset Type: </label>
            <input class="invest-text" type="text" name="type" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-subtype">
            <label for="subtype">Subtype: </label>
            <input class="invest-text" type="text" name="subtype" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-ind1">
            <label for="ind1">Industry Sector: </label>
            <input class="invest-text" type="text" name="ind1" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-ind2">
            <label for="ind2">Industry Subsector: </label>
            <input class="invest-text" type="text" name="ind2" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-region">
            <label for="region">Region: </label>
            <input class="invest-text" type="text" name="region" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-country">
            <label for="country">Country: </label>
            <input class="invest-text" type="text" name="country" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-ticker">
            <label for="ticker">Ticker ID: </label>
            <input class="invest-text" type="text" name="ticker" placeholder="eg: AMZN"> <br>
        </div>

        <div class="invest-form-area" id="invest-isin">
            <label for="isin">ISIN: </label>
            <input class="invest-text" type="text" name="isin" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-issuer">
            <label for="issuer">Issuer: </label>
            <input class="invest-text" type="text" name="issuer" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-exch">
            <label for="exch">Stock Exchange: </label>
            <input class="invest-text" type="text" name="exch" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-curr">
            <label for="curr">Currency: </label>
            <input class="invest-text" type="text" name="curr" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-denom">
            <label for="denom">Denomination: </label>
            <input class="invest-text" type="text" name="denom" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-price">
            <label for="price">Closing Price: </label>
            <input class="invest-text" type="text" name="price" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-cldate">
            <label for="cldate">Closing date: </label>
            <input class="invest-text" type="date" name="cldate" placeholder="eg: 17/01/2023"> <br>
        </div>

        <div class="invest-form-area" id="invest-issdate">
            <label for="issdate">Issue Date: </label>
            <input class="invest-text" type="date" name="issdate" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-matdate">
            <label for="matdate">Maturity date: </label>
            <input class="invest-text" type="date" name="matdate" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-coupon">
            <label for="coupon">Coupon: </label>
            <input class="invest-text" type="text" name="coupon" placeholder=""> <br>
        </div>

        <div class="invest-form-area" id="invest-risk">
            <label for="risk">Risk Rating: </label>
            <input class="invest-text" type="text" name="risk" placeholder="1 (low) to 5 (high)"> <br>
        </div>
    </div>
    <input class="btn btn-submit" id="invest-submit" type="submit" name="submit" value="Submit">
</form>

<?php include 'footer.php'; ?>