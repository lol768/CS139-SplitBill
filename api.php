<?php
require_once("src/bootstrap.php");
if (isset($_GET['action'])) {
    handleResponseForPage("ApiController", $_GET['action']);
}
