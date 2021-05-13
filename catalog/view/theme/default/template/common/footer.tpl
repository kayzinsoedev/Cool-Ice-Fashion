<div id="footer-area">
<!-- WhatsHelp.io widget -->
<script type="text/javascript">
    (function () {
        var options = {
            whatsapp: "<?= $config_whatsapp_telephone; ?>", // WhatsApp number
            call_to_action: "Message us", // Call to action
            position: "right", // Position may be 'right' or 'left'
        };
        var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
        var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
    })();
</script>
<!-- /WhatsHelp.io widget -->
<?php if($mailchimp){ ?>
<!--	<div class="newsletter-section text-center">
		<?php //$mailchimp; ?>
	</div>-->
<?php } ?>

<footer>
	<div class="container">
            <div class="footer_box">
		<div class="footer-upper-contet">

			<?php if ($menu) { ?>
                            <?php foreach($menu as $links){ ?>
				<div class="footer-contact-links">
					<h5>
                                            <?php if($links['href'] != '#'){ ?>
						<?= $links['name']; ?>
                                            <?php }else{ ?>
						<a href="<?= $links['href']; ?>" 
                                                    <?php if($links['new_tab']){ ?>
                                                        target="_blank"
                                                    <?php } ?>
                                                >
                                                <?= $links['name']; ?></a>
                                            <?php } ?>
					</h5>
                                    <?php if($links['child']){ ?>
					<ul class="list-unstyled">
						<?php foreach ($links['child'] as $each) { ?>
						<li><a href="<?= $each['href']; ?>"
							<?php if($each['new_tab']){ ?>
								target="_blank"
							<?php } ?>
							
							>
								<?= $each['name']; ?></a></li>
						<?php } ?>
					</ul>
                                    <?php } ?>
				</div>
                            <?php } ?>
			<?php } ?>
			<?php if($mailchimp){ ?>
                    <?= $mailchimp; ?>
            <?php }else{ ?>
                <div id="mi1965283135" class="mailchimp-integration box" style="flex:1!important;"> 
                    <div class="box-heading"> </div> 
                    <div class="box-content">
                    </div> 
                    <?php if($social_icons){ ?>
                        <div class="footer-social-icons">
                                <?php foreach($social_icons as $icon){ ?>
                                <a href="<?= $icon['link']; ?>" title="<?= $icon['title']; ?>" alt="
                                        <?= $icon['title']; ?>" target="_blank">
                                        <img src="<?= $icon['icon']; ?>" title="<?= $icon['title']; ?>" class="img-responsive" alt="<?= $icon['title']; ?>" />
                                </a>
                                <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
                    
		</div>
		
		<hr/>
		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center">
				<p><?= $powered.". ".$text_right; ?></p>
			</div>
			<div class="col-xs-12 col-sm-12 text-sm-right footer_text">
				<p><?php //$text_fcs; ?></p>
			</div>
		</div>
            </div>
	</div>
</footer>
</div>
<div id="ToTopHover" ></div>
<script>AOS.init({
	once: true
});</script>
<?php 
/* extension bganycombi - Buy Any Get Any Product Combination Pack */
echo $bganycombi_module; 
?>
</body></html>