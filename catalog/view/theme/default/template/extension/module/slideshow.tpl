<!--<div id="slideshow<?= $module; ?>" class="relative owl-carousel"  style="opacity: 1; width: 100%;">
  <?php foreach ($banners as $banner) { ?>
    <div class="relative h100" style="padding:6.5% 12%;">
    <div class="row " style="width:100%;margin-right: 0px;margin-left: 0px;">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <img src="image/cssbackground/1.png" alt="<?= $banner['title']; ?>" class="img-responsive no-border" style="position: absolute;bottom: 30%;left: -15%;z-index:-1;"/>
          <img src="image/cssbackground/2.png" alt="<?= $banner['title']; ?>" class="img-responsive no-border" style="position: absolute;bottom: -15%;left: -15%;z-index:-1;"/>
          <img src="image/cssbackground/3.png" alt="<?= $banner['title']; ?>" class="img-responsive no-border" style="position: absolute;bottom: -10%;right: -20%;z-index:-1;"/>
          <img src="image/cssbackground/4.png" alt="<?= $banner['title']; ?>" class="img-responsive no-border" style="position: absolute;bottom: 35%;right: -3%;z-index:-1;"/>


          <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive image1" style="position:absolute;transform: rotate(5deg)"/>
          <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive image2" style="position:absolute;transform: rotate(-1deg);"/>
          <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive image3" style="transform: rotate(-7deg);"/>
          <img src="<?= $banner['mobile_image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive visible-xs" style="z-index:2"/>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 slideshow_detail">
            <h3 class="slider_title"><?= $banner['title']; ?></h3>
          <?php if($banner['description']){ ?>
            <div class="slider-slideshow-description w100 absolute-md background-type-<?= $banner['theme']; ?>">
              <div class="container">
                <div class="slider-slideshow-description-texts">
                  <?= $banner['description']; ?>

                  <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
                  <div class="slider-slideshow-description-link" style="text-align:center">
                    <a href="<?= $banner['link']; ?>" class="btn btn-primary green_button">
                      <?= $banner['link_text']; ?>
                    </a>
                  </div>
                  class:slider-slideshow-description-link
                  <?php } ?>
                </div>
                class:slider-slideshow-description-texts
              </div>
              class:container
            </div>
            class:slider-slideshow-description
          <?php } ?>
        </div>
        </div>

      <?php if($banner['link']){ ?>
        <a href="<?= $banner['link']; ?>" class="block absolute position-left-top w100 h100"></a>
      <?php } ?>

    </div>
  <?php } ?>
</div>-->
<div id="slideshow<?= $module; ?>" class="relative owl-carousel"  style="opacity: 1; width: 100%;">
  <?php foreach ($banners as $key=> $banner) { ?>
    <div class="relative h100">
      <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive hidden-xs" />
      <img src="<?= $banner['mobile_image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive visible-xs" />
      <?php if($banner['description']){ ?>
        <div class="slider-slideshow-description w100 absolute position-center-center background-type-<?= $banner['theme']; ?>">
          <div class="container">
          <?php if($banner['textalign'] == 'right') { ?>
                <!-- <div class="slider_title hidden-xs hidden-sm lg-md-width " style="text-align: <?php echo $banner['textalign']; ?>;float:<?php echo $banner['textalign'];?>;padding-left:<?php echo $banner['padleft']; ?>;padding-right:<?php echo $banner['padright']; ?>"> -->
                <div class="slider_title hidden-xs hidden-sm lg-md-width " style="padding-bottom:10%;margin-bottom:<?php echo $banner['padleft']; ?>;margin-left: 40%;text-align:left;float:<?php echo $banner['textalign'];?>;padding-left:<?php echo $banner['padleft']; ?>;padding-right:<?php echo $banner['padright']; ?>">
          <?php }elseif($banner['textalign'] == 'left'){ ?>
                <div class="slider_title hidden-xs hidden-sm lg-md-width " style="padding-bottom:10%;width: 50%;margin-bottom:<?php echo $banner['padleft']; ?>;text-align:left;float:<?php echo $banner['textalign'];?>;padding-left:2%;padding-right:<?php echo $banner['padright']; ?>">
          <?php }else{ ?>
                <div class="slider_title hidden-xs hidden-sm lg-md-width" style="padding-bottom:10%;width: 50%;margin-bottom: <?php echo $banner['padleft']; ?>;text-align: <?php echo $banner['textalign']; ?>;margin-left:auto;margin-right:auto;padding-left:<?php echo $banner['padleft']; ?>;padding-right:<?php echo $banner['padright']; ?>">
          <?php } ?>


            <?php if($banner['title2']) { ?>
              <?php if($banner['textalign'] == 'center') { ?>
                    <h2 class="banner-title" style="text-align:<?php echo $banner['textalign']; ?>;color:<?= $banner['font_color'];?>!important;font-size: calc(50px + (27 - 20) * (100vw - 320px) / (1920 - 320));font-weight:500;"><?= nl2br($banner['title2']); ?></h2>
              <?php }else{ ?>
                    <h2 class="banner-title" style="text-align:left;color:<?= $banner['font_color'];?>!important;font-size: calc(50px + (27 - 20) * (100vw - 320px) / (1920 - 320));font-weight:500;"><?= nl2br($banner['title2']); ?></h2>
              <?php  } ?>

            <?php } ?>

            <?php if($banner['title']) { ?>
              <?php if($banner['textalign'] == 'center') { ?>
                <h2  class="banner-second-title custom-title" style="text-align:<?php echo $banner['textalign']; ?>;color:<?= $banner['font_color'];?>"><?= $banner['title']; ?></h2>
              <?php }else{ ?>
                  <h2  class="banner-second-title" style="text-align:left;color:<?= $banner['font_color'];?>!important;"><?= $banner['title']; ?></h2>
              <?php }?>
            <?php } ?>


            <?php if($banner['description']) { ?>
                <p style="line-height: 0;font-size:16px;color:<?= $banner['font_color'];?>!important;text-align: left">
                  <?= $banner['description']; ?>
                </p>
            <?php } ?>

            <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
                <div class="slider-slideshow-description-link">
                      <?php if($banner['link_text'] != ""){ ?>
                          <a href="<?= $banner['link']; ?>" class="btn btn-primary">
                              <?= $banner['link_text']; ?>
                          </a>
                      <?php } ?>
                </div>
                <!--class:slider-slideshow-description-link-->
            <?php } ?>

              </div>



          </div>
          <!-- container -->



          <div class="visible-xs visible-sm slider-container" style="text-align: center;padding:5px 2px 5px 5px;">
            <?php if($banner['title2']) { ?>
                <h2 style="color:<?= $banner['font_color'];?>!important;font-size: calc(20px + (27 - 20) * (100vw - 320px) / (1920 - 320));font-weight:500;"><?= nl2br($banner['title2']); ?></h2>
              <?php } ?>
            <?php if($banner['title']) { ?>
              <h2 style="text-align:center;color:<?= $banner['font_color'];?>!important;font-size:20px"><?= $banner['title']; ?></h2>
            <?php } ?>

            <?php if($banner['description']) { ?>
              <p style="line-height: 0;font-size:12px;color:<?= $banner['font_color'];?>!important;">
                <?= $banner['description']; ?>
              </p>
            <?php } ?>

            <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
                <div class="slider-slideshow-description-link">
                      <?php if($banner['link_text'] != ""){ ?>
                          <a href="<?= $banner['link']; ?>" class="btn btn-primary">
                              <?= $banner['link_text']; ?>
                          </a>
                      <?php } ?>
                </div>
                <!--class:slider-slideshow-description-link-->
            <?php } ?>

          </div>


            <!--class:slider-slideshow-description-texts-->
          <!--class:container-->


        </div>
        <div class="slider-number">
            <?php for($i=1; $i<=count($banners); $i++){ ?>
                    <span id="slider-counter"><?=$i;?> </span>
            <?php } ?>
        </div>

        <!--class:slider-slideshow-description-->
      <?php } ?>

      <?php if($banner['link']){ ?>
        <!--<a href="<?= $banner['link']; ?>" class="block absolute position-left-top w100 h100"></a>-->
      <?php } ?>






    </div>
  <?php } ?>
</div>
<?php //include('slideshow_script_slick.tpl'); ?>
<?php include('slideshow_script_owl.tpl'); ?>
