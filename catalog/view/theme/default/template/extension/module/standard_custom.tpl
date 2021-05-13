<div class="box_content">
    <div class="rows">
        <div class="col-sm-12" style="padding:0 0;">
            <div class="col-sm-12 col-md-6 col-lg-6 remove_padding" style="margin-top:20px;padding-left:0px">
                <div class="image_box relative <?php if($link1 != ''){echo 'pointer'; } ?>" style="background-image:url('image/<?= $image1; ?>');background-size: contain;background-size: cover;" <?php if($link1 != ""){?> onclick="window.location.href='<?= $link1; ?>'" <?php } ?> >
                    <div class="box_overlay absolute">
                        <div class="box_bottom absolute position-bottom-left">
                            <h3><?= $title1; ?></h3>
                            <?= html_entity_decode($description1); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6 remove_padding" style="margin-top:20px;padding-right:0px">
                <div class="image_box relative <?php if($link2 != ''){echo 'pointer'; } ?>" style="background-image:url('image/<?= $image2; ?>');background-size: contain;background-size: cover;" <?php if($link2 != ""){?> onclick="window.location.href='<?= $link2; ?>'" <?php } ?> >
                    <div class="box_overlay absolute">
                        <div class="box_bottom absolute position-bottom-left">
                            <h3><?= $title2; ?></h3>
                            <?= html_entity_decode($description2); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>