<?php include './header.php';
$product = htmlspecialchars($_GET['prod']);

//keep pages private from non-registered users
if(!isset($_SESSION['userid'])){
    header('Location: ./login.php');
  }

if(isset($_POST['delete'])){
    $sql = "DELETE FROM `products` WHERE id=?";
    $mysqli->execute_query($sql, [$products]);
    $sql_ = "DELETE FROM `client_prod` WHERE prod_id=?";
    $mysqli->execute_query($sql_, [$product]);
    header('Location: ./success.php');
}

$sql = "SELECT * FROM `products` WHERE id = ?";
$res = $mysqli->execute_query($sql, [$product]);
$prod = $res->fetch_all(MYSQLI_ASSOC)[0];

$sql_ = "SELECT client_id FROM `client_prod` WHERE prod_id=?";
$res_ = $mysqli->execute_query($sql_, [$product]);
$users = $res->fetch_all(MYSQLI_ASSOC);

var_dump($users);
$invested_users = array();

for($u=0; $u<count($users);$u++){
    $usersql = "SELECT users.id, users.first_name, users.last_name, users.email FROM `users`
    WHERE id=?";
    $results = $mysqli->execute_query($usersql, $users[$u]['user_id']);
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
        <?php if(count($invested_users) === 0){
            echo 'Nobody has invested in this product yet.';
        } else {
            echo 'The following clients have invested in this product: ';
        } ?>
        <?php for($i=0; $i<count($invested_users); $i++) :?>
        
        <?php echo '<a href="./users.php?email=' .  $invested_users[$i]['email']. '">' . $invested_users[$i]['first_name'] . ' ' . $invested_users[$i]['last_name'] . '</a>'?>
        <?php endfor ?>
        
    </div>
    <?php if(isset($_SESSION['type']) && $_SESSION['type'] === 'admin') :?>
    <div class="admin-controls">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <input class="btn-del" type="submit" name="delete" value="Delete">
        </form>
    </div>
    <?php endif ?>
</div>
<?php include './footer.php'; ?>