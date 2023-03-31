<?php

include 'header.php';
// admin@investing.com john.doe@example.com sally.example@gmail.com
begin_session($mysqli, "john.doe@example.com");
header('Location: ./loginsuccess.php');
