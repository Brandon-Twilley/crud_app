<?php
/**
 * Created by PhpStorm.
 * User: SecretLuver
 * Date: 5/13/2018
 * Time: 12:27 AM
 */

session_start();

$body_response = file_get_contents('php://input');
$response_json = json_decode($body_response);        //get the response body
echo json_encode($response_json);
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if($_SERVER['REQUEST_METHOD']=='POST') {
    $cart = new stdClass();
    $cart = $_SESSION['cart'];
    $item_is_in_cart = false;
    if (isset($_SESSION['cart'])) {
        if ($response_json->quantity > 0) {
            if (!isset($cart[$response_json->id])) {
                $cart[$response_json->id] = new stdClass();
                $cart[$response_json->id]->quantity = $response_json->quantity;
            } else {
                $cart[$response_json->id]->quantity = $response_json->quantity + $cart[$response_json->id]->quantity;
            }
        }
    }

    if (!$item_is_in_cart) {
        array_push($cart, $response_json);
    }

    $_SESSION['cart'] = $cart;
} elseif($_SERVER['REQUEST_METHOD']=='DELETE') {
    $cart = new stdClass();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    $cart = $_SESSION['cart'];
    $item_is_in_cart = false;
    if (isset($_SESSION['cart'])) {
        $cart_item = $cart[$response_json->id];
        unset($cart_item);
    }

    $_SESSION['cart'] = $cart;
}
    echo json_encode($_SESSION['cart']);

?>