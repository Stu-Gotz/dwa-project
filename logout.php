<?php

session_start();
session_destroy();
unset($_COOKIE['client']);
header('Location: ./login.php');
