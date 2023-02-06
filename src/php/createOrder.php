<?php
date_default_timezone_set('Asia/Hong_Kong');
$nowDateTime = date("Y-m-d H:i:s");
session_start();
include "conn.php";
$loginID = $_SESSION['loginID'];
extract($_POST);
$shopID = $_POST['pickUpShop'];
for ($i = 0; $i < count($_SESSION['shoppingCart']); $i++) {
    extract($_SESSION['shoppingCart'][$i]);
}
//extract($_SESSION["shoppingCart"]);
for ($i = 0; $i < count($_SESSION['shoppingCart']); $i++)
    foreach ($_SESSION["shoppingCart"][$i] as $key => $value) {

    }
$getNewOrderID = "SELECT * FROM orders";
$getting = mysqli_query($conn, $getNewOrderID);
$ordersOrderID = mysqli_num_rows($getting);
$ordersOrderID += 1;

$checkOK = false;
$checkOK2 = true;
//check shopping cart item quantity
for($i = 0;$i<count($_SESSION['shoppingCart']);$i++){
    if($checkOK2 == false){
        break;
    }
    extract($_SESSION['shoppingCart'][$i]);
    $getitemquantity = "SELECT * FROM goods WHERE goodsID = '$goodsID'";
    $getitemquantitying = mysqli_query($conn,$getitemquantity);
    $getitemqunaityed = mysqli_fetch_assoc($getitemquantitying);
    extract($getitemqunaityed);
    if($item_quantity > $remainingStock){
        $checkOK2 = false;
        unset($_SESSION['shoppingCart']);
        echo "<script> alert('$goodsName out of stock'); </script>";
        echo "<meta http-equiv='Refresh' content='0;URL=../../customer/customerItemList.php'>";
    }
    if($i = (count($_SESSION['shoppingCart'])-1))
            $checkOK = true;
}

//if all item quantity is fit
if($checkOK2 == true){
    //create a new orders record
    $newOrder = "INSERT INTO orders (orderID,customerEmail,shopID,orderDateTime,status)VALUES(
                     '$ordersOrderID',
                     '$loginID',
                     $shopID,
                     '$nowDateTime',
                     1
                     )";
    mysqli_query($conn, $newOrder);

    //create all new orderitem record
    for ($i = 0; $i < count($_SESSION['shoppingCart']); $i++) {
        extract($_SESSION['shoppingCart'][$i]);
        $InsertItem = "INSERT INTO orderitem VALUES(
                        $ordersOrderID,
                        $goodsID,
                        $item_quantity,
                        $item_price
                        )";
        mysqli_query($conn, $InsertItem);
        //select quantity
        $getGoodsItemQuantity = "SELECT * FROM goods WHERE goodsID = '$goodsID'";
        $getQuan = mysqli_query($conn,$getGoodsItemQuantity);
        $DBQuan = mysqli_fetch_assoc($getQuan);
        extract($DBQuan);
        //update quantity
        $remainingStock = $remainingStock - $item_quantity;
        $updateItemQuantity = "UPDATE goods SET remainingStock = '$remainingStock' WHERE goodsID = '$goodsID'";
        mysqli_query($conn,$updateItemQuantity);
    }

    //delete shoppingCart
    unset($_SESSION['shoppingCart']);
    echo "<script> alert('Place Order Successful'); </script>";
    echo "<meta http-equiv='Refresh' content='0;URL=../../customer/customerItemList.php'>";
}


?>