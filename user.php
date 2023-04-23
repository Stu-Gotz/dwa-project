<?php include 'header.php';
/* 
A lot of this page is very similar to the profile.php. For any notes relating to how the tables are generated,
please see the comments in that file

There is no table rendering for the admin because there is nothing that a visiting person would need to know, at this point in production.

*/

//keep pages private from non-registered users
if (!isset($_SESSION['userid'])) {
    header('Location: ./login.php');
}

//the email, because they are unique, will be used to identify which profile the user is trying to view
//the $user variable is then passed around the page to populate information.
//the [0] indexer ensures we only get an array with 1 entry, in case email validation failed
if (isset(($_GET['email'])) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    $email = htmlspecialchars($_GET['email']);
    $sql = "SELECT * FROM `users` WHERE email = ?";
    $res = $mysqli->execute_query($sql, [$email]);
    $user = $res->fetch_all(MYSQLI_ASSOC)[0];
    $user['name'] = $user['first_name'] . ' ' . $user['last_name'];
}

?>


<div class="user-page">
    <?php include './components/sidebar.php' ?>
    <div class="profile">
        <?php include './components/userhead.php'; ?>
    </div>
    
    <div class="user-table-area">
        <?php if (isset($user['type']) && $user['type'] === 'client') : ?>
            <table>
                <thead>
                    <th>Abbreviation</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Country</th>
                    <th>Industry</th>
                    <th>Exchange</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    <?php
                    $sql_ = 'SELECT products.type, products.name, products.country, products.closing_price, 
                products.abbr, products.exchange, products.id, products.sector1, products.status
                FROM `products` INNER JOIN `client_prod` ON client_id = ? 
                AND products.id = client_prod.prod_id';
                    $res_ = $mysqli->execute_query($sql_, [$user['id']]);
                    $product_list = $res_->fetch_all(MYSQLI_ASSOC);

                    if (isset($product_list)) {
                        for ($i = 0; $i < count($product_list); $i++) {
                            echo '<tr>
                        <td>' . $product_list[$i]['abbr'] . '</td>
                        <td><a href="./product.php?id=' .  $product_list[$i]['id'] . '">' . $product_list[$i]['name'] . '</a></td>
                        <td>' . $product_list[$i]['closing_price'] . '</td>
                        <td>' . $product_list[$i]['country'] . '</td>
                        <td>' . $product_list[$i]['sector1'] . '</td>
                        <td>' . $product_list[$i]['exchange'] . '</td>
                        <td class="accepted">' . $product_list[$i]['status'] . '</td>
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <h2>Preferences </h2>
            <?php
            $sql_ = 'SELECT * FROM client_prefs WHERE client_id=?';
            $res_ = $mysqli->execute_query($sql_, [$user['id']]);
            $prefs = $res_->fetch_all(MYSQLI_ASSOC);
            ?>
            <?php if ($prefs) : ?>
                <?php foreach ($prefs as $p) : ?>
                    <div class="prefs-area" id="sector">
                        <h4 class="pref-cat">Sector: </h4>
                        <div class="pref"><?php echo $p['ind']; ?></div>
                    </div>
                    <div class="prefs-area" id="type">
                        <h4 class="pref-cat">Type: </h4>
                        <div class="pref"><?php echo $p['type']; ?></div>
                    </div>
                    <div class="prefs-area" id="country">
                        <h4 class="pref-cat">Country: </h4>
                        <div class="pref"><?php echo $p['country']; ?></div>
                    </div>
                    <div class="prefs-area" id="risk">
                        <h4 class="pref-cat">Risk: </h4>
                        <div class="pref"><?php echo $p['risk']; ?></div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
    </div>
<?php endif ?>

<?php if (isset($user['type']) && $user['type'] === 'rm') : ?>
    <table>
        <thead>
            <th>Image</th>
            <th>Client Name</th>
        </thead>
        <tbody>
            <?php
            $sql_ = 'SELECT users.id, users.first_name, users.last_name, users.photo
                FROM `users` INNER JOIN `client_rm` ON rm_id = ? 
                AND users.id = client_rm.client_id';
            $res_ = $mysqli->execute_query($sql_, [$user['id']]);
            $client_list = $res_->fetch_all(MYSQLI_ASSOC);

            if ($client_list) {
                for ($i = 0; $i < count($client_list); $i++) {
                    echo '<tr>
                        <td><img style="height:50px; width: 50px; border-radius: 50%;" src="./assets/' . $client_list[$i]['photo'] . '" /></td>
                        <td>' . $client_list[$i]['first_name'] . ' ' . $client_list[$i]['last_name'] . '</td>
                        </tr>';
                }
            }
            ?>
        </tbody>
    </table>
<?php endif ?>

</div>
</div>
<?php include 'footer.php' ?>