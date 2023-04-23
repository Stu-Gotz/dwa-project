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

if ($_POST) {

  if ($_POST['action'] && ($_POST['action'] === "Remove")) {
    if ($_SESSION['type'] === 'client') {
      $prod_id = (int)htmlspecialchars($_POST['id']);
      $row_id = (int)htmlspecialchars($_POST['row']);
      $client_id = (int)htmlspecialchars($_SESSION['userid']);


      //remove product from lookup tables
      $sql = "DELETE FROM `client_prod` WHERE prod_id = $prod_id AND client_id = $client_id;";
      if ($mysqli->query($sql)) {

        unset($_SESSION['userprods'][$row_id]);

        header("Location: ./profile.php");
      } elseif ($mysqli->errno) {
        echo 'Error: ' . $mysqli->error;
      }
    } elseif ($_SESSION['type'] === "admin") {
      delete_product(htmlspecialchars($_POST['id']), $mysqli);
      header("Location: ./profile.php");
    }
  }
  if ($_POST['action'] && ($_POST['action'] === 'Approve')) {
    $valid = false;
    if ($_POST['id'] && (filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS))) {
      $userid = htmlspecialchars($_SESSION['userid']);
      $productid = htmlspecialchars($_POST['id']);


      $stmt = "SELECT client_id, prod_id FROM `client_prod`";
      $stmt = $mysqli->execute_query($stmt);
      $result = $stmt->fetch_all(MYSQLI_ASSOC);

      foreach ($result as $r) {

        if (((int)$productid  === (int)$r['prod_id']) && ((int)$userid  === (int)$r['client_id'])) {
          echo 'match found ' .  var_dump($r) . '<br>';
          $valid = false;
          break 1;
        } else {
          echo 'no match found. <br>';
          $valid = true;
        }
      }
      if ($valid) {
        // INSERT query to add a client-product relationship in the lookup table
        // Uses the previously sanitized variables $userid or $productid as
        // appropriate

        $mysqli->execute_query("INSERT INTO `client_prod` (`client_id`, `prod_id`) VALUES (?, ?);", [$userid, $productid]);

        // if ($mysqli->execute_query("SELECT * FROM `client_prod` WHERE prod_id=?;", [$productid])) {
        // Because accept was pressed, at least 1 person has accepted the product, so
        // we change the value to 'accepted' for that column
        $status = determineStatus($productid, $mysqli);
        echo $status;
        $mysqli->execute_query("UPDATE `products` SET products.status = ? WHERE id = ?", [$status, $productid]);
        header('Location: ./profile.php');
      }

      if ($mysqli->errno) {
        echo 'SQL Error: ' . $mysqli->error;
      }
    }
  }
}
// Function to determine the status of a product, accepted versus pending. Accepted
// means the client is currently invested. Pending means the client is not, but can.
function determineStatus($product, $mysqli)
{
  $sql = "SELECT * FROM `client_prod` WHERE client_prod.prod_id = ?";
  // $res = $mysqli->execute_query($sql, [$product, $userid]);
  // $active = $res->fetch_all();
  // $res_ = $mysqli->execute_query("SELECT * FROM products");
  // $potential = $res_->fetch_all();

  // for($a = 0; $a < count($active); $a++;) {
  //   for ($p = 0; $p < count($potential); $p++ ) {
  //     if ($potential[$p]['id'] === $active[$a]['prod_id']) {
  //       continue;
  //     } else {
  //       array_push($_SESSION['userprods'], $p);
  //     }
  //   }
  // }
  $success_check = $mysqli->execute_query($sql, [$product]);
  if ($success_check) {
    return 'accepted';
  } else {
    return 'pending';
  };
}
//
// Sadly this never worked. It would be much easier to handle with JavaScript to 
// simply edit CSS class names conditionally.
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
            <td><a href="./user.php?email=' . $_SESSION['clients'][$i]['email']
                . '">' . $_SESSION['clients'][$i]['first_name'] . ' ' .
                $_SESSION['clients'][$i]['last_name'] . '</a></td>
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
              <th>Product Abbr.</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Type</th>
              <th>Country</th>
              <th>Exchange</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody class="table-data">
            <?php
            $client_active_products = [];

            $sql = 'SELECT products.type, products.name, products.country, products.closing_price, 
            products.abbr, products.exchange, products.id, products.status FROM `products` INNER JOIN 
            `client_prod` ON client_id = ? AND products.id = client_prod.prod_id';
            $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
            $client_active_products = $res->fetch_all(MYSQLI_ASSOC);

            for ($i = 0; $i < count($client_active_products); $i++) {
              echo '<tr class="accepted">
            <td><a href="./product.php?id=' .
                htmlspecialchars($client_active_products[$i]['id']) . '">' .
                $client_active_products[$i]['abbr'] . '</a></td>
            <td><a href="./product.php?id=' .
                htmlspecialchars($client_active_products[$i]['id']) . '">' .
                $client_active_products[$i]['name'] . '</a></td>
            <td>' . htmlspecialchars($client_active_products[$i]['closing_price']) . '</td>
            <td>' . htmlspecialchars($client_active_products[$i]['type']) . '</td>
            <td>' . htmlspecialchars($client_active_products[$i]['country']) . '</td>
            <td>' . htmlspecialchars($client_active_products[$i]['exchange']) . '</td>
            <td> <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) .
                '" method="post"><input class="btn btn-del" type="submit" name="action" value="Remove"/><input type="hidden" name="row" value="'
                . $i . '"/><input type="hidden" name="id" value="' .
                htmlspecialchars($client_active_products[$i]['id']) . '"/> </form></td>
          </tr>';
            }
            ?>

          </tbody>
        </table>
        <h2>New Opportunities</h2>
        <table class="client-table">
          <thead>
            <tr>
              <th>Product Abbr.</th>
              <th>Product Name</th>
              <th>Price</th>
              <th>Type</th>
              <th>Country</th>
              <th>Exchange</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody class="table-data">
            <?php

            $sql_ = 'SELECT * FROM `products`';
            $res_ = $mysqli->execute_query($sql_);
            $available = $res_->fetch_all(MYSQLI_ASSOC);

            for ($a = 0; $a < count($available); $a++) {
              foreach ($client_active_products as $cap) {
                if ($available[$a]['abbr'] === $cap['abbr']) {
                  array_splice($available, $a, 1);
                  // if (count($available) === abs(count($available) - count($client_active_products))) {
                  //   break;
                  // } else {
                  // }
                }
              }
            }
            echo count($available);
            for ($i = 0; $i < count($available); $i++) {
              echo '<tr class="pending">
            <td><a href="./product.php?id=' .
                htmlspecialchars($available[$i]['id']) . '">' .
                $available[$i]['abbr'] . '</a></td>
            <td><a href="./product.php?id=' .
                htmlspecialchars($available[$i]['id']) . '">' .
                $available[$i]['name'] . '</a></td>
            <td>' . htmlspecialchars($available[$i]['closing_price']) . '</td>
            <td>' . htmlspecialchars($available[$i]['type']) . '</td>
            <td>' . htmlspecialchars($available[$i]['country']) . '</td>
            <td>' . htmlspecialchars($available[$i]['exchange']) . '</td>
            <td> <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) .
                '" method="post"><input class="btn btn-accept" type="submit" name="action" value="Approve" /><input type="hidden" name="row" value="'
                . $i . '" /><input type="hidden" name="id" value="' .
                htmlspecialchars($available[$i]['id']) . '" /> </form></td>
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
            $sql = "SELECT DISTINCT * FROM `products`;";
            $res = $mysqli->execute_query($sql);
            $all_products = $res->fetch_all(MYSQLI_ASSOC);

            for ($i = 0; $i < count($all_products); $i++) {
              echo '<tr>
            <td><a href="./product.php?id=' . $all_products[$i]['id'] . '">' . $all_products[$i]['abbr'] . '</a></td>
            <td><a href="./product.php?id=' . $all_products[$i]['id'] . '">' . $all_products[$i]['name'] . '</a></td>
            <td>' . htmlspecialchars($all_products[$i]['closing_price']) . '</td>
            <td>' . htmlspecialchars($all_products[$i]['type']) . '</td>
            <td>' . htmlspecialchars($all_products[$i]['country']) . '</td>
            <td>' . htmlspecialchars($all_products[$i]['exchange']) . '</td>
            <td class="' . strtolower($all_products[$i]['status']) .  '">' . strtoupper($all_products[$i]['status']) . '</td>
            <td> <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) .
                '" method="post"><input class="btn btn-del" type="submit" name="action" value="Remove"/><input type="hidden" name="row" value="'
                . $i . '"/><input type="hidden" name="id" value="' .
                htmlspecialchars($all_products[$i]['id']) . '"/> </form></td>
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