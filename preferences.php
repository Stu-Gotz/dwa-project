<?php include './header.php';


if (isset($_POST['submit'])) {
    
    $type = htmlspecialchars($_POST['type']);
    $ind = htmlspecialchars($_POST['ind']);
    $country = htmlspecialchars($_POST['country']);
    $risk = htmlspecialchars($_POST['risk']);

    $sql = "INSERT INTO 'client_prefs' ('client_id', 'type', 'ind', 'country', 'risk' )
    VALUES (?, ?, ?, ?, ?, )";

    if ($mysqli->execute_query($sql, [$id, $type, $ind, $country, $risk])) {
        echo '<script type="text/javascript">alert("success");</script>';
    } else {
        echo '<script type="text/javascript">alert("failure");</script>';
    }
}

?>
<h2 id="preferences-heading">Update Your Preferences</h2>
<form id="preferences-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="fields">
        <div class="preferences-form-area" id="preferences-type">
            <label for="type">Asset Type: </label>
            <input class="preference-text" type="text" name="type" placeholder=""> <br>
        </div>
        <div class="preferences-form-area" id="preferences-ind">
            <label for="ind1">Industry Sector: </label>
            <input class="preference-text" type="text" name="ind" placeholder=""> <br>
        </div>
        <div class="preferences-form-area" id="preferences-country">
            <label for="country">Country: </label>
            <input class="preference-text" type="text" name="country" placeholder=""> <br>
        </div>
        <div class="preferences-form-area" id="preferences-risk">
            <label for="risk">Risk Rating: </label>
            <input class="preference-text" type="text" name="risk" placeholder="1 (low) to 5 (high)"> <br>
        </div>
    </div>
    <input class="btn btn-submit" id="invest-submit" type="submit" name="submit" value="Submit">
</form>

<?php include './footer.php'; ?>