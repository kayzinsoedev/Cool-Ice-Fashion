<h2><?=$main_title; ?></h2>
<p><?=$description; ?></p>

<div class="box_content">
    <div class="rows">
        <div class="col-sm-12 home-cat-box" style="padding:0 0;">
            <?php foreach($items as $item) { ?>
            <div class="col-sm-12 col-md-3 col-lg-3 remove_padding home-cat-inner" style="margin-top:20px;padding-left:0px">
                <div class="image_box relative <?php if($item['link'] != ''){echo 'pointer'; } ?>" style="background:url('image/<?= $item['image']; ?>')no-repeat;min-height: 260px;background-size: contain;background-size: cover;" <?php if($item['link'] != ""){?> onclick="window.location.href='<?= $item['link']; ?>'" <?php } ?> >
                    <div class="box_overlay absolute" style="border: 4px dotted <?=$item['text_color'];?> ;border-radius: 10px ;">
                        <div class="home-cat-box box_bottom absolute">
                        <!-- <div class="box_bottom absolute position-bottom-left"> -->
                            <h3 class="home-cat-title" style="color:<?=$item['text_color'];?>"><?= $item['title']; ?></h3>
                            <span class="home-cat-txt" style="color:<?=$item['text_color'];?>"><?=$item['text']." >" ; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
</div>


<!-- <div class="box_content">
    <div class="rows">
        <div class="col-sm-12" style="padding:0 0;">
            <div class="col-sm-12 col-md-3 col-lg-3 remove_padding" style="margin-top:20px;padding-left:0px">
                <div class="image_box relative <?php if($link1 != ''){echo 'pointer'; } ?>" style="background-image:url('image/<?= $image1; ?>');background-size: contain;background-size: cover;" <?php if($link1 != ""){?> onclick="window.location.href='<?= $link1; ?>'" <?php } ?> >
                    <div class="box_overlay absolute">
                        <div class="box_bottom absolute position-bottom-left">
                            <h3 class="home-cat-title"><?= $title1; ?></h3>
                            <span class="home-cat-txt"><?= $text1; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 remove_padding" style="margin-top:20px;padding-right:0px">
                <div class="image_box relative <?php if($link2 != ''){echo 'pointer'; } ?>" style="background-image:url('image/<?= $image2; ?>');background-size: contain;background-size: cover;" <?php if($link2 != ""){?> onclick="window.location.href='<?= $link2; ?>'" <?php } ?> >
                    <div class="box_overlay absolute">
                        <div class="box_bottom absolute position-bottom-left">
                          <h3 class="home-cat-title"><?= $title2; ?></h3>
                          <span class="home-cat-txt">><?= $text2; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 remove_padding" style="margin-top:20px;padding-left:0px">
                <div class="image_box relative <?php if($link1 != ''){echo 'pointer'; } ?>" style="background-image:url('image/<?= $image3; ?>');background-size: contain;background-size: cover;" <?php if($link3 != ""){?> onclick="window.location.href='<?= $link3; ?>'" <?php } ?> >
                    <div class="box_overlay absolute">
                        <div class="box_bottom absolute position-bottom-left">
                            <h3 class="home-cat-title"><?= $title3; ?></h3>
                            <span class="home-cat-txt"><?= $text3; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 remove_padding" style="margin-top:20px;padding-left:0px">
                <div class="image_box relative <?php if($link1 != ''){echo 'pointer'; } ?>" style="background-image:url('image/<?= $image4; ?>');background-size: contain;background-size: cover;" <?php if($link4 != ""){?> onclick="window.location.href='<?= $link4; ?>'" <?php } ?> >
                    <div class="box_overlay absolute">
                        <div class="box_bottom absolute position-bottom-left">
                            <h3 class="home-cat-title"><?= $title4; ?></h3>
                            <span class="home-cat-txt"><?= $text4; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
