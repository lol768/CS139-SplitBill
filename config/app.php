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
        "Bills" => "bills.php"
    ),
    "public_nav_right" => array(
        "Register" => "register.php", "Login" => "login.php"
    ),
    "sqlite" => array(
        // put outside webroot..
        "path" => "splitter.sqlite3"
    ),
    "masquerade_ips" => array("127.0.0.1", "172.26.4.214")
);
