<?php
error_reporting(0);
require_once('classes/product.class.php');
$operation = trim($_REQUEST['operation']);
switch($operation)
{
    case 'set_product':
        echo json_encode($product_obj->set_product($_POST));
    break;
    case 'delete_product':
        echo json_encode($product_obj->delete_product($_POST['id']));
    break;
    case 'get_product':
        echo json_encode($product_obj->get_product($_POST['id']));
    break;
}
?>