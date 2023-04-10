<div class="profile-head">
    <div class="avatar"><img src="<?php if (isset($_SESSION['photo'])) {
                                    echo $_SESSION['photo'];
                                } else {
                                    echo './assets/blank-profile.png';
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