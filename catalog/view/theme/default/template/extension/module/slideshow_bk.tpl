<div class="container">
  <div id="slideshow<?= $module; ?>" class="relative owl-carousel"  style="opacity: 1; width: 100%;">
    <?php foreach ($banners as $banner) { ?>
      <div class="relative h100">
        <img data-src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive hidden-xs owl-lazy" />
        <img data-src="<?= $banner['mobile_image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive visible-xs owl-lazy" />
        <div class="overlay_icon">
          <img src="image/<?= $banner['watermark_icon']; ?>" alt="logo"/>
        </div>
        <?php if($banner['description']){ ?>
          <div class="slider-slideshow-description w100 absolute position-center-center background-type-<?= $banner['theme']; ?>">
            <div class="container">
              <?php if($banner['textalign'] != 'center') { ?>
                <div class="hidden-xs hidden-sm lg-md-width" style="text-align: <?php echo $banner['textalign']; ?>;float:<?php echo $banner['textalign'];?>;padding-left:<?php echo $banner['padleft']; ?>;padding-right:<?php echo $banner['padright']; ?>">
              <?php } else { ?>
                <div class="hidden-xs hidden-sm lg-md-width" style="text-align: <?php echo $banner['textalign']; ?>;margin-left:auto;margin-right:auto;padding-left:<?php echo $banner['padleft']; ?>;padding-right:<?php echo $banner['padright']; ?>">
              <?php } ?>

                  <?php if($banner['title2']) { ?>
                    <h2 style="text-align:center;color:<?= $banner['font_color'];?>!important;font-size: calc(50px + (27 - 20) * (100vw - 320px) / (1920 - 320));font-weight:500;"><?= nl2br($banner['title2']); ?></h2>
                  <?php } ?>
                  <?php if($banner['title']) { ?>
                    <h2 style="text-align:center;color:<?= $banner['font_color'];?>!important;"><?= $banner['title']; ?></h2>
                  <?php } ?>

                  <?php if($banner['description']) { ?>
                  <p style="line-height: 2;font-size:16px;color:<?= $banner['font_color'];?>!important;text-align: center">
                    <?= $banner['description']; ?>
                  </p>
                  <?php } ?>
                </div>

              <div class="visible-xs visible-sm" style="text-align: center;padding:5px 2px 5px 5px;">
                <?php if($banner['title2']) { ?>
                    <h2 style="color:<?= $banner['font_color'];?>!important;font-size: calc(20px + (27 - 20) * (100vw - 320px) / (1920 - 320));font-weight:500;"><?= nl2br($banner['title2']); ?></h2>
                  <?php } ?>
                <?php if($banner['title']) { ?>
                  <h2 style="text-align:center;color:<?= $banner['font_color'];?>!important;font-size:20px"><?= $banner['title']; ?></h2>
                <?php } ?>

                <?php if($banner['description']) { ?>
                  <p style="line-height: 2;font-size:12px;color:<?= $banner['font_color'];?>!important;">
                    <?= $banner['description']; ?>
                  </p>
                <?php } ?>

                <!-- <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
                <div class="slider-slideshow-description-link" style="margin-top:10px">
                  <a href="<?= $banner['link']; ?>" class="btn btn-primary banner_btn" style="border-radius: 5px;padding:10px;font-size:13px">
                    <?= $banner['link_text']; ?>&nbsp;&nbsp;
                    <img src="image/catalog/Slicing/Header/arrow-btn.png" alt="btn arrow" style="width:17px"/>
                  </a>
                  </a>
                </div>
                <?php } ?> -->
              </div>

              <!-- <div class="slider-slideshow-description-texts">
                <?= $banner['description']; ?>

                <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
                <div class="slider-slideshow-description-link">
                  <a href="<?= $banner['link']; ?>" class="btn btn-primary">
                    <?= $banner['link_text']; ?>
                  </a>
                </div>
                <?php } ?>
              </div> -->
              <!--class:slider-slideshow-description-texts-->
            </div>
            <!--class:container-->
          </div>
          <!--class:slider-slideshow-description-->
        <?php } ?>

        <?php if($banner['link']){ ?>
          <a href="<?= $banner['link']; ?>" class="block absolute position-left-top w100 h100"></a>
        <?php } ?>

      </div>
    <?php } ?>
  </div>
  <?php //include('slideshow_script_slick.tpl'); ?>
  <?php include('slideshow_script_owl.tpl'); ?>
</div>
