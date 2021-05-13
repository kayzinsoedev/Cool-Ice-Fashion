<?php echo $header; ?>
<div class="container">
    <div class="white_background">
      <?php echo $content_top; ?>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="row">

        <h2><?php echo $heading_title; ?></h2>

        <?php $class = 'col-sm-12'; ?>

        <div id="content" class="<?php echo $class; ?> vote_content">

          <div id="product-filter-replace">
            <div id="product-filter-loading-overlay"></div>


                  <?php if ($products) { ?>
                    
                    <div id="product-filter-detect">

                      <div class="row">
                        <div class="product-view">
                          <?php foreach ($products as $product) { ?>
                            <?php echo $product; ?>
                          <?php } ?>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-12 text-center"><?php echo $pagination; ?></div>
                      </div>

                    </div> <!-- #product-filter-detect -->

                  <?php } ?>

                  <?php if (!$products) { ?>

                    <p><?php echo $text_empty; ?></p>
                    <div class="buttons hidden">
                      <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
                    </div>

                  <?php } ?>
                    <hr>
                    <h3 class="vote_title">Vote Now</h3>
                      <?= $description; ?>

                    <form id="product_enquiry" method="post" class="form-horizontal">
                        <div class="col-sm-12 product_selection">
                            <?php foreach ($vote_product as $product) { ?>
                                <div class="col-sm-4">
                                    <label> <input type="radio" class="radio_button" name="vote" value="<?= $product['product_id']; ?>">                      
                                    <?= $product['name']; ?>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-12 text-danger" style="text-align:center">Please select one option</div>
                        
                        <div class="col-sm-12 text-center option_box">
                            <div class="form-group">
                                <label class="control-label" for="input-message">Requests (Optional):</label>
                                <textarea name="message" rows="5" id="input-message" placeholder="Message" class="form-control input-theme"></textarea>
                            </div>
                        </div>
            
                    <div class="col-sm-12">
                        <button type="button" id="vote_submit" class="btn btn-primary green_button show margin_auto">Submit</button>
                    </div>
                </form>

          </div> <!-- #product-filter-replace -->
        </div> <!-- #content -->

        <?php echo $column_right; ?></div>
        <?php echo $content_bottom; ?>
    </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">    
    $('.text-danger').css('display','none');
    
    $('#vote_submit').on('click', function(){
        $('.text-danger').css('disply','none');
            if(validated()){
                $.ajax({
                    url: 'index.php?route=product/vote/vote',
                    data: $('#product_enquiry input:checked, #product_enquiry textarea'),
                    dataType: 'json',
                    type: 'post',
                     beforeSend: function() {
                        $('#vote_submit').prop('disabled',true);
                        $('#vote_submit').text('Please Wait');
                    },
                    success: function(json){
//                        $('#vote_submit').reset();

                        $('#vote_submit').prop('disabled',false);
                        $('#vote_submit').text('Enquire Now');
//                        grecaptcha.reset();
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
                                    title: 'Submitted'
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

        if ($('input[name=vote]:checked').length == 0) {
            has_error = true;
            $(".text-danger").show();
        }else{
            $(".text-danger").hide();
        }
        
        if(has_error){
            return false;
        } else {
            return true;
        }
    }
</script>
