<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="src/style/register.css">
</head>
<?php
if (isset($_POST['LoginID'])) {
    require("src/php/conn.php");
    $loginIDD = $_POST['LoginID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['LoginPassword'];
    $phoneNumber = $_POST['phoneNumber'];
    $check = true;
    $checksameaccountname = "SELECT * FROM customer";
    $checking = mysqli_query($conn,$checksameaccountname);
    while($checked = mysqli_fetch_assoc($checking)){
        extract($checked);
        if($customerEmail == $loginIDD) {
            echo "<script> alert('Register fail. The customerID is same'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=registerForm.php'>";
            return $check = false;
        }
    };
    $loginIDD = $_POST['LoginID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['LoginPassword'];
    $phoneNumber = $_POST['phoneNumber'];
    if($check){
        $sql = "INSERT into `customer`(`customerEmail`,`firstName`,`lastName`,`password`,`phoneNumber`) 
        VALUES ('" . $loginIDD . "','" . $firstName . "','" . $lastName . "','" . $password . "','" . $phoneNumber . "')";
        if (mysqli_query($conn, $sql)) {

            echo "<script> alert('Register Success. You can login using your new account'); </script>";
            var_dump($sql);
            echo $loginIDD."<br>";
            echo $firstName."<br>";
            echo $lastName."<br>";
            echo $password."<br>";
            echo $phoneNumber."<br>";

            echo "<meta http-equiv='Refresh' content='0;URL=index.php'>";
        } else {

        }
    }
}
?>
<body>
<form Class="box" action="registerForm.php" method="post" id="registerForm">
    <h1 id="registerHeader">Register Customer Account</h1>
    <h2>
        Please fill in this form to create an account<br><br>
    </h2>
    <input type="email" placeholder="Email Address" id="registerInput" name="LoginID" required>
    <input type="text" placeholder="FirstName" id="registerInput" name="firstName" required>
    <input type="text" placeholder="LastName" id="registerInput" name="lastName" required>
    <input type="password" placeholder="Password" id="registerInput" name="LoginPassword" required>
    <input type="tel" placeholder="PhoneNumber" id="registerInput" name="phoneNumber" required>
    <br>
    <th>
        <input type="submit" value="Register" id="registerInput">
        <input type="reset" value="Reset">
    </th>
    <input type="button" value="Back" id="btnBackLogin" onclick="location.href='index.php'">
</form>
</body>
</html>