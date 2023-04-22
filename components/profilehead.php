<div class="profile-head">
    <div class="avatar"><img src="<?php if (isset($_SESSION['photo'])) {
                                    echo $_SESSION['photo'];
                                } else {
                                    echo './assets/blank-profile.png';
                                } ?>" alt="profile image for <?php echo $_SESSION['name']; ?>" srcset="" /></div>
    <div class="profile-info">
        <div class="profile-data profile-name"><?php echo $_SESSION['name']; ?></div>
        <div class="profile-data profile-type"><?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'rm') {
                                    echo 'Relation Manager';
                                    } else if (isset($_SESSION['type']) && $_SESSION['type'] === 'client') {
                                    echo 'Client';
                                    } else if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') {
                                    echo 'Administrator';
                                    } ?></div>
        <div class="profile-data profile-phone"><?php if (isset($_SESSION['phone'])) {
                                        echo $_SESSION['phone'];
                                    } ?></div>
        <div class="profile-data profile-loc"><?php if (isset($_SESSION['loc'])) {
                                    echo $_SESSION['loc'];
                                    } ?></div>
        <div class="profile-data profile-email"><?php if (isset($_SESSION['email'])) {
                                        echo '<a href="mailto:' . $_SESSION['email'] . '">' . $_SESSION['email'] . '</a>';
                                    } ?></div>
        <div class="profile-data client-rm"><?php if(isset($_SESSION['type']) && $_SESSION['type'] === 'client'){
            $sql = "SELECT users.email, users.first_name, users.last_name FROM `users` INNER JOIN `client_rm` ON client_id = ? AND users.id = client_rm.rm_id";
            $res = $mysqli->execute_query($sql, [$_SESSION['userid']]);
            $data = $res->fetch_assoc();
            if(count($data) > 0){
            echo 'Managed by: <a href="./user.php?email=' . $data['email'] . '">' . $data['first_name'] . ' ' . $data['last_name'] . '</a>';
            } else {
                echo 'Currently unassigned to a relationship manager. Please contact <a href="mailto:admin@investing.com">the administrator</a>.';
            }
        } ?></div>
        </div>
</div>