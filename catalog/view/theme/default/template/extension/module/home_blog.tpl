<style>
    .<?= $modulename ?> .bk-img {
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
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
    <h2><?= $heading_title ?></h2>
</div>
<?php } ?>
<div class="flex flex-wrap mg-m15">
    <div class="f-col f-col-100p f-col-40p-md mg-b15 mg-b0-md">
        <div class="relative h100 border-all">
            <div class="white-bkground">
            <?php if ($article[0]['thumb']) { ?>
                <a href="<?php echo $article[0]['href']; ?>">
                    <div class="relative pd-b60p bk-img" style="background-image:url('<?= $article[0]['thumb']; ?>');">
                    </div>
                </a>
                <?php if ($article[0]['category']) { ?>
                    <div class="absolute position-left20-top20"><?php echo $article[0]['category']; ?></div>
                <?php } ?>
            <?php } ?>
            </div>
            <div class="pd-20 white-bkground">
                <?php if ($article[0]['name']) { ?>
                    <div class="pd-b15"><a href="<?php echo $article[0]['href']; ?>"><h3><?php echo $article[0]['name']; ?></h3></a></div>
                <?php } ?>

                <?php if ($article[0]['date_added']) { ?>
                    <div class="pd-b15"><?php echo $article[0]['date_added']; ?></div>
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

                <?php if ($article[0]['description']) { ?>
                    <div class="pd-b30"><?php echo $article[0]['description']; ?></div>
                <?php } ?>

                <div class="pd-b15"><a class="" href="<?php echo $article[0]['href']; ?>"><?php echo $button_read_more; ?></a></div>
            </div>
        </div>
    </div>
    <div class="f-col f-col-100p f-col-60p-md pd-l15-md">
        <div class="relative flex flex-wrap mg-b15 border-all">
            <div class="f-col f-col-100p f-col-40p-md white-bkground pd-0">
            <?php if ($article[1]['thumb']) { ?>
                <a href="<?php echo $article[1]['href']; ?>">
                    <div class="relative pd-b60p pd-b100p-md bk-img" style="background-image:url('<?= $article[1]['thumb']; ?>');">
                    </div>
                </a>
                <?php if ($article[1]['category']) { ?>
                    <div class="absolute position-left20-top20"><?php echo $article[1]['category']; ?></div>
                <?php } ?>
            <?php } ?>
            </div>
            <div class="f-col f-col-100p f-col-60p-md pd-20 white-bkground">
                <?php if ($article[1]['name']) { ?>
                    <div class=" pd-b15"><a href="<?php echo $article[1]['href']; ?>"><h3><?php echo $article[1]['name']; ?></h3></a></div>
                <?php } ?>

                <?php if ($article[1]['date_added']) { ?>
                    <div class="pd-b15"><?php echo $article[1]['date_added']; ?></div>
                <?php } ?>

                <?php /*if(!empty($article[1]['tags'])) { ?>
                <div class="pd-b15">
                <ul class="list-inline">
                    <?php foreach($article[1]['tags'] as $each) { ?>
                    <li><a href="<?= $each['href'] ?>" class="btn"><?= $each['name'] ?></a></li>
                    <?php } ?>
                </ul>
                </div>
                <?php }*/ ?>

                <?php if ($article[1]['description']) { ?>
                    <div class="pd-b30"><?php echo $article[1]['description']; ?></div>
                <?php } ?>

                <div class="pd-b15"><a class="" href="<?php echo $article[1]['href']; ?>"><?php echo $button_read_more; ?></a></div>
            </div>
        </div>

        <div class="relative flex flex-wrap border-all">
            <div class="f-col f-col-100p f-col-40p-md white-bkground pd-0">
            <?php if ($article[2]['thumb']) { ?>
                <a href="<?php echo $article[2]['href']; ?>">
                    <div class="relative pd-b60p pd-b100p-md bk-img" style="background-image:url('<?= $article[2]['thumb']; ?>');">
                    </div>
                </a>
                <?php if ($article[2]['category']) { ?>
                    <div class="absolute position-left20-top20"><?php echo $article[2]['category']; ?></div>
                <?php } ?>
            <?php } ?>
            </div>
            <div class="f-col f-col-100p f-col-60p-md pd-20 white-bkground">
                <?php if ($article[2]['name']) { ?>
                    <div class="f16  pd-b15"><a href="<?php echo $article[2]['href']; ?>"><h3><?php echo $article[2]['name']; ?></h3></a></div>
                <?php } ?>

                <?php if ($article[2]['date_added']) { ?>
                    <div class="pd-b15"><?php echo $article[2]['date_added']; ?></div>
                <?php } ?>

                <?php /*if(!empty($article[2]['tags'])) { ?>
                <div class="pd-b15">
                <ul class="list-inline">
                    <?php foreach($article[2]['tags'] as $each) { ?>
                    <li><a href="<?= $each['href'] ?>" class="btn"><?= $each['name'] ?></a></li>
                    <?php } ?>
                </ul>
                </div>
                <?php }*/ ?>

                <?php if ($article[2]['description']) { ?>
                    <div class="pd-b30"><?php echo $article[2]['description']; ?></div>
                <?php } ?>

                <div class="pd-b15"><a class="" href="<?php echo $article[2]['href']; ?>"><?php echo $button_read_more; ?></a></div>
            </div>
        </div>
    </div>
</div>
<div class="pd-t30 pd-b30 text-center"><a href="<?= $view_more_link ?>" class="btn btn-primary btn-img"><?= $button_view_more ?></a></div>
