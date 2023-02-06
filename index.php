<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="src/style/index.css">

</head>

<body>
<?php
session_start();
require("src/php/conn.php");
if (isset($_POST['LoginID'])) {
    require("src/php/conn.php");
    $loginID = $_POST['LoginID'];
    $loginPassword = $_POST['LoginPassword'];
    if ($_POST['LoginType'] == "Login as Customer") {
        $type = "customer";
        $typeID = 'customerEmail';
    } else {
        $type = "tenant";
        $typeID = 'tenantID';
    }
    $sql = "SELECT * FROM $type";
    $rs = mysqli_query($conn, $sql);
    while ($rc = mysqli_fetch_assoc($rs)) {
        if ($loginID == $rc[$typeID] && $loginPassword == $rc['password']) {
            $_SESSION['isLogin'] = true;
            $_SESSION['loginID'] = $loginID;
            $_SESSION['type'] = $type;
            if ($type == "customer") {
                header('Location: ' . "customer/customerItemList.php");
            } else{
                header('Location: ' . "tenant/tenantManageItemList.php");
            }

        }
    }
}

//check the come back
if(isset($_SESSION["isLogin"])){
    $Email = $_SESSION['loginID'];
    if($_SESSION['type'] == "customer") {
        $findcusName = "SELECT * FROM customer WHERE customerEmail = '$Email'";
        $findingcus = mysqli_query($conn,$findcusName);
        $findItcus = mysqli_fetch_assoc($findingcus);
        extract($findItcus);
        echo "<script> alert('Welcome back,$lastName $firstName'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=customer/customerItemList.php'>";
    }
    else if($_SESSION['type'] == "tenant"){
        $findtenName = "SELECT * FROM tenant WHERE tenantID = '$Email'";
        $findingten = mysqli_query($conn,$findtenName);
        $findItten = mysqli_fetch_assoc($findingten);
        extract($findItten);
        echo "<script> alert('Welcome back,$name'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=tenant/tenantManageItemList.php'>";
    }
}


?>
<form Class="box" action="index.php" method="post" id="login">
    <h1 id="loginTitle">Cube Shop User Login</h1>
    <input type="text" placeholder="Customer Email/Tenant ID:" id="LoginID" name="LoginID">
    <input type="password" placeholder="Password" id="LoginPassword" name="LoginPassword">
    <input type="submit" value="Login as Customer" name="LoginType">
    <input type="submit" value="Login as Tenant" name="LoginType">
    <input type="button" value="Register Customer Account" onclick="location.href='registerForm.php'">
</form>
</body>
</html>