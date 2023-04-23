<?php include './header.php';
$product = "";

if ($_POST) {
    $product = $_POST['id'];
} elseif ($_GET) {
    $product = $_GET['id'];
}
#htmlspecialchars($_POST['submit']['id']);

//keep pages private from non-registered users
if (!isset($_SESSION['userid'])) {
    header('Location: ./login.php');
}

// if ($_POST) {

//     if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS)) {
//         $sql = "DELETE FROM `products` WHERE id=?";
//         $mysqli->execute_query($sql, [$product]);
//         $sql_ = "DELETE FROM `client_prod` WHERE prod_id=?";
//         $mysqli->execute_query($sql_, [$product]);
//         header('Location: ./profile.php');
//     }
// }
if (isset($_POST['action'])) {
    delete_product($product, $mysqli);
}

// SQL Statement to gather all products available
// This is used to display data of a product.
$sql = "SELECT * FROM `products` WHERE id = ?";
$res = $mysqli->execute_query($sql, [$product]);
$prod = $res->fetch_assoc();

// SQL Statement to find all users that are invested in the product and
// have accepted it to their profile
$sql_ = "SELECT client_id FROM `client_prod` WHERE prod_id=?";
$res_ = $mysqli->execute_query($sql_, [$product]);
$users = $res_->fetch_all(MYSQLI_ASSOC);

$invested_users = array();

for ($u = 0; $u < count($users); $u++) {
    $usersql = "SELECT users.id, users.first_name, users.last_name, users.email FROM `users`
    WHERE id=?";
    $results = $mysqli->execute_query($usersql, [$users[$u]['client_id']]);
    $user = $results->fetch_all(MYSQLI_ASSOC)[0];
    array_push($invested_users, $user);
}


?>

<div class="product-section">
    <h1 id="prod-header"><?php echo $prod['name']; ?></h1>
    <div id="basic-info">
        <div class="prod-info" id="product-abbr">Ticker: <?php echo $prod['abbr']; ?></div>
        <div class="prod-info" id="product-name">Name: <?php echo $prod['name']; ?></div>
        <div class="prod-info" id="product-price">Price: <?php echo $prod['closing_price']; ?></div>
        <div class="prod-info" id="product-country">Country: <?php echo $prod['country']; ?></div>
        <div class="prod-info" id="product-sector">Sector: <?php echo $prod['sector1'] . ', ' . $prod['sector2']; ?></div>
    </div>
    <div id="adv-info">
        <div class="prod-info" id="product-exch">Traded on: <?php echo $prod['exchange']; ?></div>
        <div class="prod-info" id="product-curr">Currency: <?php echo $prod['currency']; ?></div>
        <div class="prod-info" id="product-risk">Assessed Risk [1 (low) to 5 (high)]: <?php echo $prod['risk']; ?></div>
        <div class="prod-info" id="product-type">Product Type: <?php echo $prod['type']; ?></div>
    </div>
    <div class="about-prod">
        <?php if (count($invested_users) === 0) {
            echo 'Nobody has invested in this product yet.';
        } else {
            echo 'The following clients have invested in this product: <br>';
        } ?>
        <?php for ($i = 0; $i < count($invested_users); $i++) : ?>

            <?php echo '<a href="./user.php?email=' .  $invested_users[$i]['email'] . '">' . $invested_users[$i]['first_name'] . ' ' . $invested_users[$i]['last_name'] . '</a><br>' ?>
        <?php endfor ?>

    </div>
    <?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') : ?>
        <div class="admin-controls">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input class="btn btn-del" type="submit" name="delete" value="Delete" />
                <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
            </form>
        </div>
    <?php endif ?>
</div>
<?php include './footer.php'; ?>