<div class="custom-heading" style="margin: 4rem 0 2rem;"><h2 ><?php echo $text_instruction; ?></h2></div>
<div style="width: 100%;float: left;">
	<?php if ($error) { ?>
	  <div class="alert alert-danger" style="margin: 3rem 0;width: 100%;float: left;">
	    <i class="fa fa-exclamation-circle"></i>
	    <?= $error; ?>
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	  </div>
	  <?php } ?>
	<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 instructions well well-sm">
		<div class="col-md-6 col-sm-5 col-xs-12 left">
			<p><?php echo $bank; ?></p>
			<br>
			<p><?php echo $text_payment; ?></p>
		</div>
		<div class="col-md-6 col-sm-7 col-xs-12 right text-right">
			<a href="<?php echo $scannable_code;?>" target="_blank"><img src="<?php echo $scannable_code;?>" title="<?php echo $bank; ?>" alt="<?php echo $bank; ?>" class="img-responsive" style = "margin:auto;"></a>
		</div>
	</div>
</div>
<script type="text/javascript">
	setInterval(function(){

		$.ajax({
			url: 'index.php?route=extension/payment/omise_paynow/checkStatus',
			success: function(json) {
				if (json['redirect']) {
          			location = json['redirect'];
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

	}, 5000);
</script>