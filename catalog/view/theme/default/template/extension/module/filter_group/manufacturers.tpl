<?php if($manufacturers){ ?>

<div id="side-manufacturer">
	<div class="list-group">
		<div class="list-group-item item-header list-grouped"><?= $heading_title; ?> <i class="la la-angle-down"></i></div>
		<div class="list-group-item list-grouped-dropdown flyout-filter">
			<?php foreach($manufacturers as $manufacturer){ ?>
			<div class="checkbox-style">
				<?php if($manufacturer['checked']){ ?>
				<input type="checkbox" name="manufacturer_ids[]" id="check-brand-<?= $manufacturer['mid']; ?>" value="<?= $manufacturer['mid']; ?>" checked />
				<?php }else{ ?>
				<input type="checkbox" name="manufacturer_ids[]" id="check-brand-<?= $manufacturer['mid']; ?>" value="<?= $manufacturer['mid']; ?>" />
				<?php } ?> <label for="check-brand-<?= $manufacturer['mid']; ?>"><?= $manufacturer['name']; ?></label>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php } ?>