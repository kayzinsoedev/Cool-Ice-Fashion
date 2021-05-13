<?= $header; ?>
<div class="container">
    <div class="white_background white_background_bg">
	<?= $content_top; ?>
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div class="row"><?= $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
			<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
			<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?= $class; ?>">
			<h2><?= $heading_title; ?></h2>
			<!--<h3><?= $text_location; ?></h3>-->
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6 col-xs-12">
                            <div class="col-sm-12">
                                    <?php if ($open) { ?>
                                            <strong><?= $text_open; ?></strong><br />
                                            <?= html_entity_decode($open); ?><br />
                                    <?php } ?><br />
                                    <address>
                                            <?= html("<b>".$text_address.":</b> ".$address); ?>
                                    </address>
                                    <?php if ($direction) { ?>
                                            <?php 
                                                $direction = html_entity_decode($direction);
                                                $text = str_replace("<b>", "<br /><br /><strong>", $direction); 
                                                $text = str_replace("</b>", "</strong>", $text);
                                                echo $text;
                                            ?>
                                            <br />
                                    <?php } ?>
                            </div>
                        </div>
						<div class="col-sm-6 col-xs-12">
						    <?php echo html_entity_decode($geomap); ?>
						</div>
						<div class="col-sm-12">
						    <?php if ($comment) { ?>
                                <p style="margin-top:30px;">
                                    <strong><?= $text_comment; ?></strong>
                                    <?= html_entity_decode(nl2br($comment)); ?>
                                </p>
                            <?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php if ($locations) { ?>
				<h3><?= $text_store; ?></h3>
				<div class="panel-group" id="accordion">
					<?php foreach ($locations as $index => $location) { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a href="#collapse-location<?= $location['location_id']; ?>" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" >
										<?= $location['name']; ?> <i class="fa fa-caret-down"></i>
									</a>
								</h4>
							</div>
							<div class="panel-collapse collapse" id="collapse-location<?= $location['location_id']; ?>" >
								<div class="panel-body">
									<div class="row">
										    <?php if ($location['geocode'] || $location['google_map']) { ?> 
												<div class="col-sm-12">
													<div data-id="gmap<?= $index; ?>" data-toggle="gmap" class="gmap"
														<?php if($location['google_map']){ ?>
															data-lat="<?= $location['google_map']['lat']; ?>" 
															data-lng="<?= $location['google_map']['lng']; ?>" 
															data-store="<?= $location['google_map']['store']; ?>" 
															data-address="<?= $location['google_map']['address']; ?>" 
														<?php } ?>
													>
													<div id="gmap<?= $index; ?>" ></div>
													</div>
												</div>
											<?php } ?>
										
										<?php if ($location['image']) { ?>
											<div class="col-sm-3"><img src="<?= $location['image']; ?>" alt="<?= $location['name']; ?>" title="<?= $location['name']; ?>" class="img-thumbnail" /></div>
										<?php } ?>
										<div class="col-sm-3"><strong><?= $location['name']; ?></strong><br />
											<address>
												<?= $location['address']; ?>
											</address>
											<?php if ($location['geocode']) { ?>
												<a href="https://maps.google.com/maps?q=<?= urlencode($location['geocode']); ?>&hl=<?= $geocode_hl; ?>&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?= $button_map; ?></a>
											<?php } ?>
										</div>
										<div class="col-sm-3"> <strong><?= $text_telephone; ?></strong><br>
											<?= $location['telephone']; ?><br />
											<br />
											<?php if ($location['fax']) { ?>
												<strong><?= $text_fax; ?></strong><br>
												<?= $location['fax']; ?>
											<?php } ?>
										</div>
										<div class="col-sm-3">
											<?php if ($location['open']) { ?>
												<strong><?= $text_open; ?></strong><br />
												<?= $location['open']; ?><br />
												<br />
											<?php } ?>
											<?php if ($location['comment']) { ?>
												<strong><?= $text_comment; ?></strong><br />
												<?= $location['comment']; ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
				
			<?php } ?>
                    <div id='contact_form'>
			<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
				<h3><?= $text_contact; ?></h3>
				<div class="contact-body">
					<div class="form-group required">
						<label class="control-label" for="input-name"><?= $entry_name; ?></label>
						<input type="text" name="name" value="<?= $name; ?>" id="input-name" class="form-control" placeholder="<?= $entry_name; ?>" />
						<?php if ($error_name) { ?>
							<div class="text-danger"><?= $error_name; ?></div>
						<?php } ?>								
					</div>
				
					<div class="form-group required">
						<label class="control-label" for="input-email"><?= $entry_email; ?></label>
						<input type="text" name="email" value="<?= $email; ?>" id="input-email" class="form-control" placeholder="<?= $entry_email; ?>" />
						<?php if ($error_email) { ?>
							<div class="text-danger"><?= $error_email; ?></div>
						<?php } ?>
					</div>
					
					<div class="form-group required">
                        <label class="control-label" for="input-pax">No. of Pax</label>
                        <input type="text" name="pax" value="" placeholder="No. of Pax" id="input-pax" class="form-control input-theme" />
                        <?php if ($error_pax) { ?>
							<div class="text-danger"><?= $error_pax; ?></div>
						<?php } ?>
                    </div>
                    
                    <div class="form-group required">
                        <label class="control-label" for="input-date-required">Date Required</label>
                        <input type="text" name="date" placeholder="Date Required" id="input-date-required" class="form-control input-theme date" style="text-align:left" readonly/>
                        <?php if ($error_date) { ?>
							<div class="text-danger"><?= $error_date; ?></div>
						<?php } ?>
                    </div>
				
<!--					<div class="form-group required">
						<label class="control-label" for="input-telephone"><?= $entry_telephone; ?></label>
						<input type="tel" name="telephone" value="<?= $telephone; ?>" id="input-telephone" class="form-control input-number" placeholder="<?= $entry_telephone; ?>" />
						<?php if ($error_telephone) { ?>
							<div class="text-danger"><?= $error_telephone; ?></div>
						<?php } ?>
					</div>-->

					<div class="form-group required">
                                                <?php if(isset($product['name'])){
                                                    $subject = "Pre-order: ".$product['name'];
                                                }?>
						<label class="control-label" for="input-subject"><?= $entry_subject; ?></label>
						<input type="text" name="subject" value="<?= $subject; ?>" id="input-subject" class="form-control" placeholder="<?= $entry_subject; ?>" />
						<?php if ($error_subject) { ?>
							<div class="text-danger"><?= $error_subject; ?></div>
						<?php } ?>
					</div>

					<div class="form-group required">
						<label class="control-label" for="input-enquiry"><?= $entry_enquiry; ?></label>
						<textarea name="enquiry" rows="10" id="input-enquiry" class="form-control" placeholder="<?= $entry_enquiry; ?>"><?= $enquiry; ?></textarea>
						<?php if ($error_enquiry) { ?>
							<div class="text-danger"><?= $error_enquiry; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="contact-footer">
					<?= $captcha; ?>
					<input class="btn btn-primary green_button pull-sm-right" type="submit" value="<?= $button_submit; ?>" />
 				</div>
			</form>
                    </div>
		</div>
	<?= $column_right; ?></div>
	<?= $content_bottom; ?>
    </div>
</div>

<?= $footer; ?>
<script type="text/javascript">
    $('.date').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: '<?php echo date("d-m-Y"); ?>',
        pickTime: false,
        daysOfWeekDisabled: [1]
    });
    
    $('.date').on('dp.show', function() {
      var datepicker = $('body').find('.bootstrap-datetimepicker-widget');
      if (datepicker.hasClass('bottom')) {
    	var top = $(this).offset().top + $(this).outerHeight();
    	var left = $(this).offset().left;
    	datepicker.css({
    	  'top': top + 'px',
    	  'bottom': 'auto',
    	  'left': left + 'px'
    	});
      }
      else if (datepicker.hasClass('top')) {
    	var top = $(this).offset().top - datepicker.outerHeight();
    	var left = $(this).offset().left;
    	datepicker.css({
    	  'top': top + 'px',
    	  'bottom': 'auto',
    	  'left': left + 'px'
    	});
      }
    });
    
</script>
