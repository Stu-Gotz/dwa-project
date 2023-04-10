<div class="avatar">
    <img src="<?php if (isset($user['photo']) && $user['photo'] != "") {
                    echo './assets/' . $user['photo'];
                } else {
                    echo './assets/blank-profile.png';
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

    <div class="relation"> <?php if (isset($user['type']) && $user['type'] === 'client') {
                        $sql = "SELECT users.id, users.first_name, users.last_name, users.email FROM `users` INNER JOIN client_rm ON client_id = ? AND users.id = client_rm.rm_id";
                        $res = $mysqli->execute_query($sql, [$user['id']]);
                        $rm = $res->fetch_all(MYSQLI_ASSOC)[0];

                        echo 'Manager: <a href="' . $_SERVER['PHP_SELF'] . '?email=' . $rm['email'] . '">' . $rm['first_name'] . ' ' . $rm['last_name'] . '</a>';
                    } ?></div>
</div>    
