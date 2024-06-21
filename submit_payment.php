<?php
require_once('config.php');
$response = array();
if($_POST){
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $payment_details = $_POST['payment_details'];

    
    $conn->query("UPDATE `booking_list` SET status = 2 WHERE id = '$booking_id'");
    $conn->query("INSERT INTO `payments` (booking_id, amount, payment_method, payment_details) VALUES ('$booking_id', '$amount', '$payment_method', '$payment_details')");

    $response['status'] = 'success';
} else {
    $response['status'] = 'error';
}
echo json_encode($response);

?>

<script>
    $(function(){
        $(".view_data").click(function(){
            uni_modal("View Booking Details","view_booking.php?id="+ $(this).attr('data-id'));
        });
        $(".pay_now").click(function(){
            uni_modal("Payment","pay_booking.php?id="+ $(this).attr('data-id'));
        });
        $('#booking-list').dataTable({
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
        });
    });
</script>
