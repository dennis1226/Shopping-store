<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="../src/style/Main.css">
    <link rel="stylesheet" href="../src/style/tenantCreateItem.css">
    <?php
    session_start();
    require("../src/php/conn.php");
    $loginID = $_SESSION['loginID'];
    if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
        echo "<script> alert('Please Login First'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
    } else if ($_SESSION['type'] == "customer") {
        echo "<script> alert('Please Login As Tenant'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
    }
    ?>
    <script type="text/javascript">
        function setValue(orderID) {
            hiddenElement1 = document.getElementById("orderID");
            hiddenElement1.value = orderID;
            document.getElementById("TenantOrderRecord").submit();
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
<h1>Tenant Order Record</h1>

<br>
<form action="" method="post" id="TenantOrderRecord">
    <input name='orderID' type='hidden'id='orderID'>
    <table width="500" border="1">
        <tr>
            <td>OrderID</td>
            <td>OrderDate</td>
            <td>OrderStatus</td>
            <td>CustomerID</td>
            <td>CustomerName</td>
            <td>Shop address</td>
            <td>GoodsID</td>
            <td>GoodsName</td>
            <td>Quantity</td>
            <td>EachGoodsPrice</td>
            <td>TotalPrice</td>
            <td>Delete Record</td>
        </tr>
        <?php
        include("../src/php/conn.php");

        $sql =
            "SELECT * FROM tenant
                NATURAL JOIN showcase
                NATURAL JOIN shop
                NATURAL JOIN goods
                NATURAL JOIN orderitem, orders
                NATURAL join customer 
                WHERE tenant.tenantID = '$loginID'
                AND orders.orderID = orderitem.orderID
                AND orders.customerEmail = customer.customerEmail
                ORDER BY orderdatetime DESC
            ";
        $rs = mysqli_query($conn, $sql);
        while ($rc = mysqli_fetch_array($rs)) {
            extract($rc);
            $totalprice = $quantity*$sellingPrice;
            switch ($status) {
                case 1: $status = "Delivery";
                break;
                case 2: $status = "Awaiting";
                break;
                default:$status = "Completed";
            }
            echo "<tr>
				            <td>$orderID</td>
				            <td>$orderDateTime</td>
				            <td>$status</td>
				            <td>$customerEmail</td>
				            <td>$firstName $lastName</td>
				            <td>$address</td>
				            <td>$goodsID</td>
				            <td>$goodsName</td>
				            <td>$quantity</td>
				            <td>$sellingPrice</td>
				            <td>$totalprice</td>
			            ";
            //check the Remaining quantity, delivery , item just buy one
            $checkRecordNum =
                "SELECT * FROM orders
                NATURAL join orderitem 
                Where orders.orderID = '$orderID'";
            $checkwaiting = mysqli_query($conn,$checkRecordNum);
            $check = mysqli_num_rows($checkwaiting);
            if($status == "Delivery" && $check == 1 && $quantity > $remainingStock)
			    echo "
                    <th><input type='submit' name='delete' value='Delete Record' onclick='setValue($orderID)'></th>
                     </tr>   
                ";
        }
        ?>
        <?php
        if(isset($_POST['delete'])){
            extract($_POST);
            $orderID = $_POST['orderID'];
            echo $orderID;
            $sql3 = "DELETE FROM orderitem WHERE orderID ='$orderID';";
            $sql2 = "DELETE FROM orders WHERE orderID ='$orderID';";
            mysqli_query($conn, $sql3);
            mysqli_query($conn, $sql2);
            $num = mysqli_affected_rows($conn);
            if ($num == 1) {
                echo "<script> alert('Delete Successful'); </script>";
                echo "<meta http-equiv='Refresh' content='0'>";
            } else{
                echo "<script> alert('Delete fail'); </script>";
                echo "<meta http-equiv='Refresh' content='0'>";
            }
        }
        ?>
    </table>
</form>
</body>
</html>