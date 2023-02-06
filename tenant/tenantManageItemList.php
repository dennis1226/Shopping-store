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
    <script type="text/javascript">
        function setValue(editGoodsID,editStockPrice,editRemainingStock) {
            hiddenElement1 = document.getElementById("editGoodsID");
            hiddenElement2 = document.getElementById("editStockPrice");
            hiddenElement3 = document.getElementById("editRemainingStock");
            hiddenElement1.value = editGoodsID;
            hiddenElement2.value = editStockPrice;
            hiddenElement3.value = editRemainingStock;
            document.getElementById("TenantManageItemData").submit();
        }
        function setDelValue(editGoodsID,editStatus) {
            hiddenElement1 = document.getElementById("editGoodsID");
            hiddenElement1.value = editGoodsID;
            hiddenElement2 = document.getElementById("editStatus");
            hiddenElement2.value = editStatus;
            document.getElementById("TenantManageItemData").submit();
        }

    </script>

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
<h1>Tenant Item Management</h1>
<form action="" method="post" id="TenantManageChooseBranch">
    <div class="BranchShop">
        <label for="Branch">Branch Shop:</label>
        <select name="ShopID" id="ShopID">
            <option>All</option>
            <?php
            require("../src/php/conn.php");
            $sql = "SELECT * FROM shop ";
            $rs = mysqli_query($conn, $sql);
            while ($rc = mysqli_fetch_assoc($rs)) {
                extract($rc);
                echo "<option>$address</option>";
            }
            ?>
        </select>
        <input type="submit" name="filtershopID" value="check"/>
    </div>
    <input type="button" value="Create Item" class="Add" onclick="location.href='tenantCreateItem.php'">
</form>
<br>
<form action="tenantManageItemList.php" method="post" id="TenantManageItemData">
    <input name="editGoodsID" type="hidden" id="editGoodsID">
    <input name="editStockPrice" type="hidden" id="editStockPrice">
    <input name="editRemainingStock" type="hidden" id="editRemainingStock">
    <input name="editStatus" type="hidden" id="editStatus">
    <table width="500" border="1">
        <tr>
            <td colspan="9">
                <h1>Goods Management</h1>
            </td>
        </tr>
        <tr>
            <td>showcaseID</td>
            <td>Goods ID</td>
            <td>Name</td>
            <td>Price</td>
            <td>Quantity</td>
            <td>Status</td>
            <td>Edit</td>
            <td>Update Status</td>
        </tr>

        <?php
        //for choose the shop
        if (isset($_POST['filtershopID'])) {
            $ShopID = $_POST['ShopID'];
            //gen table and change column
            require("../src/php/conn.php");
            $loginID = $_SESSION['loginID'];
            $sql = "SELECT * FROM goods
                NATURAL JOIN showcase
                NATURAL JOIN shop
                WHERE tenantID = '$loginID'
                ";
            if ($ShopID != 'All')
                $sql .= "AND address = '$ShopID'";
        } else {
            $loginID = $_SESSION['loginID'];
            $sql = "SELECT * FROM goods
                NATURAL JOIN showcase
                NATURAL JOIN shop
                WHERE tenantID = '$loginID'
                ";
        }
        require("../src/php/conn.php");
        $rs = mysqli_query($conn, $sql);
        while ($rc = mysqli_fetch_assoc($rs)) {
            extract($rc);
            if ($status == 1){
                $status = "Available";
                $removeButtonValue = "Remove";
                $statusID = 1;
            }
            if ($status == 2) {
                $status = "Unavailable";
                $removeButtonValue = "Add";
                $statusID = 2;
            }
            echo "<tr>
                    <td>$showcaseID</td>
                    <td>$goodsID</td>
                    <td>$goodsName</td>
                    <td><input type='text' size='1' value = '$stockPrice' name='stockPrice$goodsID'></td>
                    <td><input type='text' size='1' value = '$remainingStock' name='remainingStock$goodsID'></td>
                    <td>$status</td>
                    <td><input type='submit' value='Edit' name='Edit' onclick='setValue($goodsID,$stockPrice,$remainingStock)'></td>
                    <td><input type='submit' value='$removeButtonValue' name='Remove' onclick='setDelValue($goodsID, $statusID)'></td>
			    </tr>";
        }
        //for press the Edit button to update data
        if (isset($_POST['Edit'])) {
            extract($_POST);
            echo "<br>".var_dump($_POST);
            $goodsID = $_POST['editGoodsID'];
            if($_POST["stockPrice$goodsID"]!=$_POST['editStockPrice'])
                $stockPrice = $_POST["stockPrice$goodsID"];
            else
                $stockPrice = $_POST['editStockPrice'];
            if($_POST["remainingStock$goodsID"]!=$_POST["editRemainingStock"])
                $remainingStock = $_POST["remainingStock$goodsID"];
            else
                $remainingStock = $_POST["editRemainingStock"];
            echo "<br>".$stockPrice;

            $sql1 = "UPDATE goods
                    SET stockPrice = '$stockPrice', remainingStock = '$remainingStock' 
                    WHERE goodsID='$goodsID';";
            echo "<br>".$sql1;
            mysqli_query($conn, $sql1);
            $num = mysqli_affected_rows($conn);
            if ($num == 1) {
                echo "<script> alert('Update Successful'); </script>";
                echo "<meta http-equiv='Refresh' content='0'>";
            } else{
                echo "<script> alert('Update fail'); </script>";
                echo "<meta http-equiv='Refresh' content='0'>";
            }

        }
        ?>
        <?php
        if (isset($_POST['Remove'])) {
            if ($_POST['editStatus'] == 1){
                $updateStatus = 2;
                $printAlert ='Remove';
            }
            if ($_POST['editStatus'] == 2) {
                $updateStatus = 1;
                $printAlert ='Add';
            }
            extract($_POST);
            echo "<br>" . var_dump($_POST);
            $goodsID = $_POST['editGoodsID'];
            $sql1 = "UPDATE goods SET status = '$updateStatus' WHERE goodsID='$goodsID';";
            echo "<br>" . $sql1;
            mysqli_query($conn, $sql1);
            $num = mysqli_affected_rows($conn);
            if ($num == 1) {
                echo "<script> alert('$printAlert'+' Successful'); </script>";
                echo "<meta http-equiv='Refresh' content='0'>";
            } else {
                echo "<script> alert('$printAlert'+' Fail'); </script>";
                echo "<meta http-equiv='Refresh' content='0'>";
                echo mysqli_error($conn);
            }
        }
        ?>
    </table>
</form>
</body>
</html>