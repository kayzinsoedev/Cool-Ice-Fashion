<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?=$direction; ?>" lang="<?=$lang; ?>">
<!--<![endif]-->

<?= $head_tags ?>

<body class="<?=$class; ?> <?= $seo_enabled?'short_hand':''; ?> <?= $isMobile; ?>">
    
    <script>
      // window.fbAsyncInit = function() {
      //   FB.init({
      //     appId      : '{your-app-id}',
      //     cookie     : true,
      //     xfbml      : true,
      //     version    : '{api-version}'
      //   });
          
      //   FB.AppEvents.logPageView();   
          
      // };
    
      // (function(d, s, id){
      //    var js, fjs = d.getElementsByTagName(s)[0];
      //    if (d.getElementById(id)) {return;}
      //    js = d.createElement(s); js.id = id;
      //    js.src = "https://connect.facebook.net/en_US/sdk.js";
      //    fjs.parentNode.insertBefore(js, fjs);
      //  }(document, 'script', 'facebook-jssdk'));
    </script>

	<div id="loading_wrapper">
		<div class="spinner">
		  <div class="dot1"></div>
		  <div class="dot2"></div>
		</div>
	</div>

	<?= $fb_messanger; ?>
	<div class="x213"><h1 id="page_heading_title" ><?= $title; ?></h1></div>
	<header class="fixed-header" >
	    <div class="header_title header_banner">
	        <?php if($header_icon != ""){?>
	            <img src="<?= "image/".$header_icon ?>" class="img_responsive"> 
	        <?php } ?>
	        <?php if($header_title != ""){?>
	            <?= html_entity_decode($header_title); ?>
	        <?php } ?>
	    </div>
	    <div class="container">
	    </div>
		<div class="container">
			
			<div class="header-container">

				<div class="header-mobile-links visible-xs visible-sm">
					<div class="header-links">
						<a id="mobileNav" href="#sidr" class="pointer esc">
							<i class="fa fa-bars"></i>
						</a>
						<span class="hidden-xs hidden-sm">
							<?= $pop_up_search; ?>
						</span>
					</div>
				</div>

				<div class="">
				</div>
				<div class="header-logo">
					<?php if ($logo) { ?>
						<a class="header-logo-image" href="<?=$home; ?>">
							<img src="<?=$logo; ?>" title="<?=$name; ?>" alt="<?=$name; ?>" class="img-responsive" />
						</a>
					<?php } else { ?>
						<a class="header-logo-text" href="<?=$home; ?>"><?=$name; ?></a>
					<?php } ?>
				</div>
				<div class="header-top">

<!--					<div class="search_container">
						<span class="hidden-xs hidden-sm"><?= $search; ?></span>
					</div>-->
					<div class="flavours_container">
                                            <button class="flavours_button" onclick="window.location.href='<?= $flavours; ?>'">Flavours In Store</button>
					</div>
					<span class="hidden-xs hidden-sm">
						<?= $login_part; ?>
					</span>
					<?= $cart; ?>

					<span class="hidden" >
                                                <?= $enquiry; ?>
						<?=$currency; ?>
						<?=$language; ?>
						<?=$wishlist; ?>
					</span>
				</div>


				<div class="header-menu hidden-xs hidden-sm">
					<?= $menu; ?>
				</div>
			</div>
		</div>
	</header>

	<div id="sidr">
		<div class="header-mobile">
			<div class="mobile-account relative">
				<?php if($logged){ ?> 
				<a href="<?= $account; ?>">
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
					<?= $text_account; ?></a>
				<a href="<?= $logout; ?>">
					<i class="fa fa-sign-out" aria-hidden="true"></i>
					<?= $text_logout; ?></a>
				<?php }else{ ?> 
				<a href="<?= $login; ?>">
					<i class="fa fa-user-circle-o" aria-hidden="true"></i>
					<?= $text_login; ?></a>
				<a href="<?= $register; ?>">
					<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					<?= $text_register; ?></a>
				<?php } ?>
			</div>
			<div class="mobile-search">
				<?= $search; ?>
			</div>
		</div>
		<?= $mobile_menu; ?>
	</div>


	<?= $page_banner; ?>