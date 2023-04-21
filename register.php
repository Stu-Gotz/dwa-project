<?php
include './header.php';
require_once './connection.php';
require_once './authorisation.php';
require_once './validation.php';
require_once './helpers.php';
require_once './filter.php';
require_once './flash.php';
require_once './sanitization.php';

flash() ?>
<div class="reg-wrapper">
    <form class="reg-form" action="./reg.php" method="post">

        <h1>Sign Up</h1>

        <div class="reg-area">
            <label for="username">First Name: </label>
            <input type="text" name="first_name" id="firstname" value="">
        </div>

        <div class="reg-area">
            <label for="username">Last Name: </label>
            <input type="text" name="last_name" id="lastname" value="">
        </div>

        <div class="reg-area">
            <label for="email">Email: </label>
            <input type="email" name="email" id="email" value="">
            <small><?= $errors['email'] ?? '' ?></small>
        </div>

        <div class="reg-area">
            <label for="password">Password: </label>
            <input type="password" name="password" id="password" value="">
            <small><?= $errors['password'] ?? '' ?></small>
        </div>

        <div class="reg-area">
            <label for="password2">Confirm password: </label>
            <input type="password" name="password2" id="password2" value="">
            <small><?= $errors['password2'] ?? '' ?></small>
        </div>

        <div class="reg-area">
            <label for="agree">
                <input type="checkbox" name="agree" id="agree" value="checked" /> I agree with the <a href="#" title="term of services">terms and conditions.</a>
            </label> <small><?= $errors['agree'] ?? '' ?></small>

        </div>

        <button type="submit" name="submit" class="btn btn-submit">Register</button>
        <div>Already a member? <a href="./login.php">Login here</a></div>

    </form>
</div>