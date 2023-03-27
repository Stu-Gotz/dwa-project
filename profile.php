<?php
include 'header.php';

//contains the blank avatar image
$default = './assets/blank-profile.png';

if (isset($_SESSION['type']) && $_SESSION['type'] === 'rm') {
  $sql = "SELECT users.id, users.email, users.first_name, users.last_name, users.location , users.phone
          FROM `users` INNER JOIN client_rm ON rm_id = ? AND users.id = client_rm.client_id";
  $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
  $_SESSION['clients'] = $res->fetch_all(MYSQLI_ASSOC);
}

if(isset($_SESSION['type']) && $_SESSION['type'] === 'client'){
  $sql = "SELECT product.type, product.name, product.country, product.closing_price, product.abbr, product.exchange, product.id 
  FROM `product` INNER JOIN prod_client ON client_id = ? AND product.id = prod_client.prod_id";
  $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
  $_SESSION['userprods'] = $res->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="profile-page">
  <?php include 'sidebar.php'; ?>
  <!-- Basically all this does is a bunch of if/else checks to dynamically set stuff based on if it is or isn't there -->
  <div class="profile">
    <div class="profile-head">
      <div class="avatar"><img src="<?php if (isset($_SESSION['photo'])) {
                                      echo $_SESSION['photo'];
                                    } else {
                                      echo $default;
                                    } ?>" alt="profile image for <?php echo $_SESSION['name']; ?>" srcset="" /></div>
      <div class="profile-info">
        <div class="profile-name"><?php echo $_SESSION['name']; ?></div>
        <div class="profile-type"><?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'rm') {
                                    echo 'Relation Manager';
                                  } else if (isset($_SESSION['type']) && $_SESSION['type'] === 'client') {
                                    echo 'Client';
                                  } else if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') {
                                    echo 'Administrator';
                                  } ?></div>
        <div class="profile-phone"><?php if (isset($_SESSION['phone'])) {
                                      echo $_SESSION['phone'];
                                    } ?></div>
        <div class="profile-loc"><?php if (isset($_SESSION['loc'])) {
                                    echo $_SESSION['loc'];
                                  } ?></div>
        <div class="profile-email"><?php if (isset($_SESSION['email'])) {
                                      echo '<a href="mailto:' . $_SESSION['email'] . '">' . $_SESSION['email'] . '</a>';
                                    } ?></div>
      </div>
    </div>
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
              <th>Client Name</th>
              <th>Client Email</th>
              <th>Client Location</th>
              <th>Client Phone</th>
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
            <td>' . $_SESSION['userprods'][$i]['closing_price'] . '</td>
            <td>' . $_SESSION['userprods'][$i]['type'] . '</td>
            <td>' . $_SESSION['userprods'][$i]['country'] . '</td>
            <td>' . $_SESSION['userprods'][$i]['exchange'] . '</td>
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