<?php

include 'header.php';
begin_session($mysqli, "john.doe@example.com");
header('Location: ./index.php');