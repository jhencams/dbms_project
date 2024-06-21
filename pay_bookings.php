<?php
require_once('config.php');
if(isset($_GET['id'])){
    $booking_id = $_GET['id'];
    $booking = $conn->query("SELECT * FROM `booking_list` WHERE id = '$booking_id'")->fetch_assoc();
}
?>
<div class="modal-header">
    <h5 class="modal-title">Payment for Booking <?= $booking['code'] ?></h5>
</div>
<div class="modal-body">
    <form id="payment_form">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?= $booking['amount_due'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select class="form-control" id="payment_method" name="payment_method">
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_details">Payment Details</label>
            <textarea class="form-control" id="payment_details" name="payment_details"></textarea>
        </div>
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" id="submit_payment">Submit Payment</button>
</div>
<script>
    $('#submit_payment').click(function(){
        $.ajax({
            url: 'submit_payment.php',
            method: 'POST',
            data: $('#payment_form').serialize(),
            success: function(response){
                if(response.status == 'success'){
                    alert('Payment successful');
                    location.reload();
                } else {
                    alert('Payment failed');
                }
            }
        });
    });
</script>
