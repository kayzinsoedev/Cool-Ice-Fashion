<?php echo $header; ?>
<div class="container">
    <div class="white_background">
        <?php echo $content_top; ?>
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <div class="row"><?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <div id="content" class="<?php echo $class; ?>">
                <h2><?php echo $heading_title; ?></h2>
                <div class="ncategory_link">
                    <?php if(isset($ncategories)){ ?>
                        <?php foreach($ncategories as $category){
                            if($category_active == $category['ncategory_id']){ ?>
                                <a href="<?php echo $category['href']; ?>" class="active"> <?= $category['name']; ?> </a>
                            <?php }else{ ?>
                                <a href="<?php echo $category['href']; ?>"> <?= $category['name']; ?> </a>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <?php echo $description; ?>
            </div>
            <?php echo $column_right; ?></div>
            <?php echo $content_bottom; ?>
        </div>
</div>
<?php echo $footer; ?> 