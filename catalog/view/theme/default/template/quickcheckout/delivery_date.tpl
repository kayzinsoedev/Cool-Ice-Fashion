<label class="control-label pd-t15 pd-b15"><strong>Date & Time</strong></label>
	
<div class="input-group sc-date pd-b15">
	<input type="text" name="delivery_date" value="" class="form-control delivery_date readonly-white" readonly="true" />
	<label class="input-group-addon">
		<span class="glyphicon glyphicon-calendar"></span>
	</label>
</div>

<div class="pd-b15">
<select name="delivery_time" class="form-control time-select delivery_time">
<option value="">-- Please Select Time --</option>
<?php foreach ($sc_timings_slots as $each) { ?>
    <option value="<?php echo $each['value']; ?>"><?php echo $each['label']; ?></option>
<?php } ?></select>
</div>

<script type="text/javascript">
<?php if(strtotime($delivery_min) > strtotime($delivery_max)){ ?>
    var r = confirm("Your cart item already expired! Please remove it");
    window.location.href = "<?= $cart_link; ?>";
<?php } ?>
$('.sc-date').datetimepicker({
    format: 'YYYY-MM-DD',
    minDate: '<?php echo $delivery_min; ?>',
    maxDate: '<?php echo $delivery_max; ?>',
    disabledDates: [<?php echo $delivery_unavailable; ?>],
    ignoreReadonly: true,
    useCurrent: false,
    <?php if ($delivery_days_of_week != '') { ?>
    daysOfWeekDisabled: [<?php echo $delivery_days_of_week; ?>]
    <?php } ?>
});
$(".delivery_date").val("");
$(".delivery_time").hide();

$('.sc-date').on("dp.change", function (e) {
	$(".delivery_time").show();
	updateDeliveryTime($(this).find('input[type="text"]').val(), $('.time-select'));
});

function updateDeliveryTime(selected_date, time_select) {
    var data = "delivery_date="+selected_date+"&shipping_method="+$('input[name=\'shipping_method\']:checked').data("title");
    var shipping_method =$('input[name=\'shipping_method\']:checked').val();
     console.log(shipping_method);
    $.ajax({
    	url: 'index.php?route=quickcheckout/shipping_method/getdeliverytimes&'+data,
    	type: 'get',
    	dataType: 'json',
    	beforeSend: function() {
    	},
    	complete: function() {
    	},
    	success: function(json) {
    		if(json){
    		    var day = 0;
    		    var message = "";
    			time_select.find('option').remove();
    			time_select.append('<option value="">-- Please Select Time --</option>');
    			for(let ind in json) {
                    day = json[ind].day;
                    message = json[ind].message;
    				time_select.append('<option value="' + json[ind].value + '">' + json[ind].label + '</option>');
    			}
    			if(day == 7 && shipping_method == "category_product_based.category_product_based_0"){
        			swal({
					    title: "Important Note",
					    html: message,
					    type: "info"
			        });
    			}
            }
    	},
    	error: function(xhr, ajaxOptions, thrownError) {
    	    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    	}
    });
}

$('.delivery_time').on('change', function() {
        var data = "delivery_date="+$(".delivery_date").val()+"&delivery_time="+$(this).val();
        $.ajax({
                url: 'index.php?route=quickcheckout/shipping_method/setSlotSurchage&'+data,
                type: 'get',
                dataType: 'json',
                beforeSend: function() {
                },
                complete: function() {
                },
                success: function(html) {
                    loadCart();
                },
                <?php if ($debug) { ?>
                error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
                <?php } ?>
        });
});
</script>