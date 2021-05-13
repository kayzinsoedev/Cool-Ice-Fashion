<style>
    .review_slide_1{
        width:80%;
        margin:50px 10%;
    }
    .review_slide_1 #featured_review_slide_1 .review_description{
        text-align: center;
        line-height: 25px;
        margin:20px 50px;
    }
    .review_slide_1 h2{
        margin-bottom:40px!important;
    }
    .review_slide_1 #featured_review_slide_1 .review_name_position{
        text-align:center;
        margin:30px;
    }
    .review_slide_1 #featured_review_slide_1 .review_name_position span{
        font-weight: bold;
    }
    .review_slide_1 #featured_review_slide_1 .owl-nav > *{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        justify-content: center;
        width: 0px;
        height: auto;
    }
    .review_slide_1 #featured_review_slide_1 .owl-nav img{
        width:50px;
        height:50px;
    }
    .review_slide_1 #featured_review_slide_1 .owl-prev, .review_slide_1 #featured_review_slide_1 .owl-next{
        display: block!important;
    }
    .review_slide_1 #featured_review_slide_1 .owl-nav > * {
        width: 50px!important;
    }
    
    @media screen and (max-width: 991px) {
        .review_slide_1{
            width:100%;
            margin:50px 0%;
        }
    }
    @media screen and (max-width: 767px) {    
        .review_slide_1 #featured_review_slide_1 .owl-nav img {
            width: 30px;
            height: 30px;
        }
    }
    @media screen and (max-width: 540px) {
        .featured-module .featured {
            padding: 0px 0px;
        }
    }
    
</style>
<div class="featured-module review_slide_1">
  <div class="featured section">
    <div class="table_heading text-center">
      <h2 class="text-center target-heading">
          <?= $title; ?>
      </h2>
    </div>
    <div class="owl-carousel" id="featured_review_slide_1">
      <?php foreach ($reviews as $review) { ?>
        <div class="review_box">
            <div class="review_description">
                <?php echo html_entity_decode($review['text']); ?>
            </div>
            <div class="review_name_position">
                <span><?= $review['author']; ?></span>
            </div>
        </div>
      <?php } ?>
    </div>
    <!--<div class="fullwidth text-center">
        <button type="button" onclick="window.location.href='<?= $view_more; ?>'" class="btn btn-primary">View More</button>
    </div>-->
    <script type="text/javascript">

      $(window).load(function(){
        setTimeout(function () {
          featured_review_carousel();
        }, 250)
      });

      function featured_review_carousel(){
        var owl_review =  $("#featured_review_slide_1").owlCarousel({
          items: 1,
          margin: 30,
          loop: false,
          dots: false,
          nav: true,
          navText: ["<img src='image/cssbackground/left.png' alt='left'>","<img src='image/cssbackground/right.png' alt='left'>"],
          responsive: {
            0: {
              items: 1,
              margin: 0,
            },
            480: {
              items: 1,
              margin: 10,
            },
            768: {
              items: 1,
              margin: 15,
            },
            992:{
              items: 1,
              margin: 15,
            },
            1200: {
              items: 1,
              margin: 20,
            }
          },
          onInitialized: function () {
            $('#featured_review_slide_1').addClass('');

            // Fix Looping issue when add to cart
            $('#featured_review_slide_1 .input-number').each(function(index, value){
                $old=$(this).attr('id');
                $new=$old + 'a' + index;

                $(this).attr('id', $new); // Change add to cart
                $btn_cart = $(this).parents('div.owl-item').find('.btn-cart');

                if($btn_cart.length){
                  $onclick = $btn_cart.attr('onclick');
                  $onclick = $onclick.replace($old, $new);
                  $btn_cart.attr('onclick', $onclick);
                }
            });
            
            // Update add-to-cart
            $(window).resize();

            // Fix if content occupied more than window view
            $slider_window_view_width = $('#featured_review_slide_1').width();
            $content_width = 0;
            $content_margin = 0;
            $single_margin = 0;

            $('#featured_review_slide_1 .owl-item.active').each(function(){
              $content_width += $(this).outerWidth();

              $single_margin = $(this).css('margin-right');
              $single_margin = $single_margin.replace('px','');
              $content_margin += parseFloat($single_margin);
            });

            $content_width += $content_margin;
            $content_width -= $single_margin;
            
            //cl($content_width);
            //cl($slider_window_view_width);

            if($content_width > $slider_window_view_width){
              setTimeout(function(){
                $('#featured_review_slide_1').trigger('refresh.owl.carousel');
              }, 100);
            }

          },
          onRefreshed: function(){ 
            $(".review .owl-item .product-layout .product-thumb").removeAttr("style");

            var height = 0;

            $(".review .owl-item").each(function(){
              if(height < $(this).height()) height = $(this).height();            
            });

            $(".review .owl-item .product-layout .product-thumb").css('min-height', height);
          }
        });
      }
    </script>
  </div>
</div>