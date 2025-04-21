<?php
    session_start();

    // include('../config/db.php');


    // if(isset($_POST['addToCartBtn'])){
    //     if(isset($_SESSION['cart'])){

    //     }else{
    //         $session_arr = array(
    //             'prod_id' => $_GET['id'],
    //             'prod_name' => $_POST['productName'],
    //             'prod_price' => $_POST['productPrice'],
    //             'prod_qty' => $_POST['qty'],
    //         );
    //         $_SESSION['cart'][] = $session_arr;
    //     }
    // }

    if (isset($_POST['addToCartBtn'])) {
        // Extract product information
        $productId = $_POST['productId'];
        $productName = $_POST['productName'];
        $productImage = $_POST['productImage'];
        $productPrice = $_POST['productPrice'];
        $productQty = $_POST['qty'];
        $productTotal = $_POST['total'];
    
        // Initialize cart if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    
        // Check if the product already exists in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['prod_id'] == $productId) {
                // If product already exists, update quantity
                $item['prod_qty'] += $productQty;
                // $item['prod_price'] += $productPrice;
                $item['prod_total']  += $productPrice;
                $found = true;
                break;
            }
        }
    
        // If product doesn't exist in the cart, add it
        if (!$found) {
            $_SESSION['cart'][] = [
                'prod_id' => $productId,
                'prod_name' => $productName,
                'prod_image' => $productImage,
                'prod_price' => $productPrice,
                'prod_qty' => $productQty,
                'prod_total' => $productTotal
            ];
        }
    }
    
    ?>