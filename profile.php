<?php
include 'header.php';



if (isset($_SESSION['name'])) {
  $name = $_SESSION['name'];
}

$default = './assets/blank-profile.png';


?>

<div class="profile-page">
  <ul class="sidebar-menu">
    <li><a href="/dashboard.php">Products</a></li>
    <li><a href="settings.php">Settings</a></li>
    <li><a href="/about.php">About</a></li>
    <li><a href="/inbox.php">Messages</a></li>

  </ul>
  <div class="profile">
    <div class="profile-head">
      <div class="avatar"><img src="<?php if(isset($_SESSION['photo'])) { echo $_SESSION['photo']; } else { echo $default; }?>" alt="profile image for <?php echo $_SESSION['name']; ?>" srcset="" /></div>
      <div class="profile-info">
        <div class="profile-name"><?php echo $_SESSION['name']; ?></div>
        <div class="profile-type"><?php if (isset($_SESSION['type']) == 'RM') {
                                    echo 'Relation Manager';
                                  } else if (isset($_SESSION['type']) == 'client') {
                                    echo 'Client';
                                  } else if (isset($_SESSION['type']) == 'admin') {
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

          // mysql query to get all users of type client with $_SESSION['name'] == table['manager']
          // for($i=0; $i < table.length; $i++){
          //   echo '<tr>
          //   <td>'. $table['first_name'] . ' ' . table['last_name'] . '</td>
          //   <td>' . $table['email'] . '</td>
          //   <td>' . table['location'] . '</td>
          //   <td>' . table['phone'] . '</td>
          // </tr>';
          // }
          ?>
          <!-- delete this after logic figured out -->
          <tr>
            <td>John Doe</td>
            <td>John.Doe@example.com</td>
            <td>London, UK</td>
            <td>+441234567890</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>