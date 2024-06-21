<?php 
if ($_settings->userdata('login_type') == 1 || $_settings->userdata('id') <= 0) {
    echo "<script> alert('You are not allowed to access this page.'); location.replace('./')</script>";
    exit;
}
?>
<div class="content py-5">
    <div class="container">
        <div class="card card-outline card-maroon rounded-0 shadow">
            <div class="card-header">
                <h4 class="card-title">My Bookings</h4>
                <div class="card-tools">
                    <button id="book_now" class="btn btn-default border-0 bg-gradient-maroon btn-flat" href="./?page=manage_account">New Booking</button>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <table id="booking-list" class="table table-stripped table-bordered">
                        <colgroup>
                            <col width="15%">
                            <col width="15%">
                            <col width="25%">
                            <col width="15%">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-maroon">
                                <th class="text-center">Date Created</th>
                                <th class="text-center">Ref. Code</th>
                                <th class="text-center">Hall</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Payment</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = "SELECT b.*, h.name as hall, p.amount as payment_amount, p.status as payment_status 
                                      FROM `booking_list` b 
                                      INNER JOIN hall_list h ON b.hall_id = h.id 
                                      LEFT JOIN payment_list p ON b.id = p.booking_id 
                                      WHERE b.client_id = '{$_settings->userdata('id')}' 
                                      ORDER BY b.status ASC, UNIX_TIMESTAMP(b.date_created) ASC";
                            $bookings = $conn->query($query);
                            
                            if (!$bookings) {
                                echo "<tr><td colspan='6' class='text-center'>Error: " . $conn->error . "</td></tr>";
                            } else {
                                while ($row = $bookings->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                                <td><?= $row['code'] ?></td>
                                <td><?= $row['hall'] ?></td>
                                <td class="text-center">
                                    <?php 
                                        switch ($row['status']) {
                                            case 0:
                                                echo '<span class="badge badge-secondary bg-gradient-secondary px-3 rounded-pill">Pending</span>';
                                                break;
                                            case 1:
                                                echo '<span class="badge badge-primary bg-gradient-primary px-3 rounded-pill">Confirmed</span>';
                                                break;
                                            case 2:
                                                echo '<span class="badge badge-teal bg-gradient-teal px-3 rounded-pill">Done</span>';
                                                break;
                                            case 3:
                                                echo '<span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Cancelled</span>';
                                                break;
                                            default:
                                                echo '<span class="badge badge-default border px-3 rounded-pill">N/A</span>';
                                                break;
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        if ($row['payment_status'] == 1) {
                                            echo '<span class="badge badge-success bg-gradient-success px-3 rounded-pill">Paid</span>';
                                        } else {
                                            echo '<span class="badge badge-warning bg-gradient-warning px-3 rounded-pill">Unpaid</span>';
                                        }
                                    ?>
                                    <br>
                                    Amount: <?= isset($row['payment_amount']) ? number_format($row['payment_amount'], 2) : 'N/A' ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-default border btn-sm btn-flat view_data" data-id='<?= $row['id'] ?>'><i class="fa fa-eye"></i> View</button>
                                    <?php if ($row['status'] == 1 && $row['payment_status'] != 1): ?>
                                        <button class="btn btn-success border btn-sm btn-flat pay_now" data-id='<?= $row['id'] ?>'><i class="fa fa-credit-card"></i> Pay</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
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
                { orderable: false, targets: 5 }
            ],
        });
    });
</script>
