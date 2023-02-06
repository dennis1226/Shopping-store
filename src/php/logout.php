<?php
session_start();
unset($_SESSION["isLogin"]);
unset($_SESSION["loginID"]);
unset($_SESSION["type"]);
unset($_SESSION['shoppingCart']);
header("Location: ../../index.php");

?>
