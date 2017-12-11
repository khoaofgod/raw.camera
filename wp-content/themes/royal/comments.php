<?php 
	// Prevent the direct loading
	
	if(!empty($_SERVER['SCRIPT-FILENAME']) && basename($_SERVER['SCRIPT-FILENAME']) == 'comments.php') {
		die(esc_html__('You cannot access this file', 'royal'));
	}

	// Check if post is pwd protected

	if(post_password_required()){
		?>
			<p><?php esc_html_e('This post is password protected. Enter the password to view the comments.', 'royal'); ?></p>
		<?php
		return;
	}
	
	if(have_comments()) :?>
		<div id="comments" class="comments">
		<h4 class="title-alt"><span><?php comments_number( esc_html__('No Comments', 'royal'), esc_html__('One Comment', 'royal'), esc_html__('% Comments', 'royal')); ?></span></h4>

		<ul class="comments-list">
			<?php wp_list_comments('callback=etheme_comments'); ?>
		</ul>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
			
			<div class="comments-nav">
				<div class="pull-left"><?php previous_comments_link(__('&larr; Older Comments', 'royal')); ?></div>
				<div class="pull-right"><?php next_comments_link(__('Newer Comments &rarr;', 'royal')); ?></div>
				<div class="clear"></div>
			</div>

		<?php endif ?>	
		
		</div>

	<?php elseif(!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
		
		<p><?php esc_html_e('Comments are closed', 'royal') ?></p>

	<?php 
	endif;

	// Display Comment Form
	comment_form(array('title_reply' => '<span>' . esc_html__('Leave a reply', 'royal') . '</span>'));
?>