<style>
.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
.payment_info_message {
    width: 80%;
    margin: 50px auto;

}
</style>
<div class="payment_info_message">
    <div class="alert alert-success"><b>Success!!</b> Your payment is successfull...</div>
</div>
<?php

update_post_meta($payment_id, "payment_status", "success");
//update_post_meta($payment_id, "payable", "");


# update booking status;
update_post_meta($booking_id, "wp_travel_engine_booking_payment_status", "success");
update_post_meta($booking_id, "wp_travel_engine_booking_status", "booked");


$trip_id = $package_name = $package_price = "";
$order_trips = get_post_meta($booking_id, "order_trips", true);
foreach ($order_trips as $order_trip)
{
    $trip_id = $order_trip['ID'];
    $package_name = $order_trip['title'];
    $package_price = $order_trip['cost'];
}
update_post_meta($booking_id, "paid_amount", $package_price);
update_post_meta($booking_id, "due_amount", 0);

?>
<div class="thank-you-container">
	<h3 class="trip-details">Booking Details:</h3>
	<div class="detail-container">
		<div class="detail-item">
			<strong class="item-label">Booking ID :</strong>
			<span class="value"><?php echo $booking_id ?></span>
		</div>
		<div class="detail-item" style="text-align:center;justify-content:center;">
			<strong style="font-size:18px;font-weight:normal">Trip Details</strong>
		</div>
					<div class="detail-item">
				    <a href="<?php echo get_permalink($trip_id) ?>" 
                        data-wpel-link="internal" target="_blank" 
                        rel="noopener noreferrer"><?php echo $package_name ?></a> <code>[#<?php echo $trip_id ?>]</code>
			</div>
			<div class="detail-item">
				<strong class="item-label">Trip ID:</strong>
				<span class="value"><?php echo $trip_id ?></span>
			</div>
								<div class="detail-item">
						<strong class="item-label">Trip Code:</strong>
						<span class="value">WTE-<?php echo $trip_id ?></span>
					</div>
								<div class="detail-item">
				<strong class="item-label">Trip Cost:</strong>
				<span class="value">
				<?php echo $package_price ?><br>				</span>
			</div>

			
								<div class="detail-item" style="text-align:center;justify-content:center;">
			
		</div>
						
	</div>
</div>

<br><br>