<h3 class="category_title"><?= $heading_title; ?></h3>
<h3><?= $product_name; ?></h3>
<ul class="list-unstyled">
  <?php if ($manufacturer) { ?>
  <li><?= $text_manufacturer; ?> <a href="<?= $manufacturers; ?>"><?= $manufacturer; ?></a></li>
  <?php } ?>
  <!--<li><?= $text_model; ?> <?= $model; ?></li>-->
  <?php if ($reward) { ?>
  <!--<li><?= $text_reward; ?> <?= $reward; ?></li>-->
  <?php } ?>
  <!--<li><?= $text_stock; ?> <?= $stock; ?></li>-->
</ul>
<?php if ($price && !$enquiry) { ?>
<ul class="list-unstyled">
  <?php if (!$special) { ?>
  <li>
    <div class="product-price old-prices" ><?= $price; ?></div>
  </li>
  <?php } else { ?>
  <li><span style="text-decoration: line-through;" class="old-prices"><?= $price; ?></span></li>
  <li>
    <div class="product-special-price new-prices"><?= $special; ?></div>
  </li>
  <?php } ?>
  <?php if ($tax) { ?>
  <li class="product-tax-price product-tax" ><?= $text_tax; ?> <?= $tax; ?></li>
  <?php } ?>
  <?php if ($points) { ?>
  <li><?= $text_points; ?> <?= $points; ?></li>
  <?php } ?>
</ul>
<?php } ?>

<?php if($enquiry){ ?>
    <div class="enquiry-block">
      <div class="label label-primary">
        <?= $text_enquiry_item; ?>
      </div>
    </div>
<?php } ?>
<div class="product-description">
  <?= $description; ?>
</div>
<ul class="list-unstyled">
    <?php if ($discounts) { ?>
    <li>
        <hr>
    </li>
    <?php foreach ($discounts as $discount) { ?>
        <li>Buy <?= $discount['quantity']; ?><?= $text_discount; ?><?= $discount['price']; ?> each</li>
    <?php } ?>
  <?php } ?>
</ul>
<?php include_once('product_options.tpl'); ?>


<?php if($share_html){ ?>

<div class="clearfix"></div>
<div class="input-group-flex">
    <span><?= $text_share; ?></span>
    <div><?= $share_html; ?></div>
</div>
<?php } ?>
<div class="clearfix"></div>
<?= $waiting_module; ?>

<?php if ($review_status) { ?>
<div class="rating">
  <p>
    <?php for ($i = 1; $i <= 5; $i++) { ?>
    <?php if ($rating < $i) { ?>
    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
    <?php } else { ?>
    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
    <?php } ?>
    <?php } ?>
    <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?= $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?= $text_write; ?></a></p>
</div>
<?php } ?>