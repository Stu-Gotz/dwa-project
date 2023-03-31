<?php include 'header.php';

$email = htmlspecialchars($_GET['email']);
$sql = "SELECT * FROM `users` WHERE email = ?";
$res = $mysqli->execute_query($sql, [$email]);
$user = $res->fetch_all(MYSQLI_ASSOC)[0];
var_dump($user);
$user['name'] = $user['first_name'] . ' ' . $user['last_name'];
$default = './assets/blank-profile.png';

$sql = "";
?>


<div class="profile-page">
    <div class="profile">
        <div class="profile-head">
            <div class="avatar">
                <img src="<?php if (isset($user['photo']) && $user['photo'] != "") {
                                echo $user['photo'];
                            } else {
                                echo $default;
                            } ?>" />
            </div>
            <div class="profile-info">
                <div class="profile-name"><?php echo $user['name']; ?></div>
                <div class="profile-type"><?php if (isset($user['type']) && $user['type'] === 'rm') {
                                                echo 'Relation Manager';
                                            } else if (isset($user['type']) && $user['type'] === 'client') {
                                                echo 'Client';
                                            } else if (isset($user['type']) && $user['type'] === 'admin') {
                                                echo 'Administrator';
                                            } ?></div>
                <div class="profile-phone"><?php if (isset($user['phone'])) {
                                                echo $user['phone'];
                                            } ?></div>
                <div class="profile-loc"><?php if (isset($user['loc'])) {
                                                echo $user['loc'];
                                            } ?></div>
                <div class="profile-email"><?php if (isset($user['email'])) {
                                                echo '<a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a>';
                                            } ?></div>
                <div class="rm"><?php if (isset($user['type']) && $user['type'] === 'client') {
                                    $sql = "SELECT users.id, users.first_name, users.last_name, users.email FROM `users` INNER JOIN client_rm ON client_id = ? AND users.id = client_rm.rm_id";
                                    $res = $mysqli->execute_query($sql, [$user['id']]);
                                    $rm = $res->fetch_assoc(MYSQLI_ASSOC)[0];
                                    echo '<a href="./user.php?u="' . $rm['email'] . '">' . $rm['first_name'] . ' ' . $rm['last_name'] . '</a>';
                                } ?></div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php' ?>