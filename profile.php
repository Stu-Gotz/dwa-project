<?php
include 'header.php';

//contains the blank avatar image
$default = './assets/blank-profile.png';

//keep certain pages private from non-registered users
if (!isset($_SESSION['userid'])) {
  header('Location: ./login.php');
}

if ($_POST) {

  /* If the user elects to remove an item, it will first check if they are a client. RMs cannot remove
     investments on the behalf of clients, or subscribe to them.
     The client vs admin check is because the same component is being re-used on both pages.

  */ 
  if ($_POST['action'] && ($_POST['action'] === "Remove")) {
    if ($_SESSION['type'] === 'client') {
      $prod_id = (int)htmlspecialchars($_POST['id']);
      $row_id = (int)htmlspecialchars($_POST['row']);
      $client_id = (int)htmlspecialchars($_SESSION['userid']);


      // remove lookup table entry b by searching for matching product and client IDs
      $sql = "DELETE FROM `client_prod` WHERE prod_id = $prod_id AND client_id = $client_id;";
      if ($mysqli->query($sql)) {
        // Remove the entry from the $_SESSION['userprods'] array
        unset($_SESSION['userprods'][$row_id]);

        header("Location: ./profile.php");
      } elseif ($mysqli->errno) {
        echo 'Error: ' . $mysqli->error;
      }
    } elseif ($_SESSION['type'] === "admin") {
      // since admins only delete items fullstop, it will delete the product
      // with the delete_product custom function, found in header.php.
      // this function removes the product both from all the lookup table
      // and from the products data table
      delete_product(htmlspecialchars($_POST['id']), $mysqli);
      header("Location: ./profile.php");
    }
  }
  /* an approve request only comes from the client, at the moment. The RM's 
    approval functions have not been implemented.
    By getting the ID of the product, and the user_id from the $_SESSION variable,
    the user can add and remove items from their portfolio.
   this also updates the tables
   */
  if ($_POST['action'] && ($_POST['action'] === 'Approve')) {

    // validation check boolean variable. changes to true 
    // if no matches are found in the validation check.
    $valid = false;
  
    if ($_POST['id'] && (filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS))) {
      $userid = htmlspecialchars($_SESSION['userid']);
      $productid = htmlspecialchars($_POST['id']);

      // need all client-product relation data to perform validation checks

      $stmt = "SELECT client_id, prod_id FROM `client_prod`";
      $stmt = $mysqli->execute_query($stmt);
      $result = $stmt->fetch_all(MYSQLI_ASSOC);

      /* Validation Check:
        if the IDs already exist in the lookup table, then the entry is invalid
        the validation check is explicitly set to false in case it changed because previous values did not match
        the loop then breaks and the logic ceases for this action.
      */
      foreach ($result as $r) {

        if (((int)$productid  === (int)$r['prod_id']) && ((int)$userid  === (int)$r['client_id'])) {
          $valid = false;
          break 1; //<--- jump out of the loop. one fail is all we need.
        } else {
          $valid = true; //<--set it true if there isnt a match on this instance. if it stays true to the end, continue with logic below
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
        $status = determineStatus($productid, $mysqli); //returns 'pending' or 'accepted'
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
// means any client is currently invested. Pending means the client is not, but can.
function determineStatus($product, $mysqli)
{
  //Function works by querying the lookup table and seeing if the product id exists there
  //if it does, then someone has subscribed to it, and it is accepted. otherwise the query
  //returns false and it must be pending.
  $sql = "SELECT * FROM `client_prod` WHERE client_prod.prod_id = ?";  
  if ($mysqli->execute_query($sql, [$product])) {
    return 'accepted';
  } else {
    return 'pending';
  };
}

?>

<div class="profile-page">
  <?php include './components/sidebar.php' ?>
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
            //sql query finds all users who are managed by the RM.
            //these users and their information is displayed in a table on the RM's page
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
            
            //perform a SQL query to get all the data, then iterate over the resulting array and place values into locations
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
            <td> <form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . //this form/input sends variables to $_POST to be used for updating data
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
            //perform a sql query to get all products.
            $sql_ = 'SELECT * FROM `products`';
            $res_ = $mysqli->execute_query($sql_);
            $available = $res_->fetch_all(MYSQLI_ASSOC);
            
            //perform a check if any of the client's current 
            //investments are listed in the total products from above
            //if it is, remove that product array from the total
            //then same as the table above, iterate over and display data
            for ($a = 0; $a < count($available); $a++) {
              foreach ($client_active_products as $cap) {
                if ($available[$a]['abbr'] === $cap['abbr']) {
                  array_splice($available, $a, 1);
                }
              }
            }
            echo count($available); //<- nice little count for potential products
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
          // this is the same basic idea as above, except this uses if/end if and for/end for
          // which I prefer because it doesnt require so much string concatenation.
          // this part wasnt developed until later though and thats why there is inconsistency in the
          // code blocks. "dont let perfect be the enemy of good".
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
            //admin needs all products
            $sql = "SELECT DISTINCT * FROM `products`;";
            $res = $mysqli->execute_query($sql);
            $all_products = $res->fetch_all(MYSQLI_ASSOC);
            //get results, iterate and fill
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