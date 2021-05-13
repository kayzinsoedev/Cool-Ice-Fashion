<?php echo $header; ?>
<div class="container contact_box">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <?php echo $content_top; ?>
    
            <div class="panel panel-default home-contact-us">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                        <?php echo html_entity_decode($geomap); ?>
                        <?php /*if ($geocode || $google_map) { ?>
                                <div data-id="gmap_contact" data-toggle="gmap" class="gmap"
                                        <?php if($google_map){ ?>
                                                data-lat="<?= $google_map['lat']; ?>" 
                                                data-lng="<?= $google_map['lng']; ?>" 
                                                data-store="<?= $google_map['store']; ?>" 
                                                data-address="<?= $google_map['address']; ?>" 
                                        <?php } ?>
                                        data-geo="<?= $geocode; ?>"
                                >
                                        <div id="gmap_contact"></div>
                                </div>
                        <?php }*/ ?>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="col-sm-12">
                                <h2><?= "Contact Us"; ?></h2>
                            </div>
                            <div class="col-sm-12">
                                <address>
                                    <?= html("Address: ".$address); ?>
                                </address>
                            </div>
                            <div class="col-sm-12">
                                <?php if ($open) { ?>
                                    <strong><?= "Opening/Self-collection Hours"; ?></strong><br />
                                    <?= html_entity_decode($open); ?><br />
                                    <br />
                                <?php } ?>
                            </div>
                            <div class="col-sm-12">
                                <a href="<?= $contact_page; ?>" class="btn btn-primary green_button">Contact Us Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>