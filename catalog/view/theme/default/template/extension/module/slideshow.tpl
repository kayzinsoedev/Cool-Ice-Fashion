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
  <?php foreach ($banners as $banner) { ?>
    <div class="relative h100">
      <img src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive hidden-xs" />
      <img src="<?= $banner['mobile_image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive visible-xs" />
      <?php //if($banner['description']){ ?>
        <div class="slider-slideshow-description w100 absolute position-center-center background-type-<?= $banner['theme']; ?>">
      <h3 class="slider_title"><?= $banner['title']; ?></h3>
            <div class="slider-slideshow-description-texts">
              <?= $banner['description']; ?>

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
        <!--class:slider-slideshow-description-->
      <?php //} ?>
      
      <?php if($banner['link']){ ?>
        <!--<a href="<?= $banner['link']; ?>" class="block absolute position-left-top w100 h100"></a>-->
      <?php } ?>
      
    </div>
  <?php } ?>
</div>
<?php //include('slideshow_script_slick.tpl'); ?>
<?php include('slideshow_script_owl.tpl'); ?>