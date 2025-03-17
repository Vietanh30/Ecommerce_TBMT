<?php
// Chỉ cho phép POST và POST có ID đơn hàng
if(!$_POST || !isset($_POST['id']) || !is_numeric($_POST['id']))
   die('access denied');

$order_id = $_POST['id'];

// Kiểm tra đơn hàng có tồn tại không
$result = $conn->query("SELECT payment_status FROM orders where id={$order_id}");

if($result) {
    // Lấy thông tin đơn hàng
   $order_details = $result->fetch_object();
   
   // Trả về kết quả trạng thái đơn hàng dạng JSON. Ví dụ: {"payment_status":"Unpaid"}
   echo json_encode(['payment_status' => $order_details->payment_status]);
} else {
    
    // Trả về kết quả không tìm thấy đơn hàng
   echo json_encode(['payment_status' => 'order_not_found']);

}
?>
