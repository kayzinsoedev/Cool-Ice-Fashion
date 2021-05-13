<div class="about_content information_content about_box">
    <div class="rows">
        <?php foreach($contents as $content){ ?>
            <div class="col-sm-12" style="margin-bottom:100px;">
                <div class="col-lg-6 col-md-12 <?php if($content['position'] != 1){echo "image_center";} ?> ">
                    <?php if($content['position'] == 1){
                        if($content['title']){?>
                            <h3><?= $content['title']; ?></h3>
                        <?php } ?>
                        <?= html_entity_decode($content['description']); 
                    }else{ ?>
                        <img src="image/<?= $content['image']; ?>" class="img-responsive left">
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-12 <?php if($content['position'] != 0){echo "image_center";} ?>">
                    <?php if($content['position'] == 0){
                        if($content['title']){?>
                            <h3><?= $content['title']; ?></h3>
                        <?php } ?>
                        <?= html_entity_decode($content['description']); 
                    }else{ ?>
                        <img src="image/<?= $content['image']; ?>" class="img-responsive right">
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>