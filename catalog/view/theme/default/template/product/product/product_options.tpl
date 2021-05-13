
<div id="product">
    <?php if(!$is_out_stock){?>
        <div class="form-group">
          
            <div class="input-group">
              <span class="input-group-addon"><?= $entry_qty; ?></span>
              <span class="input-group-btn"> 
                <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="qty-<?= $product_id; ?>" onclick="descrement($(this).parent().parent())")>
                  <span class="glyphicon glyphicon-minus"></span> 
                </button>
              </span>
              <input type="text" name="quantity" class="form-control input-number integer text-center" id="input-quantity" value="<?= $minimum; ?>" >
              <span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="qty-<?= $product_id; ?>" onclick="increment($(this).parent().parent())">
                  <span class="glyphicon glyphicon-plus"></span>
                </button>
              </span>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php if ($options) { ?>
        <hr>
        <!--<h3><?= $text_option; ?></h3>-->
        <div class='clearfix'></div> 
            <div class='product_from'>
                <?php foreach ($options as $option) { ?>
                <?php if ($option['type'] == 'select') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <select name="option[<?= $option['product_option_id']; ?>]" id="input-option<?= $option['product_option_id']; ?>" class="form-control">
                    <option value=""><?= $text_select; ?></option>
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                    <option value="<?= $option_value['product_option_value_id']; ?>"><?= $option_value['name']; ?>
                    </option>
                    <?php } ?>
                  </select>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'radio') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label"><?= $option['name']; ?></label>
                  <div id="input-option<?= $option['product_option_id']; ?>">
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                    <div class="radio">
                      <label>
                        <input type="radio" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option_value['product_option_value_id']; ?>" />
                        <?php if ($option_value['image']) { ?>
                        <img src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
                        <?php } ?>                    
                        <?= $option_value['name']; ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'checkbox') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label"><?= $option['name']; ?></label>
                  <div id="input-option<?= $option['product_option_id']; ?>">
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="option[<?= $option['product_option_id']; ?>][]" value="<?= $option_value['product_option_value_id']; ?>" />
                        <?php if ($option_value['image']) { ?>
                        <img src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
                        <?php } ?>
                        <?= $option_value['name']; ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'text') {
                            $max_num = $option['max_letter'];
                            $max = "";
                            $max_class = "";
                            if($option['max_letter'] > 0){
                                $max = "maxlength = '".$option['max_letter']."'";
                                $max_class = "max_class";
                            }
                ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <input type="text" name="option[<?= $option['product_option_id']; ?>]" onkeypress="return onlyAlphabets(event,this);" value="<?= $option['value']; ?>" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control checkLanguage <?= $max_class; ?>" />
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'number') {?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <input type="number" name="option[<?= $option['product_option_id']; ?>]" min='<?= $option['minimum']; ?>' max='<?= $option['maximum']; ?>' onkeydown="javascript: return event.keyCode == 69 ? false : true" value="<?= $option['value']; ?>" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control checknumber<?= $option['product_option_id']; ?>" />
                </div>
                <script>
                    $('.checknumber<?= $option['product_option_id']; ?>').on("keyup change",function(){
                        var text = $(this).val();
                        var min = <?= $option['minimum']; ?>;
                        var max = <?= $option['maximum']; ?>;
                        if(text < min || text > max){
                            $(this).val("");
                        }
                    });
                </script>
                <?php } ?>
                <?php if ($option['type'] == 'textarea') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <textarea name="option[<?= $option['product_option_id']; ?>]" rows="5" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control checkTextareaLanguage" maxlength="<?=$option['char_limit'];?>"><?= $option['value']; ?></textarea>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'file') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label"><?= $option['name']; ?></label>
                  <button type="button" id="button-upload<?= $option['product_option_id']; ?>" data-loading-text="<?= $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?= $button_upload; ?></button>
                  <input type="hidden" name="option[<?= $option['product_option_id']; ?>]" value="" id="input-option<?= $option['product_option_id']; ?>" />
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'date') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <div class="input-group date">
                    <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'datetime') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <div class="input-group datetime">
                    <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'time') { ?>
                <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                  <div class="input-group time">
                    <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="HH:mm" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>    
        <?php } ?>
    <?php } ?>
    <?php if ($recurrings) { ?>
    <hr>
    <h3><?= $text_payment_recurring; ?></h3>
    <div class="form-group required">
      <select name="recurring_id" class="form-control">
        <option value=""><?= $text_select; ?></option>
        <?php foreach ($recurrings as $recurring) { ?>
        <option value="<?= $recurring['recurring_id']; ?>"><?= $recurring['name']; ?></option>
        <?php } ?>
      </select>
      <div class="help-block" id="recurring-description"></div>
    </div>
    <?php } ?>
  <div class='clearfix'></div>
    <?php if ($minimum > 1) { ?>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?= $text_minimum; ?></div>
    <?php } ?>
    <div class="form-group">
    
      <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
      <br />
      <?php if(!$is_out_stock){?>
        <?php if(!$enquiry){ ?>
            <button type="button" id="button-cart" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary green_button"><img src="image/cssbackground/cart-copy.png" style="width:20px;"> <?= $button_cart; ?></button>
          <?php }else{ ?>
            <button type="button" class="btn btn-primary green_button" onclick="window.location.href='<?= $order_link; ?>'"><?= "Enquiry"; ?></button>
          <?php } ?>
          
          <?php if($download){ ?>
            <a href="<?= $download; ?>" target="_blank" class="btn btn-primary" ><?= $button_download; ?></a>
          <?php } ?>
      <?php } ?>
  
    </div>
  </div>


  
<script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    
    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });
    
    $('.time').datetimepicker({
        pickDate: false
    });
    
    $('.checkLanguage').on("keyup change",function(){
        var text = $(this).val();
        var lenght = text.length;
        <?php if(isset($max_num)){ ?>
            var max = <?= $max_num; ?>;
        <?php }else{ ?>
            var max = 5000;
        <?php } ?>
        var new_text = "";
        var i = 0;
        for(var a=0; a<lenght; a++){
            var char = text.substr(a, 1);
            if (/^[a-zA-Z]+$/.test(char)) {
                if(max > 0){
                    if(i <= max){
                        new_text = new_text + char;
                        i++;
                    }
                }else{
                    new_text = new_text + char;
                }
            }
        }
        $(this).val(new_text);
    });
    
    $('.checkTextareaLanguage').on("keyup change",function(){
        var text = $(this).val();
        var lenght = text.length;
        var new_text = "";
        for(var a=0; a<lenght; a++){
            var char = text.substr(a, 1);
            //if (/^[a-zA-Z]+$/.test(char)) {
            if (/^[a-zA-Z0-9.\/!"\#$%&'()*+,\-:;<=>?@\[\\\]^_`{|}~ ]+$/.test(char)) {
                new_text = new_text + char;
            }
        }
        $(this).val(new_text);
    });
    
    $('.max_class').on("keydown",function(){
        <?php if(isset($max_num)){ ?>
            var max = <?= $max_num; ?>;
        <?php }else{ ?>
            var max = 5000;
        <?php } ?>
         var value = $(this).val();
        
         var len = value.length - substr_count(value," ");
         len = len +1;
         var length = value.length;
         
         if(len > max){
             $(this).val($(this).val().substr(0,length-1));
         }
    });
    
    $('button[id^=\'button-upload\']').on('click', function() {
        var node = this;
    
        $('#form-upload').remove();
    
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
    
        $('#form-upload input[name=\'file\']').trigger('click');
    
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }
    
        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);
    
                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $(node).button('loading');
                    },
                    complete: function() {
                        $(node).button('reset');
                    },
                    success: function(json) {
                        $('.text-danger').remove();
    
                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }
    
                        if (json['success']) {
                            alert(json['success']);
    
                            $(node).parent().find('input').val(json['code']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    
    function onlyAlphabets(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            }else if (e) {
                var charCode = e.which;
            }else { 
                return true;
            }
            
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32){
                return true;
            }else{
                return false;
            }
        }
        catch (err) {
            alert(err.Description);
        }
    }
    
    function substr_count(string,substring,start,length){
        var c = 0;
        if(start){ 
            string = string.substr(start);
        }
        if(length){ 
            string = string.substr(0,length);
        }
        for (var i=0;i<string.length;i++){
            if(substring == string.substr(i,substring.length)){
                c++;
            }
        }
        return c;
    }
    //--></script>

  <?php if(isset($update_price_status) && $update_price_status) { ?>
					
	<script type="text/javascript">
	
		$("#product input[type='checkbox']").click(function() {
			changePrice();
		});
		
		$("#product input[type='radio']").click(function() {
			changePrice();
		});
		
		$("#product select").change(function() {
			changePrice();
		});
		
		$("#input-quantity").blur(function() {
			changePrice();
		});
                
		$("#product input[type='text']").keyup(function() {
			changePrice();
		});

        $("#product input[type='number']").keyup(function() {
			changePrice();
		});

        $("#input-quantity").parent(".input-group").find(".btn-number").click(function() {
			changePrice();
		});
		
		function changePrice() {
			$.ajax({
				url: 'index.php?route=product/product/updatePrice&product_id=<?php echo $product_id; ?>',
				type: 'post',
				dataType: 'json',
				data: $('#product input[name=\'quantity\'], #product select, #product input[type=\'checkbox\']:checked, #product input[type=\'radio\']:checked, #product input[type=\'text\'], #product input[type=\'number\']'),
				beforeSend: function() {
					
				},
				complete: function() {
					
				},
				success: function(json) {
					$('.alert-success, .alert-danger').remove();
					
					if(json['new_price_found']) {
						$('.new-prices').html(json['total_price']);
						$('.product-tax').html(json['tax_price']);
					} else {
						$('.old-prices').html(json['total_price']);
						$('.product-tax').html(json['tax_price']);
					}
				}
			});
		}
	</script>
		
<?php } ?>