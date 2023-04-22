<?php
include 'header.php';
// TODO

/* 
1 ACCEPT OR DECLINE INVESTMENT IDEA @ TABLE

*/
//contains the blank avatar image
$default = './assets/blank-profile.png';

//keep pages private from non-registered users
if (!isset($_SESSION['userid'])) {
  header('Location: ./login.php');
}

if (isset($_GET['submit'])) {
  $prod_id = $_GET[0];
  $row_id = (int)$_GET[1];

  echo '<script type="text/javascript"><alert>"delete pressed"</alert></script>';
  //remove from associations
  $sql = "DELETE `prod_client`, `products` FROM `prod_client` WHERE prod_client.prod_id = ? AND products.id = ?;";
  $res = $mysqli->execute_query($sql, [$prod_id, $prod_id]);
  $res->free_result();
  unset($_SESSION['userprods'][$row_id]);

  header("Location: profile.php");
}
if (isset($POST['deny'])) {
  header("Location: profile.php");
}

function determineStatus($product, $userid, $mysqli)
{
  $sql = "SELECT * FROM `client_prod` WHERE client_prod.prod_id = ? AND client_prod.client_id = ?";
  if ($mysqli->execute_query($sql, [$product, $userid])) {
    return 'accepted';
  } else {
    return 'pending';
  };
}
?>

<div class="profile-page">
  <?php include './components/sidebar.php' ?>
  <!-- Pages will render differently based on the type of user that is loged in
    Client users: will be given a  -->
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

            $sql = "SELECT users.id, users.email, users.first_name, users.last_name, 
            users.location , users.phone FROM `users` INNER JOIN client_rm ON 
            rm_id = ? AND users.id = client_rm.client_id";
            $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
            $_SESSION['clients'] = $res->fetch_all(MYSQLI_ASSOC);

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
        <h2>Current Investments</h2>
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
            $sql = 'SELECT products.type, products.name, products.country, products.closing_price, 
            products.abbr, products.exchange, products.id, products.status FROM `products` INNER JOIN 
            `client_prod` ON client_id = ? AND products.id = client_prod.prod_id';
            $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
            $_SESSION['userprods'] = $res->fetch_all(MYSQLI_ASSOC);

            for ($i = 0; $i < count($_SESSION['userprods']); $i++) {
              echo '<tr class="' . determineStatus($_SESSION['userprods'][$i]['id'], $_SESSION['userid'], $mysqli) . '">
            <td><a href="./product.php?prod=' . htmlspecialchars($_SESSION['userprods'][$i]['id']) . '">' . $_SESSION['userprods'][$i]['abbr'] . '</a></td>
            <td><a href="./product.php?prod=' . htmlspecialchars($_SESSION['userprods'][$i]['id']) . '">' . $_SESSION['userprods'][$i]['name'] . '</a></td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['closing_price']) . '</td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['type']) . '</td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['country']) . '</td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['exchange']) . '</td>
            <td> <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '"?id=' . htmlspecialchars($_SESSION['userprods'][$i]['id']) . '&row=' . $i . '" method="GET"><button class="btn btn-accept" type="submit" name="accept">Approve</button></form></td>
            <td> <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '"?id=' . htmlspecialchars($_SESSION['userprods'][$i]['id']) . '&row=' . $i . '" method="GET"><button class="btn btn-del" type="submit" name="deny">Deny</button</form></td>
          </tr>';
            }
            ?>
          </tbody>
        </table>
        <div class="client-prefs">
          <h2>Preferences </h2>
          <?php
          $sql_ = 'SELECT * FROM client_prefs WHERE client_id=?';
          $res_ = $mysqli->execute_query($sql_, [$_SESSION['userid']]);
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
              <th>Price</th>
              <th>Type</th>
              <th>Country</th>
              <th>Exchange</th>
              <th>Status</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody class="table-data">
            <?php
            $sql = "SELECT products.type, products.name, products.country, products.closing_price, 
              products.abbr, products.exchange, products.id, products.status FROM `products`;";
            $res = $mysqli->execute_query($sql);
            $_SESSION['userprods'] = $res->fetch_all(MYSQLI_ASSOC);

            for ($i = 0; $i < count($_SESSION['userprods']); $i++) {
              echo '<tr>
            <td><a href="./product.php?prod=' . $_SESSION['userprods'][$i]['id'] . '">' . $_SESSION['userprods'][$i]['abbr'] . '</a></td>
            <td><a href="./product.php?prod=' . $_SESSION['userprods'][$i]['id'] . '">' . $_SESSION['userprods'][$i]['name'] . '</a></td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['closing_price']) . '</td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['type']) . '</td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['country']) . '</td>
            <td>' . htmlspecialchars($_SESSION['userprods'][$i]['exchange']) . '</td>
            <td class="' . $_SESSION['userprods'][$i]['status'] .  '">' . $_SESSION['userprods'][$i]['status'] . '</td>
            <td><form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $_SESSION['userprods'][$i]['id'] . '&row=' . $i . '" method="GET"><button class="btn btn-del" type="submit">Delete</button></form></td>
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