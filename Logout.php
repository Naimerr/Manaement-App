<?php
class Logout{
    function __construct() {
        session_start();
    }

    public function logoutAction() {
        session_destroy();
        header('location: Login.php');
    }
}

$logoutObj = new Logout();

$logoutObj = $logoutObj->logoutAction();


?>
