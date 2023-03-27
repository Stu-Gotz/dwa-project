<?php include 'header.php';

$email = htmlspecialchars($_GET['email']);
$sql = "SELECT * FROM `users` WHERE email = ?";
$res = $mysqli->execute_query($sql, [$email]);
$_SESSION['client'] = $res->fetch_all(MYSQLI_ASSOC)[0];
var_dump($_SESSION['client']);
$_SESSION['client']['name'] = $_SESSION['client']['first_name'] . ' ' . $_SESSION['client']['last_name'];
$default = './assets/blank-profile.png';

$sql = "";
?>
<!--  alt="profile image for <?php echo $_SESSION['client']['name']; ?>" -->

<div class="profile-page">
    <div class="profile">
        <div class="profile-head">
            <div class="avatar">
                <img src="<?php if (isset($_SESSION['client']['photo']) && $_SESSION['client']['photo'] != "") {
                                echo $_SESSION['client']['photo'];
                            } else {
                                echo $default;
                            } ?>" />
            </div>
            <div class="profile-info">
                <div class="profile-name"><?php echo $_SESSION['client']['name']; ?></div>
                <div class="profile-type"><?php if (isset($_SESSION['client']['type']) && $_SESSION['client']['type'] === 'rm') {
                                                echo 'Relation Manager';
                                            } else if (isset($_SESSION['client']['type']) && $_SESSION['client']['type'] === 'client') {
                                                echo 'Client';
                                            } else if (isset($_SESSION['client']['type']) && $_SESSION['client']['type'] === 'admin') {
                                                echo 'Administrator';
                                            } ?></div>
                <div class="profile-phone"><?php if (isset($_SESSION['client']['phone'])) {
                                                echo $_SESSION['client']['phone'];
                                            } ?></div>
                <div class="profile-loc"><?php if (isset($_SESSION['client']['loc'])) {
                                                echo $_SESSION['client']['loc'];
                                            } ?></div>
                <div class="profile-email"><?php if (isset($_SESSION['client']['email'])) {
                                                echo '<a href="mailto:' . $_SESSION['client']['email'] . '">' . $_SESSION['client']['email'] . '</a>';
                                            } ?></div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>