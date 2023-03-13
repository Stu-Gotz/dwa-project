<?php
session_start();

$password = 'password';
$hashpass = password_hash($password, PASSWORD_DEFAULT);
// var_dump($hashpass);

function begin_session()
{
    $envs = include 'env.php';
    $mysqli = new mysqli($envs['DB_HOST'], $envs['DB_USER'], $envs['DB_PASS'], $envs['DB_NAME']);
    if ($mysqli->connect_error) {
        die('Failed to connect to database: ' . $mysqli->connect_error);
    }
    $user = [
        'f_name' => "John",
        'l_name' => "Doe",
        'location' => "London, UK",
        'email' => "john.doe@example.com",
        'phone' => "+445321553099",
        'pass' => "password",
        'photo' => "1.jpg",
        'type' => "RM"
    ];

    $phone = str_replace(' ', '', $user['phone']);
    $phone = str_replace('-', '', $phone);

    $_SESSION['name'] = $user['f_name'] . ' ' . $user['l_name'];
    $_SESSION['type'] = $user['type'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['loc'] = $user['location'];
    $_SESSION['photo'] = './assets/' . $user['photo'];
    $_SESSION['phone'] = $phone;

    if ($_SESSION['type'] === 'admin' or $_SESSION['type'] === 'RM') {
        echo 'hello';
    }

    return $mysqli;
}

$_SESSION['password'] = $hashpass;


echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- Material Icon(s) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Custom css -->
    <link rel="stylesheet" href="./style/main.css">
</head>

<body>
    <!-- Navigation Component -->
    <header class="heading">
        <nav class="navbar">
            <div class="navcontainer">
                <a href="./index.php" class="home">
                    <!-- Google Material Home Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48" class="home-icon">
                        <path d="M11 39h7.5V26.5h11V39H37V19.5L24 9.75 11 19.5Zm-3 3V18L24 6l16 12v24H26.5V29.5h-5V42Zm16-17.65Z" />
                    </svg>
                </a>
                <h2 class="title">Smarter Investing Inc.</h2>' ?>
                <?php if (isset($_SESSION["name"])) {
                    echo '<div class="login-area"><p style="margin-bottom:7px;">Welcome <a href="./profile.php">' . $_SESSION["name"] . '</p></a><a href="./logout.php" class="login"> Logout</a></div>';
                } else {
                    echo '<a href="./login.php" class="login">Log In</a>';
                } ?>
            <?php echo '</div>
        </nav>
    </header>';
            ?>