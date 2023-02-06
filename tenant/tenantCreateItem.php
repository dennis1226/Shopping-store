<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="../src/style/Main.css">
    <link rel="stylesheet" href="../src/style/tenantCreateItem.css">
    <?php
    session_start();
    if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
        echo "<script> alert('Please Login First'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
    } else if ($_SESSION['type'] == "customer") {
        echo "<script> alert('Please Login As Tenant'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
    }
    ?>
    <?php
    if (isset($_POST['showCaseID'])) {
        require("../src/php/conn.php");
        $showCaseID = $_POST['showCaseID'];
        $goodsName = $_POST['goodsName'];
        $goodsPrice = $_POST['goodsPrice'];
        $goodsQuantity = $_POST['goodsQuantity'];
        $status = '1';
        $sql = "SELECT goodsID FROM goods";
        $rs = mysqli_query($conn, $sql);
        $goodsID = mysqli_num_rows($rs) + 1;
        mysqli_free_result($rs);
        $sql = "INSERT into `goods`(`goodsID`,`showcaseID`,`goodsName`,`stockPrice`,`remainingStock`,`status`) 
VALUES ('" . $goodsID . "','" . $showCaseID . "','" . $goodsName . "','" . $goodsPrice . "','" . $goodsQuantity . "','" . $status . "')";
        if (mysqli_query($conn, $sql)) {
            echo "<script> alert('Create New Item Success'); </script>";
            header('Location: ' . "tenantManageItemList.php");
        } else {

        }
    }
    ?>
</head>
<body>
<ul id="menu">
    <li><a href="tenantCreateItem.php">Launch New Good</a></li>
    <li><a href="tenantManageItemList.php">Goods Management</a></li>
    <li><a href="tenantOrderRecord.php">Order Management</a></li>
    <li><a href="../src/php/logout.php">LogOut</a></li>
</ul>

<br>
<br>
<h1>Tenant Create Item</h1>
<h2>
    Please fill in this form to create an Item
</h2>
<form action="tenantCreateItem.php" class="CreateItem" method="post" id="CreateItem">
   <div id="showcaselabel">
     <label for="showcase" >Showcase :</label>
     <select id="showCaseID" name="showCaseID">
    <?php
    require("../src/php/conn.php");
    $loginID = $_SESSION['loginID'];
    echo $loginID;
    $sql = "SELECT * FROM showcase WHERE tenantID = '$loginID'";
    $rs = mysqli_query($conn, $sql);
    while ($rc = mysqli_fetch_assoc($rs)) {
        extract($rc);
        echo "<option>$showcaseID</option>";
    }
    ?>
    </select>
     
   </div>
    
    
    <br>
    <input type="text" placeholder="Goods Name" id="registerInput" name="goodsName" required="required">
    <br>
    <input type="text" placeholder="Price" id="registerInput" name="goodsPrice" required="required">
    <br>
    <input type="text" placeholder="Quantity" id="registerInput" name="goodsQuantity" required="required">
    <br>
    <table>
        <tr>
            <th>
                <input type="button" value="Back" class="Back"
                       onclick="location.href='/tenant/tenantManageItemList.html'">
            </th>
            <th>
                <input type="reset" value="Reset">
            </th>
            <th>
                <input type="submit" value="Create" class="createItem">
            </th>
        </tr>
    </table>
</form>
</body>
</html>