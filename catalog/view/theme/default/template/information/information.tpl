<?php echo $header; ?>
<div class="container">
    <div class="white_background">
      <?php echo $content_top; ?>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
          <h2><?php echo $heading_title; ?></h2>
          <?php if($heading_title == "About Us"){ ?> 
              <p class="info_tab"><span class="text_tab about_tab active" data-type="1">About Us</span> | <span class="text_tab milestones_tab" data-type="2">Milestones</span> | <span class="text_tab our_team_tab" data-type="3">Join Our Team</span></p>
          <?php } ?>
          <?php echo $description; ?>
          <?php if($information_id == 7){ ?>
            <hr>
            <form id="product_enquiry" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="enquiry-body">
                    <div class="form-group required">
                        <label class="control-label" for="input-name">Name</label>
                        <input type="text" name="name" placeholder="Name" id="input-name" class="form-control input-theme" />
                        <div class="text-danger ">Please fill in your name!</div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="input-email">Email Address</label>
                        <input type="text" name="email" placeholder="Email Address" id="input-email" class="form-control input-theme" />
                        <div class="text-danger ">E-Mail Address does not appear to be valid!</div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="input-date-required">Date Required</label>
                        <input type="text" name="date_required" placeholder="Date Required" id="input-date-required" class="form-control input-theme date" />
                        <div class="text-danger ">Please select date!</div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="input-subject">Subject</label>
                        <input type="text" name="subject" placeholder="Subject" id="input-subject" class="form-control input-theme" />
                        <div class="text-danger ">Please fill in Subject!</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="input-sample_link">Sample Link</label>
                        <input type="text" name="sample_link" value="" placeholder="Sample Link" id="input-sample_link" class="form-control input-theme" />
<!--                        <label class="control-label" for="input-upload">Upload File</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="upload_file_field" value="" placeholder="" id="input-filename" class="form-control" readonly style="height:50px;"> 
                            <span class="input-group-btn"> 
                                <button type="button" id="button-upload" data-loading-text="Loading..." class="upload_file_field_button" style="background-color:#124734;border:0;">
                                    <i class="fa fa-upload"></i> Upload
                                </button> 
                            </span>
                        </div>
                        <div class="text-danger ">Please upload file!</div>-->
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="input-pax">No. of Pax</label>
                        <input type="text" name="no_pax" value="" placeholder="No. of Pax" id="input-pax" class="form-control input-theme" />
                        <div class="text-danger ">Please fill in No. of Pax </div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="input-message">Message</label>
                        <textarea name="message" rows="5" id="input-message" placeholder="Message" class="form-control input-theme"></textarea>
                        <div class="text-danger ">Message must be between 10 and 3000 characters!</div>
                    </div>
                </div>
                <div class="enquiry-footer">
                    <?= $captcha; ?>
                    <button type="button" id="enquiry_submit" class="btn btn-primary pull-sm-right green_button">Submit</button>
                </div>
            </form>
          <?php } ?>
        </div>
        <?php echo $column_right; ?>
        <?php echo $content_bottom; ?>
        </div>
            <?php if($checkout == 1){ ?>
            <div style="text-align:center;margin-top:50px;">
                <button class="btn btn-primary" onclick="window.location.href = '<?= $checkout_page; ?>'">Yes, I Agree</button>
                <button class="btn btn-danger" onclick="window.location.href = '<?= $cart_page; ?>'">Cancel</button>
            </div>    
            <?php } ?>
    </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
    $('.date').datetimepicker({
        pickTime: false,
        daysOfWeekDisabled: [1]
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
                    url: 'index.php?route=tool/enquiry',
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
                            $("#input-filename").val(json['file_name']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    
    $('.text-danger').css('display','none');
    
    $('#enquiry_submit').on('click', function(){
        $("input").css("border","2px solid #124734");
        $("textarea").css("border","2px solid #124734");
        $('.text-danger').css('display','none');
            if(validated()){
                $.ajax({
                    url: 'index.php?route=product/category/enquiry',
                    data: $('#product_enquiry input, #product_enquiry textarea'),
                    dataType: 'json',
                    type: 'post',
                     beforeSend: function() {
                        $('#enquiry_submit').prop('disabled',true);
                        $('#enquiry_submit').text('Please Wait');
                    },
                    success: function(json){
//                        $('#enquiry_submit').reset();

                        $('#enquiry_submit').prop('disabled',false);
                        $('#enquiry_submit').text('Enquire Now');
                        grecaptcha.reset();
//                                    if(json['warning']){
//                                        swal({
//                                            type: 'error',
//                                            title: 'Something Wrong. Please contact administrators.'
//                                        }).then(function(value){
//                                        });
//                                    }
//                                    if(json['success']){
                                swal({
                                    type: 'success',
                                    title: 'Enquiry Submitted'
                                }).then(function(value){
//                                                window.location.reload();
                                });
                                document.getElementById("product_enquiry").reset();
//                                    }
                    }
                });
            }
    });
    
    function validated(){
        var has_error = false;
        $('#product_enquiry input, #product_enquiry textarea:not(#g-recaptcha-response)').each(function(){
                let ele = this;
                let v = ele.value;
                var length = $.trim(v).length;
                var trigger_error = false;
                switch($(ele).attr('id')){
                    case 'input-name':
                        if(length < 3 || length > 32){
                            trigger_error = true;
                        }
                        break;
                    case 'input-subject':
                        if(length < 4){
                            trigger_error = true;
                        }
                        break;
                    case 'input-pax':
                        if(length < 1){
                            trigger_error = true;
                        }
                        break;
                    case 'input-date-required':
                        if(length < 1){
                            trigger_error = true;
                        }
                        break;
                    case 'upload_file_field':
                        if(length < 1){
                            trigger_error = true;
                        }
                        break;
                    case 'input-email':
                        if(!isEmail($.trim(v))){
                            trigger_error = true;
                        }
                        break;
                    case 'input-message':
                        if(length < 10 || length > 3000){
                            trigger_error = true;
                        }
                        break;
                }
                if(trigger_error){ 
                    if($(ele).attr('id') == "upload_file_field"){
                        $(ele).parent().parent().find('.text-danger').show();
                    }else{
                        $(ele).parent().find('.text-danger').show();
                    }
                    $('#'+$(ele).attr('id')).css("border","2px solid red");
                    has_error = true;
                }
        }); 
//        if (grecaptcha.getResponse() == '') {
//            $('#google_recaptcha').append('<div class="text-danger ">Verification is not correct!</div>');
//                    has_error = true;
//        }
        if(has_error){
            return false;
        } else {
            return true;
        }
    }
    
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
//    validateFile(20000);
//    function validateFile(limit){
//        $('input[type="file"]').each(function() {
//            $(this).change(function(){
//                $('.upload_file_field').val('');
//                if(this.files[0] != null){
//                    var kiloByte = this.files[0].size / 1000;
//                    if( kiloByte > limit ){
//                        alert('Your file size '+(kiloByte/1000).toFixed(0)+'MB exceed upload limit of '+limit/1000+'MB .');
//                        $(this).val('');
//                    } else {
//                        $('.upload_file_field').val(this.files[0].name);
//                    }
//                }
//            }); 
//        });
//    }
</script>