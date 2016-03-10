<?php
return array(
    "oauth" => array(
        "base_url" => "https://protostar.adamwilliams.eu",
    ),
    "cookies" => array(
        "path" => "/",
        "name" => "SplitBillSession"
    ),
    "public_nav_left" => array(
        "Home" => "index.php", "Groups" => "groups.php",
        "Bills" => "bills.php", "Settings" => "settings.php"
    ),
    "public_nav_right" => array(
        "Register" => "register.php", "Login" => "login.php"
    ),
    "sqlite" => array(
        // put outside webroot..
        "path" => "splitter.sqlite3"
    )
);
