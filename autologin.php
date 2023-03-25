<?php

include 'header.php';
begin_session($mysqli, "admin@web.site");
header('Location: ./loginsuccess.php');
