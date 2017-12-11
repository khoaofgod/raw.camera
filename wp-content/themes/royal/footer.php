<?php 
	global $etheme_responsive; 
	$fd = etheme_get_option('footer_demo'); 	
	$fbg = etheme_get_option('footer_bg');
	$copybg = etheme_get_option('copyright_bg');
	$fcolor = etheme_get_option('footer_text_color');
	$prebg = etheme_get_option('prefooter_bg');
	$ft = ''; $ft = apply_filters('custom_footer_filter',$ft);
	$custom_footer = etheme_get_custom_field('custom_footer', et_get_page_id()); 
?>
    
    <?php if($custom_footer != 'without'): ?>
		<?php if((is_active_sidebar('footer1') || $fd) && empty($custom_footer)): ?>
			<div class="footer-top footer-top-<?php echo esc_attr($ft); ?>" <?php if($prebg != ''): ?>style="background-color:<?php echo $prebg; ?>"<?php endif; ?>>
				<div class="container">
	                <?php if ( !is_active_sidebar( 'footer1' ) ) : ?>
	               		<?php if($fd) etheme_footer_demo('footer1'); ?>
	                <?php else: ?>
	                    <?php dynamic_sidebar( 'footer1' ); ?>
	                <?php endif; ?>  
				</div>
			</div>
		<?php endif; ?>
		
	
		<?php if((is_active_sidebar('footer2') || $fd) && empty($custom_footer)): ?>
			<footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
				<div class="container">
	                <?php if ( !is_active_sidebar( 'footer2' ) ) : ?>
	               		<?php if($fd) etheme_footer_demo('footer2'); ?>
	                <?php else: ?>
	                    <?php dynamic_sidebar( 'footer2' ); ?>
	                <?php endif; ?>
	                <?php do_action('etheme_after_footer_widgets'); ?>
				</div>

			</footer>
		<?php endif; ?>
	
		<?php if(!empty($custom_footer)): ?>
            <footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
                <div class="container">
                    <?php echo et_get_block($custom_footer); ?>  
                </div>
            </footer>
        <?php endif; ?>
	
		<?php if((is_active_sidebar('footer9') || is_active_sidebar('footer10') || $fd) && empty($custom_footer)): ?>
		<div class="copyright copyright-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($copybg != ''): ?>style="background-color:<?php echo $copybg; ?>"<?php endif; ?>>
			<div class="container">
				<div class="row-copyrights">
					<div class="pull-left">
						<?php if(is_active_sidebar('footer9')): ?> 
							<?php dynamic_sidebar('footer9'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer9'); ?>
						<?php endif; ?>
					</div>
					<div class="clearfix visible-xs"></div>
					<div class="copyright-payment pull-right">
						<?php if(is_active_sidebar('footer10')): ?> 
							<?php dynamic_sidebar('footer10'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer10'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	    <?php endif; ?>
    <?php endif; ?>
	
	</div> <!-- page wrapper -->
	</div> <!-- st-content-inner -->
	</div>
	</div>
	<?php do_action('after_page_wrapper'); ?>
	</div> <!-- st-container -->
	

    <?php if (etheme_get_option('loader')): ?>
    	<script type="text/javascript">
    		if(jQuery(window).width() > 1200) {
		        jQuery("body").queryLoader2({
		            barColor: "#111",
		            backgroundColor: "#fff",
		            percentage: true,
		            barHeight: 2,
		            completeAnimation: "grow",
		            minimumTime: 500,
		            onLoadComplete: function() {
			            jQuery('body').addClass('page-loaded');
		            }
		        });
    		}
        </script>
	<?php endif; ?>

	<?php if ( etheme_get_option('fixed_ppopup') ): ?>
                    <div class="popup_link fixed-popup button <?php if(!etheme_get_option('promo_link')): ?>hidden<?php endif; ?>" <?php if (etheme_get_option('pp_button_bg')): echo 'style="background-color: ' . etheme_get_option('pp_button_bg') . '"'; endif; ?>><a class="etheme-popup <?php echo (etheme_get_option('promo_auto_open')) ? 'open-click': '' ; ?>" href="#etheme-popup"><?php if ( etheme_get_option('pp_title') == '' ) { esc_html_e('Newsletter', 'royal'); } else { echo etheme_get_option('pp_title'); } ?></a></div>
      <?php  endif; ?>
	
	<?php if (etheme_get_option('to_top')): ?>
		<div id="back-top" class="back-top <?php if(!etheme_get_option('to_top_mobile')): ?>visible-lg<?php endif; ?> bounceOut">
			<a href="#top">
				<span></span>
			</a>
		</div>
	<?php endif ?>


	<?php
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		wp_footer();
	?>
</body>

</html>