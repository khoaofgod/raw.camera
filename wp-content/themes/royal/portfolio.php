<?php
/**
 * Template Name: Portfolio
 *
 */
 ?>
 
<?php 

	$l = et_page_config();

?>

<?php 
	get_header();
?>

<?php do_action( 'et_page_heading' ); ?>

<div class="container">
	<div class="page-content sidebar-position-without">
		<div class="row">
			<div class="content col-md-12">
					<?php if( have_posts() ) { the_post();  ?>

					<?php if ( etheme_get_option('portfolio_content_side') == '' || etheme_get_option('portfolio_content_side') == 'top' && the_content() != '') echo do_shortcode( get_the_content() );

						get_etheme_portfolio();

						if ( etheme_get_option('portfolio_content_side') == 'bottom'  && the_content() != '') echo do_shortcode( get_the_content() );

					}

					else {
						get_etheme_portfolio();
					}

					?>
			</div>
		</div>

	</div>
</div>
	
<?php
	get_footer();
?>