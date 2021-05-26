<style>
    .<?= $modulename ?> .bk-img {
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        min-height: 300px;
        width: 100%;
    }

    .<?= $modulename ?> .border-all {
        border: 1px solid #ccc;
    }

    .<?= $modulename ?> .white-bkground {
        background-color: #fff;
    }

    .<?= $modulename ?> .mg-b15 {
        margin-bottom: 15px;
    }

    .<?= $modulename ?> .pd-20 {
        padding: 20px;
    }

    .<?= $modulename ?> .f-col {
        position: relative;
        min-height: 1px;
        width: 100%;
    }

    .<?= $modulename ?> .position-left20-top20 {
        top: 20px;
        left: 20px;
    }

    .<?= $modulename ?> .f-col-100p {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }

    .<?= $modulename ?> .pd-b60p {
        padding-bottom: 60%;
    }

    @media (min-width: 992px) {
        .<?= $modulename ?> .pd-md-b70p {
            padding-bottom: 70%;
        }

        .<?= $modulename ?> .f-col-40p-md {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 40%;
            flex: 0 0 40%;
            max-width: 40%;
        }

        .<?= $modulename ?> .f-col-60p-md {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 60%;
            flex: 0 0 60%;
            max-width: 60%;
        }

        .<?= $modulename ?> .pd-b100p-md {
            padding-bottom: 100%;
        }

        .<?= $modulename ?> .mg-b0-md {
            margin-bottom: 0;
        }

        .<?= $modulename ?> .pd-l15-md {
            padding-left: 15px;
        }
    }

    @media (min-width: 1200px) {
        .<?= $modulename ?> .pd-b100p-lg {
            padding-bottom: 100%;
        }
    }
</style>
<?php if($heading_title) { ?>
<div class="text-center">
    <h2 class="home-fblog-title"><?= $heading_title ?></h2>
</div>
<?php } ?>

<?php if($heading_descripton) { ?>
<div class="text-center">
    <p class="fblog-desc"><?=$heading_descripton;?></p>
</div>
<?php } ?>

<div id="home-featured-blog">
  <!-- <?php debug($article); ?> -->
<?php foreach($article as $arti){ ?>

<div class="flex flex-wrap mg-m15">

            <div class="white-bkground">
            <?php if ($arti['thumb']) { ?>
                <a href="<?php echo $article[0]['href']; ?>">
                    <div class="relative pd-b60p bk-img" style="background-image:url('<?= $arti['thumb']; ?>');">
                    </div>
                </a>
                <?php if ($arti['category']) { ?>
                    <div class="absolute position-left20-top20"><?php echo $arti['category']; ?></div>
                <?php } ?>
            <?php } ?>
            </div>
            <div class="pd-20 white-bkground">
                <?php if ($arti['name']) { ?>
                    <div class="pd-b15 home-fblog-name"><a href="<?php echo $arti['href']; ?>"><h3><?php echo $arti['name']; ?></h3></a></div>
                <?php } ?>

                <?php if ($arti['date_added']) { ?>
                      <div class="pd-b15 home-fblog-date"><h3><?php echo $arti['date_added']; ?></h3></div>
                <?php } ?>

                <?php /*if(!empty($article[0]['tags'])) { ?>
                <div class="pd-b15">
                <ul class="list-inline">
                    <?php foreach($article[0]['tags'] as $each) { ?>
                    <li><a href="<?= $each['href'] ?>" class="btn"><?= $each['name'] ?></a></li>
                    <?php } ?>
                </ul>
                </div>
                <?php }*/ ?>

                <?php if ($arti['description']) { ?>
                    <div class="pd-b30 home-fblog-desc"><?php echo $arti['description']; ?></div>
                <?php } ?>

                <div class="pd-b15 home-fblog-readmore-btn"><a class="" href="<?php echo $arti['href']; ?>"><?php echo $button_read_more." >>"; ?></a></div>
            </div>
        <!-- </div> -->
    <!-- </div> -->

</div>

<?php } ?>

</div>
<div class="pd-t30 pd-b30 text-center"><a href="<?= $view_more_link ?>" class="btn btn-img home-fblog-btn">View All</a></div>



<script>
    $(window).load(function(){
        setTimeout(function (){
            home_featured_blog();
        }, 10);
    });

function home_featured_blog(){
    $("#home-featured-blog").on('init', function (event, slick) {
      $('#home-featured-blog').parent().removeAttr('style');
    });

  $("#home-featured-blog").slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 2,
    responsive: [
      {
        breakpoint: 1201,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 1025,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 993,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 769,
        settings: {
          arrows: false,
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 541,
        settings: {
          slidesToShow: 1,
          arrows: false,
          autoplay: true,
        }
      },
      {
        breakpoint: 415,
        settings: {
          slidesToShow: 1,
          arrows: false,
        }
      },
      {
        breakpoint: 376,
        settings: {
          slidesToShow: 1,
          arrows: false,

        }
      }
    ],
    prevArrow: "<div class='pointer slick-nav left prev absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-left fa-2em'></i></div></div>",
    nextArrow: "<div class='pointer slick-nav right next absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-right fa-2em'></i></div></div>",
  });

}
</script>
