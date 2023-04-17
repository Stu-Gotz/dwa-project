<?php

include 'header.php';
// admin@investing.com john.doe@example.com sally.example@gmail.com
begin_session($mysqli, "admin@investing.com");
header('Location: ./loginsuccess.php');
