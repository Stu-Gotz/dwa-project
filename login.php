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
?>
<!-- <h1 style="font-family: sans-serif; color: darkgray;">You have logged in as John Doe.</h1> -->
<!-- login form --> 
<!-- "<?php echo $_SERVER['PHP_SELF']; ?>" -->
<form action="autologin.php" method="POST">
    <h1>Login</h1>
    <div>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?= $inputs['username'] ?? '' ?>">
        <small><?= $errors['username'] ?? '' ?></small>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <small><?= $errors['password'] ?? '' ?></small>
    </div>
    <div>
        <input name="submit" value="Log In" type="submit" />
        <!-- client can click register if their account is not registered to login -->
        <a href="register.php">Register</a>
    </div>
</form>

<p>Interested in investing with us? <a href="register.php">Register here!</a></p>
<?php include 'footer.php'; ?>