<div class="row max-offset">
	<div class="module-instagram">
		<?php if(!empty($instagrams)) {?>
		<?php if($entry_instagram) { ?>
		<div class="section-title text-center pd-t30 pd-md-t60">
			<?php echo $entry_instagram; ?>
		</div>
		<?php } ?>
		<div class="instagram">

			<?php foreach ($instagrams as $instagram){ ?>
			<div class="item <?php echo $hover_effect;?>">
				<a href="<?php echo $instagram['href'];?>" target="_blank" data-like="<?php echo $instagram['likes'];?>" title="<?php echo $instagram['text'];?>">
					<i class="fa fa-heart" aria-hidden="true"></i>
					<?php /* ?>
					<img src="<?php echo $instagram['img'];?>" alt="<?php echo $instagram['text'];?>">
					<?php */ ?>
					<div style="background-image:url('<?php echo $instagram['img'];?>'); background-size:cover; background-position:center; background-repeat:no-repeat; padding-bottom:100%;"></div>
				</a>
			</div>
			<?php } ?>

		</div>
		<?php } ?>
	</div>
</div>
<style>
	.module-instagram .slick-prev:before,
	.module-instagram .slick-next:before {
		color: <?php echo $color;
		?>;
	}

	.module-instagram h4 {
		text-align: <?php echo $text_align;
		?>
	}

	.instagram .item .fa:before {
		color: <?php echo $heart_color;
		?>
	}

	.instagram .item a:before {
		color: <?php echo $heart_text_color;
		?>
	}

	<?php if($center_mode): ?>.slick-slide {
		opacity: .2;
		transition: opacity .3s linear 0s;
	}

	.slick-slide.slick-active.slick-center {
		opacity: 1;
	}

	<?php endif;
	?>
</style>
<?php if($use_plugin): ?>
<?php //if($entry_instagram) {?>
<script>
	$('.instagram ').slick({
		slidesToShow: <?php echo $slidesToShow;?>,
		slidesToScroll: <?php echo $slidesToScroll ?>,
		autoplay: <?php echo $autoplay; ?>,
		autoplaySpeed: <?php echo $autoplaySpeed; ?>,
		dots: <?php echo $dots; ?>,
		arrows: <?php echo $arrows; ?>,
		<?php echo ($center_mode) ? "centerMode : $center_mode," : ''; ?>
		responsive: [{
				breakpoint: 1024,
				settings: {
					slidesToShow: <?php echo $slidesToShow; ?>,
					slidesToScroll: <?php echo $slidesToScroll ?>,
					infinite: true,
					arrows: false
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: <?php echo $slidesToShowTablet; ?>,
					slidesToScroll: <?php echo $slidesToScrollTablet; ?>,
					arrows: false,
					dots: true,
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: <?php echo $slidesToShowCelphone; ?>,
					slidesToScroll: <?php echo $slidesToScrollCelphone; ?>,
					arrows: false,
					dots: true,
				}
			}
		]
	});
</script>
<?php //} ?>
<?php endif; ?>