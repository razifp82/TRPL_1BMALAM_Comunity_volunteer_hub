<?php
session_start();

$_SESSION = array();

setcookie('user', '', time() - 3600, '/');
setcookie('userType', '', time() - 3600, '/');

session_destroy();

header("location: /nuraga/index.php");
exit();
?>
