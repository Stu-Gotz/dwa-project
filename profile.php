<?php
include 'header.php';
// TODO

/* 
1 ACCEPT OR DECLINE INVESTMENT IDEA @ TABLE

*/
//contains the blank avatar image
$default = './assets/blank-profile.png';

if (isset($_SESSION['type']) && $_SESSION['type'] === 'rm') {
  $sql = "SELECT users.id, users.email, users.first_name, users.last_name, 
  users.location , users.phone FROM `users` INNER JOIN client_rm ON 
  rm_id = ? AND users.id = client_rm.client_id";
  $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
  $_SESSION['clients'] = $res->fetch_all(MYSQLI_ASSOC);
}

if (isset($_SESSION['type']) && $_SESSION['type'] === 'client') {
  $sql = 'SELECT products.type, products.name, products.country, products.closing_price, 
  products.abbr, products.exchange, products.id, products.status FROM `products` INNER JOIN 
  `client_prod` ON client_id = ? AND products.id = client_prod.prod_id';
  $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
  $_SESSION['userprods'] = $res->fetch_all(MYSQLI_ASSOC);
}

if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') {
  $sql = "SELECT products.name, products.id, products.abbr, products.status FROM `products`;";
  $res = $mysqli->execute_query($sql);
  $_SESSION['userprods'] = $res->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['delete'])) {
  $prod_id = $_POST['id'];
  $row_id = (int)$_POST['row'];

  //remove from associations
  $sql = "DELETE FROM `prod_client` WHERE prod_id ='" . $prod_id . "' AND DELETE FROM `products` WHERE id= '". $prod_id . "';";
  
  unset($_SESSION['userprods'][$row_id]);

  header("Location: profile.php");
}
if (isset($POST['deny'])){
  header("Location: profile.php");
}

function determineStatus($product, $userid, $mysqli){
  $sql = "SELECT * FROM `client_prod` WHERE client_prod.prod_id = ? AND client_prod.client_id = ?";
  if($mysqli->execute_query($sql, [$product, $userid])){
    return 'accepted';
  } else {
    return 'pending';
  };
}
?>

<div class="profile-page">
  <?php include './components/sidebar.php' ?>
  <!-- Basically all this does is a bunch of if/else checks to dynamically set stuff based on if it is or isn't there -->
  <div class="profile">
    <?php include './components/profilehead.php' ?>
    <?php if ($_SESSION['type'] === 'rm') : ?>
      <div class="table-area">
        <table class="client-table">
          <thead>
            <tr>
              <th>Client Name</th>
              <th>Client Email</th>
              <th>Client Location</th>
              <th>Client Phone</th>
            </tr>
          </thead>
          <tbody class="table-data">
            <?php
            for ($i = 0; $i < count($_SESSION['clients']); $i++) {
              echo '<tr>
            <td><a href="./user.php?email=' . $_SESSION['clients'][$i]['email'] . '">' . $_SESSION['clients'][$i]['first_name'] . ' ' . $_SESSION['clients'][$i]['last_name'] . '</a></td>
            <td>' . $_SESSION['clients'][$i]['email'] . '</td>
            <td>' . $_SESSION['clients'][$i]['location'] . '</td>
            <td>' . $_SESSION['clients'][$i]['phone'] . '</td>
          </tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
    <?php if ($_SESSION['type'] === 'client') : ?>
      <div class="table-area">
        <table class="client-table">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Product Email</th>
              <th>Price</th>
              <th>Type</th>
              <th>Country</th>
              <th>Exchange</th>
              <th>Accept</th>
              <th>Deny</th>
            </tr>
          </thead>
          <tbody class="table-data">
            <?php
            // NEED TO FIGURE OUT HOW TO NAVIGATE TO PAGE AND ACCESS USER DATA
            // COOKIES DOESNT SEEM SUFFICIENT
            for ($i = 0; $i < count($_SESSION['userprods']); $i++) {
              echo '<tr class="' . determineStatus($_SESSION['userprods'][$i]['id'], $_SESSION['userid'], $mysqli) . '">
            <td><a href="./product.php?prod=' . htmlspecialchars($_SESSION['userprods'][$i]['name']) . '">' . $_SESSION['userprods'][$i]['abbr'] . '</a></td>
            <td><a href="./product.php?prod=' . htmlspecialchars($_SESSION['userprods'][$i]['name']) . '">' . $_SESSION['userprods'][$i]['name'] . '</a></td>
            <td>' . $_SESSION['userprods'][$i]['closing_price'] . '</td>
            <td>' . $_SESSION['userprods'][$i]['type'] . '</td>
            <td>' . $_SESSION['userprods'][$i]['country'] . '</td>
            <td>' . $_SESSION['userprods'][$i]['exchange'] . '</td>
            <td> <form action="' . $_SERVER['PHP_SELF'] . '?id=' . htmlspecialchars($_SESSION['userprods'][$i]['id']) . '&row=' . $i. '" method="POST"><button class="btn btn-accept" type="submit" name="accept">Approve</button></form></td>
            <td> <form action="' . $_SERVER['PHP_SELF'] . '?id=' . htmlspecialchars($_SESSION['userprods'][$i]['id']) . '&row=' . $i. '" method="POST"><button class="btn btn-del" type="submit" name="deny">Deny</button</form></td>
          </tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
    <?php if ($_SESSION['type'] === 'admin') : ?>
      <div class="table-area">
        <table class="client-table">
          <thead>
            <tr>
              <th>Product Abbreviation</th>
              <th>Product Name</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody class="table-data">
            <?php
            // NEED TO FIGURE OUT HOW TO NAVIGATE TO PAGE AND ACCESS USER DATA
            // COOKIES DOESNT SEEM SUFFICIENT
            for ($i = 0; $i < count($_SESSION['userprods']); $i++) {
              echo '<tr>
            <td><a href="./product.php?prod=' . $_SESSION['userprods'][$i]['name'] . '">' . $_SESSION['userprods'][$i]['abbr'] . '</a></td>
            <td><a href="./product.php?prod=' . $_SESSION['userprods'][$i]['name'] . '">' . $_SESSION['userprods'][$i]['name'] . '</a></td>
            <td class="' . $_SESSION['userprods'][$i]['status'] .  '">' . $_SESSION['userprods'][$i]['status']. '</td>
            <td><form action="' . $_SERVER['PHP_SELF'] . '" method="POST?id="' . $_SESSION['userprods'][$i]['name'] .'&row="' . $i . '"><button class="btn-del" type="submit" name="delete">Delete</button></form></td>
          </tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <?php endif ?>
  </div>
</div>
<?php include 'footer.php'; ?>