<?= $header; ?>
<div class="container">

    <div class="white_background">
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
              <!--<h2><?php $heading_title; ?></h2>-->
              <div class="row">
                  <?php if ($column_left || $column_right) { ?>
                  <?php $class = 'col-sm-6'; ?>
                  <?php } else { ?>
                  <?php $class = 'col-sm-6'; ?>
                  <?php } ?>
                  <div class="<?= $class; ?>">
                    <?php include_once('product_image.tpl'); ?>
                  </div>

                  <?php if ($column_left || $column_right) { ?>
                  <?php $class = 'col-sm-6'; ?>
                  <?php } else { ?>
                  <?php $class = 'col-sm-6'; ?>
                  <?php } ?>

                  <div class="<?= $class; ?>">
                    <?php include_once('product_description.tpl'); ?>
                  </div>
              </div>

              <?php include_once('product_attributes_reviews.tpl'); ?>

                  <?php //if ($products) include_once('product_related.tpl'); ?>
                  <?= $related_products_slider; ?>

              <!--
              <?php if ($tags) { ?>
              <p><?= $text_tags; ?>
                <?php for ($i = 0; $i < count($tags); $i++) { ?>
                <?php if ($i < (count($tags) - 1)) { ?>
                <a href="<?= $tags[$i]['href']; ?>"><?= $tags[$i]['tag']; ?></a>,
                <?php } else { ?>
                <a href="<?= $tags[$i]['href']; ?>"><?= $tags[$i]['tag']; ?></a>
                <?php } ?>
                <?php } ?>
              </p>
              <?php } ?>
               -->
              </div>
            <?= $column_right; ?></div>
            <?= $content_bottom; ?>
    </div>
</div>

<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<?php if(!$enquiry){ ?>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	if($('#input-quantity').val() > 0) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: $('#product input[type=\'text\'], #product input[type=\'number\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
			dataType: 'json',
			beforeSend: function () {
				$('#button-cart').button('loading');
			},
			complete: function () {
				$('#button-cart').button('reset');
			},
			success: function (json) {
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');

				if (json['error']) {
					if (json['error']['option']) {
						for (i in json['error']['option']) {
							var element = $('#input-option' + i.replace('_', '-'));

							if (element.parent().hasClass('input-group')) {
								element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							} else {
								element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
							}
						}
					}

					if (json['error']['recurring']) {
						$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
					}

					// Highlight any found errors
					$('.text-danger').parent().addClass('has-error');
				}

				if (json['success']) {
					//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					swal({
						title: json['success_title'],
						html: json['success'],
						type: "success"
					});

					setTimeout(function () {
						$('#cart-quantity-total').text(json['total_quantity']);
						$('#cart-total').text(json['total']);
					}, 100);

					$('#cart > ul').load('index.php?route=common/cart/info ul > *');
				}

				if(json['error_stock_add']){
					swal({
						title: json['error_stock_add_title'],
						html: json['error_stock_add'],
						type: "error"
					});
				}

				if(json['error_outofstock']){
					swal({
						title: json['error_outofstock_title'],
						html: json['error_outofstock'],
						type: "error"
					});
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>
<?php }else{ ?>
<script type="text/javascript"><!--
	$('#button-enquiry').on('click', function () {
		if ($('#input-quantity').val()  > 0) {
			$.ajax({
				url: 'index.php?route=enquiry/cart/add',
				type: 'post',
				data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
				dataType: 'json',
				beforeSend: function () {
					$('#button-enquiry').button('loading');
				},
				complete: function () {
					$('#button-enquiry').button('reset');
				},
				success: function (json) {
					$('.alert, .text-danger').remove();
					$('.form-group').removeClass('has-error');

					if (json['error']) {
						if (json['error']['option']) {
							for (i in json['error']['option']) {
								var element = $('#input-option' + i.replace('_', '-'));

								if (element.parent().hasClass('input-group')) {
									element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								} else {
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
						}

						if (json['error']['recurring']) {
							$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
						}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						swal({
							title: json['success_title'],
							html: json['success'],
							type: "success"
						});

						setTimeout(function () {
							$('#enquiry-quantity-total').text(json['total_quantity']);
							$('#enquiry-total').text(json['total']);
						}, 100);

						$('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
//--></script>
<?php } ?>

<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?= $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?= $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}

			// to reset recaptcha after form ajax submit
			if(typeof grecaptcha !== 'undefined' && grecaptcha && grecaptcha.reset) { 
				grecaptcha.reset(); 
			} 
		}
	});
});
//--></script>
<input type='hidden' id='fbProductID' value='<?= $product_id ?>' />
<?= $footer; ?>
