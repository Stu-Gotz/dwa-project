<?php

include 'header.php';
begin_session($mysqli, "admin@investing.com");
header('Location: ./loginsuccess.php');