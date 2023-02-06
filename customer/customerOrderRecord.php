<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="../src/style/Main.css">
    <link rel="stylesheet" href="../src/style/customerOrderRecord.css">
    <?php
    session_start();
    if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
        echo "<script> alert('Please Login First'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
    } else if ($_SESSION['type'] == "tenant") {
        echo "<script> alert('Please Login As Customer'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../index.php'>";
    }
    ?>
</head>

<body>


<form Class="box" action="accountUpdateForm.php" method="post">
    <ul id="menu">
        <li><a href="customerItemList.php">Shopping</a></li>
        <li><a href="customerOrderRecord.php">Order Record</a></li>
        <li><a href="customerAccountInfo.php">Profile</a></li>
        <li><a href="../src/php/logout.php">LogOut</a></li>
    </ul>

    <!--                  id user is tenant
<form Class="box" action="accountUpdateForm.html" method="post" id="accountUpdateForm">
<ul id="menu">
<li><a href="/AccountInfo.html">Profile</a></li>
<li><a href="/ItemList.html">Shopping</a></li>
<li><a href="/tenant//tenantOrderRecord.html">Order</a></li>
<li><a href="/index.html">LogOut</a></li>
</ul>
-->
    <br>
    <br>
    <h1 id="registerHeader">Customer Order Record</h1>
    <?php
    require("../src/php/conn.php");
$loginEmail = $_SESSION['loginID'];
    $sql = "SELECT * FROM orders
                NATURAL JOIN shop
                WHERE orders.shopID = shop.shopID AND orders.customerEmail = '$loginEmail'
                ORDER BY orderID";
    echo $sql;
    $rs = mysqli_query($conn, $sql);
    echo mysqli_error($conn);

    while ($rc = mysqli_fetch_assoc($rs)) {
        $totalPrice = 0;
        $orderID = $rc['orderID'];
        $orderDateTime = $rc['orderDateTime'];
        $shopID = $rc['shopID'];
        $address = $rc['address'];
        $status = $rc['status'];
        echo "
			<table>
				<tr>
					<th colspan='2'>Order ID: $orderID</th>
					<th colspan='2'>Order Date: $orderDateTime</th>
				</tr>

				<tr>
					<td colspan='2'>ShopID: $shopID</td>
					<td colspan='2'>$address</td>
				</tr>
				<tr>
                    <td>Goods ID</td>
                    <td>Goods Name</td>
                    <td>Quantity</td>
                    <td>Selling Price</td>
                </tr>";
        $sql1 =
            "SELECT * FROM orderitem
                NATURAL JOIN goods
                WHERE orderitem.orderID = $orderID
                ";
        $rs1 = mysqli_query($conn, $sql1);
        while ($rc1 = mysqli_fetch_assoc($rs1)) {
            $goodsID = $rc1['goodsID'];
            $goodsName = $rc1['goodsName'];
            $quantity = $rc1['quantity'];
            $sellingPrice = $rc1['stockPrice'];
            echo "
				<tr>
					<td>$goodsID</td>
					<td>$goodsName</td>
					<td>$quantity</td>
					<td>$sellingPrice</td>
				</tr>";
            $totalPrice += ($sellingPrice*$quantity);
        }
        switch ($status) {
            case 1:
                $status = "Delivery";
                break;
            case 2:
                $status = "Awaiting";
                break;
            default:
                $status = "Completed";
        }
        if ($status == 1)
            $status = "Delivery";
        echo "
				<tr>
					<td colspan='4''>--------------------------------------------------------------------------------------</td>
				</tr>
				<tr>
					<td colspan='3'>Total Price:</td>
					<td colspan='1'>$totalPrice</td>
				</tr>
				<tr>
					<td colspan='3'>Status:</td>
					<td colspan='1'>$status</td>
				</tr>
			</table>";
    }
    ?>

</form>
</body>
</html>