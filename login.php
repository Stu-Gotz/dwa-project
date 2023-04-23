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

/* 
Determines validity of login credentials through the use of filter_var functions.
To be extra secure, $_POST variables are still passed through htmlspecialchars().
$data is an array of user data, retrieved by matching the email address with entries
in the database. If one field is incorrect, an alert will notify the user.
Users are not told which field is incorrect, to prevent attackers from narrowing down
attack angles.
Upon successfully loggin in, the user will be sent to a login success page before being redirected to their profile.
*/
if (count($_POST) > 0) {
    if (filter_var($_POST['username'], FILTER_SANITIZE_EMAIL)) {
        $username = htmlspecialchars($_POST['username']);

        if (filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS)) {
            $password = htmlspecialchars($_POST['password']);

            $sql = "SELECT * FROM `users` WHERE email = ?";
            $res = $mysqli->execute_query($sql, [$username]);
            $data = $res->fetch_all(MYSQLI_ASSOC)[0];

            $valid = password_verify($password, $data['password']);
            if ($valid) {
                begin_session($mysqli, $username);
                $_SESSION['errors']['login_error'] = FALSE;
                header("Location: ./loginsuccess.php");
            } else {
                $_SESSION['errors']['login_error'] = 'Invalid Username or password.';
                header('Location: ./login.php');
            }
        }
    }
}

?>


<div class="login-wrapper">
    <?php if (isset($_SESSION['errors']['login_error'])) : ?>
        <div class="error"> <?php echo htmlspecialchars($_SESSION['errors']['login_error']) ?></div>
    <?php endif ?>
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