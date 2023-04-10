<?php include 'header.php';
if (isset($_GET['email'])){
    $email = htmlspecialchars($_GET['email']);
    $sql = "SELECT * FROM `users` WHERE email = ?";
    $res = $mysqli->execute_query($sql, [$email]);
    $user = $res->fetch_all(MYSQLI_ASSOC)[0];
    $user['name'] = $user['first_name'] . ' ' . $user['last_name'];
}

?>

<!-- <script type="text/javascript">
    window.history.pushState("object or string", "Title", "./user.php")
</script> -->

<div class="user-page">
    <div class="profile">
        <?php include './components/userhead.php'; ?>
    </div>
    <div class="user-table-area">
        <?php if(isset($user['type']) && $user['type'] === 'client') :?>
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

                if(isset($product_list)){
                    for($i=0; $i<count($product_list); $i++){
                        echo '<tr>
                        <td>' . $product_list[$i]['abbr'] . '</td>
                        <td>' . $product_list[$i]['name'] . '</td>
                        <td>' . $product_list[$i]['closing_price'] . '</td>
                        <td>' . $product_list[$i]['country'] . '</td>
                        <td>' . $product_list[$i]['sector1'] . '</td>
                        <td>' . $product_list[$i]['exchange'] . '</td>
                        <td class="' . $product_list[$i]['status']. '">' . $product_list[$i]['status'] . '</td>
                        </tr>';
                    }
                }
            ?>
        </tbody>
    </table>
    <?php endif ?>

    <?php if(isset($user['type']) && $user['type'] === 'rm') :?>
        <table>
        <thead>
            <th>User Image</th>
            <th>Client Name</th>
        </thead>
        <tbody>
            <?php
                $sql_ = 'SELECT *FROM `users` INNER JOIN ON rm_id = ? AND 
                users.id = client_rm.client_id';
                $res_ = $mysqli->execute_query($sql_, [$user['id']]);
                $client_list = $res_->fetch_all(MYSQLI_ASSOC);

                if(isset($client_list)){
                    for($i=0; $i<count($client_list); $i++){
                        echo '<tr>
                        <td><img style="height:50px; width: 50px; border-radius: 50%;" src="./assets/'. $client_list[$i]['photo'] . '" /></td>
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