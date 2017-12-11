<?php
/**
 * Template Name: Custom Registration Page
 */
extract(etheme_get_page_sidebar());
//Check whether the user is already logged in
if (!$user_ID) {
        extract(etheme_get_page_sidebar());
        get_header();
        
        ?>


            <div class="page-heading bc-type-<?php echo esc_attr(etheme_option('breadcrumb_type')); ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 a-center">
                            <h1 class="title"><span><?php the_title(); ?></span></h1>
                            <?php etheme_breadcrumbs(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container et-registration">
                <div class="page-content sidebar-position-<?php echo esc_attr($position); ?> responsive-sidebar-<?php echo esc_attr($responsive); ?>">
                    <div class="row">
                        <?php if($position == 'left' || ($responsive == 'top' && $position == 'right')): ?>
                                <?php etheme_get_sidebar($sidebarname); ?>
                        <?php endif; ?>

                        <div class="content <?php echo $content_span; ?>">
                               <?php
                                if(get_option('users_can_register') || get_site_option( 'registration', 'none' ) == 'user') {
                                    ?>
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <div class="content-box">
                                                <h3 class="title"><span><?php esc_html_e('Create Account', 'royal'); ?></span></h3>
                                                <div id="result"></div> 

                                                <?php et_register_form(); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <?php 
                                                if (have_posts()) :
                                                   while (have_posts()) :
                                                      the_post();
                                                      the_content();
                                                   endwhile;
                                                endif;
                                             ?>
                                        </div>

                                    </div>

                                    <?php
                                }
                                else { ?>
                                    <span class="error"><?php esc_html_e('Registration is currently disabled. Please try again later', 'royal' ); ?> </span>
                                    <?php }
                                ?>
                        </div>

                        <?php if($position == 'right' || ($responsive == 'bottom' && $position == 'left')): ?>
                            <?php etheme_get_sidebar($sidebarname); ?>                            
                        <?php endif; ?>
                    </div>

                </div>
            </div>


        <?php
        get_footer();
}
else {
    echo "<script type='text/javascript'>window.location='". home_url() ."'</script>";
}
?>