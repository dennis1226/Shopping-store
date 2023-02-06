<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HKCS</title>
    <link rel="stylesheet" href="../src/style/Main.css">
    <link rel="stylesheet" href="../src/style/customerItemList.css">
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
    <?php
    include "../src/php/conn.php";
    if (isset($_POST["addToCart"])) {
        if (isset($_SESSION["shoppingCart"])) {
            $item_array_id = array_column($_SESSION["shoppingCart"], "goodsID");
            if (!in_array($_GET["id"], $item_array_id)) {
                $count = count($_SESSION["shoppingCart"]);
                $item_array = array(
                    'goodsID' => $_GET["id"],
                    'item_name' => $_POST["hidden_name"],
                    'item_price' => $_POST["hidden_price"],
                    'item_quantity' => $_POST["quantity"]
                );
                $_SESSION["shoppingCart"][$count] = $item_array;
            } else {
                echo '<script>alert("Goods Already In Cart")</script>';
                echo '<script>window.location="customerItemList.php"</script>';
            }
        } else {
            $item_array = array(
                'goodsID' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'item_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"]
            );
            $_SESSION["shoppingCart"][0] = $item_array;
        }
    }
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "delete") {
            foreach ($_SESSION["shoppingCart"] as $keys => $values) {
                if ($values["goodsID"] == $_GET["id"]) {
                    unset($_SESSION["shoppingCart"][$keys]);
                    echo '<script>window.location="customerItemList.php"</script>';
                }
            }
        }
    }
    ?>

</head>
<body>
<form class="box">
    <ul id="menu">
        <li><a href="customerItemList.php">Shopping</a></li>
        <li><a href="customerOrderRecord.php">Order Record</a></li>
        <li><a href="customerAccountInfo.php">Profile</a></li>
        <li><a href="../src/php/logout.php">LogOut</a></li>
    </ul>
    <br>
    <br>
    <h1 id="registerHeader">Customer Item List</h1>
    <table id="goods">
        <tr>
           <th colspan="5">Goods List</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Remaining Quantity</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
    <?php
    $sql = "SELECT * FROM goods";
    $rs = mysqli_query($conn, $sql);
    if(mysqli_num_rows($rs) > 0)
    {
        while($rc = mysqli_fetch_array($rs))
        {
            if($rc['remainingStock']>0){
            ?>
</form>
                <form method="post" action="customerItemList.php?action=add&id=<?php echo $rc["goodsID"]; ?>">
                    <tr>
                        <td><?php echo $rc["goodsID"]; ?></td>
                        <td><?php echo $rc["goodsName"]; ?></td>
                        <td><?php echo $rc["stockPrice"]; ?></td>
                        <td><?php echo $rc["remainingStock"]; ?></td>
                        <td><input type="number" name="quantity" value="1" max="<?php echo $rc['remainingStock'];?>" /></td>
                        <input type="hidden" name="hidden_ID" value="<?php echo $rc["goodsID"]; ?>" />
                        <input type="hidden" name="hidden_name" value="<?php echo $rc["goodsName"]; ?>" />
                        <input type="hidden" name="hidden_price" value="<?php echo $rc["stockPrice"]; ?>" />
                        <td><input type="submit" name="addToCart" value="Add to Cart" /></td>
                    </tr>
                </form>

            <?php
            }
        }
    }
    ?>
    </table>



<table id="cart">
    <tr>
        <th colspan="5">Shopping Cart</th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Price</th>
        <th>Action</th>
    </tr>
    <?php
    if(!empty($_SESSION["shoppingCart"]))
    {
        $total = 0;
        foreach($_SESSION["shoppingCart"] as $keys => $values)
        {
            ?>
            <tr>
                <td><?php echo $values["goodsID"]; ?></td>
                <td><?php echo $values["item_name"]; ?></td>
                <td><?php echo $values["item_quantity"]; ?></td>
                <td><?php echo $values["item_price"]; ?></td>
                <td><?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                <td><a href="customerItemList.php?action=delete&id=<?php echo $values["goodsID"]; ?>">Remove</a></td>
            </tr>
            <?php
            $total = $total + ($values["item_quantity"] * $values["item_price"]);
        }
        ?>

        <tr>
            <td colspan="4" >Shopping Cart Total Price:</td>
            <td colspan="1" >$ <?php echo number_format($total, 2); ?></td>
            <td></td>
        <tr>
        <form name="checkOutForm" id="checkOutForm" method="post" action="../src/php/createOrder.php">
        <td colspan="2">Pick Up Shop:</td>
        <td colspan="3"><select name="pickUpShop" id="pickUpShop">
                <?php
                $shop_sql = "SELECT * FROM shop";
                $shop_rs = mysqli_query($conn, $shop_sql);
                while ($shop_rc = mysqli_fetch_assoc($shop_rs)) {
                    extract($shop_rc);
                    echo "<option value='$shopID'>$address</option>";
                }
                ?>
            </select></td>
        <td colspan="2"><input type="submit" value="Check Out"></td>
        </tr>
            </form>
        </tr>
        <?php

    }

    ?>
</table>


</body>
</html>