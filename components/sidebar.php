<?php 
if(isset($_SESSION["type"]) && $_SESSION["type"]==="admin"){
    echo '<ul class="sidebar-menu">
            <li><a href="./products.php">Products</a></li>
            <li><a href="./settings.php">Settings</a></li>
            <li><a href="./about.php">About</a></li>
            <li><a href="./creation.php">Submit an Idea</a></li>
        </ul>'; 
} else {
    echo '<ul class="sidebar-menu">
            <li><a href="./products.php">Products</a></li>
            <li><a href="./settings.php">Settings</a></li>
            <li><a href="./about.php">About</a></li>
        </ul>';
}