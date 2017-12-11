<?php
get_header();
?>


<?php do_action( 'et_page_heading' ); ?>

	<div class="container">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?>">
			<div class="row">

				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
					<?php if(have_posts()): while(have_posts()) : the_post(); ?>
						<article <?php post_class('blog-post post-single'); ?> id="post-<?php the_ID(); ?>" >
							<?php
                                $content = get_the_content();
							    compareCamera($content);
							?>
						</article>


					<?php endwhile; else: ?>

						<h1><?php esc_html_e('No posts were found!', 'royal') ?></h1>

					<?php endif; ?>



				</div>



			</div>

		</div>
	</div>

<?php
get_footer();
?>