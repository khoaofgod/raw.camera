<?php 
	get_header();
?>

<?php do_action( 'et_page_heading' ); ?>

<div class="container">
	<div class="page-content page-404">
		<div class="row">
			<div class="col-md-12">
				<h1 class="largest">404</h1>
				<h1><?php esc_html_e('Oops! Page not found', 'royal') ?></h1>
				<hr class="horizontal-break">
				<p><?php esc_html_e('Sorry, but the page you are looking for is not found. Please, make sure you have typed the current URL.', 'royal') ?> </p>
				<?php get_search_form( true ); ?>
				<a href="<?php echo home_url(); ?>" class="button medium"><?php esc_html_e('Go to home page', 'royal'); ?></a>
			</div>
		</div>


	</div>
</div>

	
<?php
	get_footer();
?>