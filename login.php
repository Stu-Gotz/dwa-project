<?php include 'header.php';
// require_login();
// if (isset($_POST['submit'])) {
//     $inputs = [];
//     $errors = [];

//     [$inputs, $errors] = filter($_POST, [
//         'username' => 'string | required',
//         'password' => 'string | required'
//     ]);

//     if ($errors) {
//         redirect_with('login.php', ['errors' => $errors, 'inputs' => $inputs]);
//     }

//     // if login fails
//     if (!login($inputs['username'], $inputs['password'])) {

//         $errors['login'] = 'Invalid username or password';

//         redirect_with('login.php', [
//             'errors' => $errors,
//             'inputs' => $inputs
//         ]);
//     }
// }

$message = "";
if (count($_POST) > 0) {

    $username = htmlspecialchars($_POST['username']);

    $sql = "SELECT * FROM `users` WHERE email = ?";
    $res = $mysqli->execute_query($sql, [$username]);
    $data = $res->fetch_all(MYSQLI_ASSOC)[0];

    $valid = password_verify(htmlspecialchars($_POST['password']), $data['password']);
    if ($valid) {
        begin_session($mysqli, $username);
    } else {
        echo '<script type="text/javascript">alert("Invalid Username or Password!")</script>';
    }
}
if (isset($_SESSION["id"])) {
    header("Location: ./loginsuccess.php");
}
?>

<!-- <h1 style="font-family: sans-serif; color: darkgray;">You have logged in as John Doe.</h1> -->
<!-- login form -->
<!-- "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" -->
<div class="login-wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
        <!-- <form action="./autologin.php" method="POST"> -->

        <h1>Login</h1>
        <div class="login-user">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= $inputs['username'] ?? '' ?>">
            <small><?= $errors['username'] ?? '' ?></small>
        </div>
        <div class="login-password">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <small><?= $errors['password'] ?? '' ?></small>
        </div>
        <div class="login-submit">
            <button class="btn btn-submit" name="submit" type="submit">Log In</button>
            <!-- client can click register if their account is not registered to login -->
            <a href="./register.php">Register</a>
        </div>
    </form>
    <p>Interested in investing with us? <a href="register.php">Register here!</a></p>
</div>



<?php //include 'footer.php'; 
?>