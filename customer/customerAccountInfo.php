<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="../src/style/Main.css">
    <link rel="stylesheet" href="../src/style/customerAccountInfo.css">
    <?php
    session_start();
    ?>
    <?php
    if (isset($_POST['deleteAccout'])) {
        require("../src/php/conn.php");
        $loginID = $_SESSION['loginID'];
        $sql = "SELECT orderID FROM orders WHERE customerEmail='$loginID'";
        $rs = mysqli_query($conn,$sql);
        while ($rc=mysqli_fetch_assoc($rs)){
            $orderID = $rc['orderID'];
            $sql = "DELETE FROM orderitem WHERE orderID = '$orderID'";
            mysqli_query($conn,$sql);
        }
        $sql = "DELETE FROM orders WHERE customerEmail='$loginID'";
        mysqli_query($conn,$sql);
        echo mysqli_error($conn);
        $sql = "DELETE FROM customer WHERE customerEmail='$loginID'";
        if (mysqli_query($conn, $sql)) {
            $accountDeleted = true;
            echo "<script> alert('Delete Account Success'); </script>";
            unset($_SESSION["isLogin"]);
            unset($_SESSION["loginID"]);
            echo "<meta http-equiv='Refresh' content='0'url='../index.php'>";
        } else {
            echo "<script> alert('Delete Account Fail'); </script>";
            echo mysqli_error($conn);
        }
    }
    ?>

    <?php
    if($accountDeleted == false){
        if (isset($_POST['newFirstName']) || isset($_POST['newLastName']) || isset($_POST['newPassword']) || isset($_POST['newPhoneNumber'])) {
            require("../src/php/conn.php");
            $passwordChanged = false;
            $multiChanged = false;
            $loginID = $_SESSION['loginID'];
            $sql = "UPDATE customer SET";
            if ($_POST['newFirstName'] != "") {
                $newFirstName = $_POST['newFirstName'];
                $sql .= " firstName='$newFirstName'";
                $multiChanged = true;
            }

            if ($_POST['newLastName'] != "") {
                if($multiChanged){
                    $sql.= ",";
                }
                $newLastName = $_POST['newLastName'];
                $sql .= " lastName='$newLastName'";
                $multiChanged = true;
            }

            if ($_POST['newPassword'] != "") {
                if($multiChanged){
                    $sql.= ",";
                }
                $newPassword = $_POST['newPassword'];
                $sql .= " password='$newPassword'";
                $passwordChanged = true;
                $multiChanged = true;
            }

            if ($_POST['newPhoneNumber'] != "") {
                if($multiChanged){
                    $sql.= ", ";
                }
                $newPhoneNumber = $_POST['newPhoneNumber'];
                $sql .= " phoneNumber='$newPhoneNumber'";
            }

            $sql .= " WHERE customerEmail='$loginID'";
            if (mysqli_query($conn, $sql)) {
                if($passwordChanged){
                    echo "<script> alert('Update Success , Please Login Agina'); </script>";
                    unset($_SESSION["isLogin"]);
                    unset($_SESSION["loginID"]);
                }else
                    echo "<script> alert('Update Success'); </script>";
                echo "<meta http-equiv='Refresh' content='0'url='../index.php'>";
            } else {
                echo "<br>".$sql;
                echo "<script> alert('Update Fail, Please check all data has input'); </script>";
                echo mysqli_error($conn);
            }
        }
    }
    ?>


    <?php
        if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
            echo "<script> alert('Please Login'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
        } else if ($_SESSION['type'] == "tenant") {
            echo "<script> alert('Please Login As Customer'); </script>";
            echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
        }
    ?>

</head>

<body>


<form Class="box" action="customerAccountInfo.php" method="post" id="accountUpdateForm">
    <ul id="menu">
        <li><a href="customerItemList.php">Shopping</a></li>
        <li><a href="customerOrderRecord.php">Order Record</a></li>
        <li><a href="customerAccountInfo.php">Profile</a></li>
        <li><a href="../src/php/logout.php">LogOut</a></li>
    </ul>
    <br>
    <br>
    <h1 id="registerHeader">User Profile</h1>
    <h2>
        Enter new information to update your profile<br>
    </h2>
    <?php
    $loginID = $_SESSION['loginID'];
    include("../src/php/conn.php");
    $sql = "SELECT * FROM customer WHERE customerEmail='$loginID'";
    $rs = mysqli_query($conn, $sql);
    $rc = mysqli_fetch_assoc($rs);
    $firstName = $rc['firstName'];
    $lastName = $rc['lastName'];
    $password = $rc['password'];
    $phoneNumber = $rc['phoneNumber'];
    ?>
    <table>
        <tr>
            <th>Current Information</th>
            <th>New Information</th>
        </tr>
        <tr>
            <?php
            echo "<th><input type='text' value='$loginID' id='currentInfo' disabled></th>";
            ?>
            <th><input type="text" value="Email cannot be changed" id="currentInfo" disabled></th>
        </tr>
        <tr>
            <?php
            echo "<th><input type='text' value='$firstName' id='currentInfo' disabled></th>";
            ?>
            <th><input type="text" placeholder="FirstName" id="registerInput" name="newFirstName"></th>
        </tr>
        <tr>
            <?php
            echo "<th><input type='text' value='$lastName' id='currentInfo' disabled></th>";
            ?>
            <th><input type="text" placeholder="LastName" id="registerInput" name="newLastName"></th>
        </tr>
        <tr>
            <?php
            echo "<th><input type='password' value='$password' id='currentInfo' disabled></th>";
            ?>
            <th><input type="password" placeholder="Password" id="registerInput" name="newPassword"></th>
        </tr>
        <tr>
            <?php
            echo "<th><input type='tel' value='$phoneNumber' id='currentInfo' disabled></th>";
            ?>
            <th><input type="tel" placeholder="PhoneNumber" id="registerInput" name="newPhoneNumber"></th>
        </tr>
        <tr>
            <th><input type="submit" value="Update" id="registerInput"></th>
            <th><input type="reset" value="Reset"></th>
        </tr>
    </table>
    <input type="submit" value="Delete Account" id="registerInput" name="deleteAccout">
</form>
</body>
</html>