<?php include './header.php';
//keep pages private from non-registered users
if(!isset($_SESSION['userid'])){
    header('Location: ./login.php');
  }

$sql = "SELECT * FROM `products`";
$res = $mysqli->execute_query($sql);
$data = $res->fetch_all(MYSQLI_ASSOC);

?>
<h1>All Available Products at Smarter Investing Inc.</h1>
<div class="table-wrapper">
    <table class="products-table">
        <thead>
            <tr>
                <th>Product Ticker Name</th>
                <th>Product Name</th>
                <th>Product Exchange</th>
                <th>Product Type</th>
                <th>Product Sector(s)</th>
                <th>Product Price</th>
                <th>Product Currency</th>
                <th>Product Region</th>
                <th>Product Country</th>
                <th>Product Issuer</th>
                <th>Product Closing Date</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($data); $i++) : ?>
                <tr>
                    <td><?php echo '<a href="./product.php?prod='. $data[$i]['id'] .  '">' . $data[$i]['abbr']; ?></td>
                    <td><?php echo $data[$i]['name']; ?></td>
                    <td><?php echo $data[$i]['exchange']; ?></td>
                    <td><?php echo $data[$i]['type']; ?></td>
                    <td><?php echo $data[$i]['sector1'] . ', ' . $data[$i]['sector2']; ?></td>
                    <td><?php echo $data[$i]['closing_price']; ?></td>
                    <td><?php echo $data[$i]['currency']; ?></td>
                    <td><?php echo $data[$i]['region']; ?></td>
                    <td><?php echo $data[$i]['country']; ?></td>
                    <td><?php echo $data[$i]['issuer']; ?></td>
                    <td><?php echo $data[$i]['closing_date']; ?></td>
                </tr>
            <?php endfor ?>
        </tbody>
    </table>
</div>

<?php include './footer.php'; ?>