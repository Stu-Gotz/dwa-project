<?php
/* because this PHP file is included in every other php file, starting a session
    is only needed once.
    it also allows for the application to only need the <head> tags one time, which means all
    stylesheets, javascript files, or other links are available to all php files that include this one
 */
session_start();

// We learned about this in class,  $_SESSION is a built-in function
//that I don't really know deep down, but basically it allows access to the $_SESSION
//variable which we can use throughout the page to inject data as an object with php 
$_SESSION['errors'] = array(); 

//This needs to be replaced with real data
$envs = include './env.php';

$mysqli = new mysqli($envs['DB_HOST'], $envs['DB_USER'], $envs['DB_PASS'], $envs['DB_NAME']);

//This will be used after zero-ing out a session (after a logout, for instance)
//tl;dr it connects to the database, and sets user data in $_SESSION.
//Because this is only called through logging in, it allows these values to be
//accessed on pages such as their profile and others.
function begin_session($mysqli, $login)
{
    //if theres no database connection, send an error that the application failed to connect
    if ($mysqli->connect_error) {
        die('Failed to connect to database: ' . $mysqli->connect_error);
    } elseif (filter_var($login, FILTER_SANITIZE_EMAIL)) {
        // if we connect and the $login variable is validated
        //query the db to find the matching login information.
        $res = $mysqli->execute_query('SELECT * FROM `users` WHERE email=?', [htmlspecialchars($login)]);
        $user = $res->fetch_assoc();

        //set all the corresponding $_SESSION variables so they can be used during the duration of the 
        //users session. All are passed in htmlspecialchars() for security.
        $_SESSION['userid'] = htmlspecialchars($user['id']);
        $_SESSION['name'] = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
        $_SESSION['type'] = htmlspecialchars($user['type']);
        $_SESSION['email'] = htmlspecialchars($user['email']);
        $_SESSION['loc'] = htmlspecialchars($user['location']);
        $_SESSION['photo'] = htmlspecialchars('./assets/' . $user['photo']);
        $_SESSION['phone'] = htmlspecialchars($user['phone']);
        $_SESSION['id'] = htmlspecialchars($user['id']);
        $_SESSION['password'] = htmlspecialchars($user['password']);

        return $mysqli;
    } 
}

//function to delete a product from the database
//prevents having to rewrite the if statement at multiple places
function delete_product($id, $mysqli)
{
    if (filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $sql = "DELETE FROM `products` WHERE id=?";
        $mysqli->execute_query($sql, [$id]);
        $sql_ = "DELETE FROM `client_prod` WHERE prod_id=?";
        $mysqli->execute_query($sql_, [$id]);
    }
}

// //function to validate text that never got implemented and isn't complete.
// //was making it then discovered filter_var which is good enough for the purposes of this
// //assignment
// function validate_text($field, $text, $c = FALSE)
// {
    
//     $errorMsg = "";
//     if ($field === 'password') {

//         if ($c) {
//             if (!($field[0] === $field[1])) {
//                 $errorMsg = "Passwords do not match.";
//                 return  $errorMsg;
//             }
//         }
//         if (strlen($_POST["password"]) <= 8) {
//             $errorMsg = "Your Password Must Contain At Least 8 Characters!";
//             return $errorMsg;
//         } elseif (!preg_match("#[0-9]+#", $text)) {
//             $errorMsg = "Your Password Must Contain At Least 1 Number!";
//             return $errorMsg;
//         } elseif (!preg_match("#[A-Z]+#", $text)) {
//             $errorMsg = "Your Password Must Contain At Least 1 Capital Letter!";
//             return $errorMsg;
//         } elseif (!preg_match("#[a-z]+#", $text)) {
//             $errorMsg = "Your Password Must Contain At Least 1 Lowercase Letter!";
//             return $errorMsg;
//         }
//     }

//     if ($field === 'name') {
//         if (!preg_match("/^[a-zA-Z ]*$/", $text)) {
//             $errorMsg = "Only letters and white space allowed";
//             return $errorMsg;
//         }
//         return $errorMsg;
//     }

//     if ($field === 'number') {
//         if (!preg_match("/[0-9]*/", $text)) {
//             $errorMsg = "Only numbers are allowed.";
//             return $errorMsg;
//         }
//         return $errorMsg;
//     }
// }
?>

<!-- after we do all the functions and set all the data, -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI Inc.</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- Material Icon(s) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Custom css -->
    <link rel="stylesheet" href="./style/main.css">
    <!-- jQuery  -->
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
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
                    <h2 class="title">Smarter Investing Inc.</h2>
                <!-- If they are logged in, it welcomes the user and gives a link to their profile. Note the $_SESSION variable and how that doesn't change -->
                <div class="login-area"><?php if (isset($_SESSION["name"]) && filter_var($_SESSION['name'], FILTER_SANITIZE_SPECIAL_CHARS)) {
                                            echo '<p style="margin-bottom:7px;">Welcome <a href="./profile.php">' . htmlspecialchars($_SESSION["name"]) . '</p></a><a href="./logout.php" class="login"> Logout</a>';
                                        } else {
                                            echo '<a href="./login.php" class="login">Log In</a>';
                                        } ?></div>
            </div>
        </nav>
    </header>