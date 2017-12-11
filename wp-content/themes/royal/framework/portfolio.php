<?php

/**
*
* Portfolio
*
*/

if ( ! class_exists( 'ETheme_Post_Types' ) ) :

	class ETheme_Post_Types {

		public function init() {
			/**
			 *
			 * ETheme init oll functions.
			 *
			 */
			add_action( 'init', array( $this, 'portfolio_init' ) );
			add_filter( 'post_type_link', array( $this, 'portfolio_post_type_link' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'custom_type_settings' ) );
			add_action( 'load-options-permalink.php', array( $this,'seatings_for_permalink') );

		}


		public function portfolio_init() {
			/**
			 *
			 * Register portfolio post type.
			 *
			 */


			$slug = get_option( 'portfolio_base' ) ? get_option( 'portfolio_base' ) : 'project';
			$slug .= get_option( 'portfolio_custom_base' );

			$labels = array(
				'name' => _x('Projects', 'post type general name', 'royal'),
				'singular_name' => _x('Project', 'post type singular name', 'royal'),
				'add_new' => _x('Add New', 'project', 'royal'),
				'add_new_item' => esc_html__('Add New Project', 'royal'),
				'edit_item' => esc_html__('Edit Project', 'royal'),
				'new_item' => esc_html__('New Project', 'royal'),
				'view_item' => esc_html__('View Project', 'royal'),
				'search_items' => esc_html__('Search Projects', 'royal'),
				'not_found' =>  esc_html__('No projects found', 'royal'),
				'not_found_in_trash' => esc_html__('No projects found in Trash', 'royal'),
				'parent_item_colon' => '',
				'menu_name' => 'Portfolio'
			
			);
			
			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => false,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => array('title','editor','author','thumbnail','excerpt','comments'),
				'rewrite' => array('slug' => $slug)
			);
			
			register_post_type('etheme_portfolio',$args);
			
			$labels = array(
				'name' => _x( 'Tags', 'taxonomy general name', 'royal' ),
				'singular_name' => _x( 'Tag', 'taxonomy singular name', 'royal' ),
				'search_items' =>  esc_html__( 'Search Types', 'royal' ),
				'all_items' => esc_html__( 'All Tags', 'royal' ),
				'parent_item' => esc_html__( 'Parent Tag', 'royal' ),
				'parent_item_colon' => esc_html__( 'Parent Tag:', 'royal' ),
				'edit_item' => esc_html__( 'Edit Tags', 'royal' ),
				'update_item' => esc_html__( 'Update Tag', 'royal' ),
				'add_new_item' => esc_html__( 'Add New Tag', 'royal' ),
				'new_item_name' => esc_html__( 'New Tag Name', 'royal' ),
			);
			
			// Custom taxonomy for Project Tags
			/*register_taxonomy('tag',array('etheme_portfolio'), array(
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'tag' ),
			));*/
			
			$labels2 = array(
				'name' => _x( 'Portfolio Categories', 'taxonomy general name', 'royal' ),
				'singular_name' => _x( 'Category', 'taxonomy singular name', 'royal' ),
				'search_items' =>  esc_html__( 'Search Types', 'royal' ),
				'all_items' => esc_html__( 'All Categories', 'royal' ),
				'parent_item' => esc_html__( 'Parent Category', 'royal' ),
				'parent_item_colon' => esc_html__( 'Parent Category:', 'royal' ),
				'edit_item' => esc_html__( 'Edit Categories', 'royal' ),
				'update_item' => esc_html__( 'Update Category', 'royal' ),
				'add_new_item' => esc_html__( 'Add New Category', 'royal' ),
				'new_item_name' => esc_html__( 'New Category Name', 'royal' ),
			);
			
			
			register_taxonomy('portfolio_category',array('etheme_portfolio'), array(
				'hierarchical' => true,
				'labels' => $labels2,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => ( get_option( 'portfolio_cat_base' ) ) ? get_option( 'portfolio_cat_base' ) : 'portfolio-category' ),
			));
			
		}


		public function portfolio_post_type_link( $permalink, $post ) {
			/**
			 *
			 * Add support for portfolio link custom structure.
			 *
			 */

			if ( $post->post_type != 'etheme_portfolio' ) {
				return $permalink;
			}


			if ( false === strpos( $permalink, '%' ) ) {
				return $permalink;
			}

			// Get the custom taxonomy terms of this post.
			$terms = get_the_terms( $post->ID, 'portfolio_category' );

			if ( ! empty( $terms ) ) {
				$terms = wp_list_sort( $terms, 'ID' ); // order by ID

				$category_object = apply_filters( 'portfolio_post_type_link_portfolio_cat', $terms[0], $terms, $post );
				$category_object = get_term( $category_object, 'portfolio_category' );
				$portfolio_category     = $category_object->slug;

				if ( $category_object->parent ) {
					$ancestors = get_ancestors( $category_object->term_id, 'portfolio_category' );
					foreach ( $ancestors as $ancestor ) {
						$ancestor_object = get_term( $ancestor, 'portfolio_category' );
						$portfolio_category     = $ancestor_object->slug . '/' . $portfolio_category;
					}
				}
			} else {
				$portfolio_category = esc_html__( 'uncategorized', 'slug', 'etheme' );
			}

			if ( strpos( $permalink, '%author%' ) != false ) {
				$authordata = get_userdata( $post->post_author );
				$author = $authordata->user_nicename;
			} else {
				$author = '';
			}

			$find = array(
				'%year%',
				'%monthnum%',
				'%day%',
				'%hour%',
				'%minute%',
				'%second%',
				'%post_id%',
				'%author%',
				'%category%',
				'%portfolio_category%'
			);

			$replace = array(
				date_i18n( 'Y', strtotime( $post->post_date ) ),
				date_i18n( 'm', strtotime( $post->post_date ) ),
				date_i18n( 'd', strtotime( $post->post_date ) ),
				date_i18n( 'H', strtotime( $post->post_date ) ),
				date_i18n( 'i', strtotime( $post->post_date ) ),
				date_i18n( 's', strtotime( $post->post_date ) ),
				$post->ID,
				$author,
				$portfolio_category,
				$portfolio_category
			);

			$permalink = str_replace( $find, $replace, $permalink );

			return $permalink;
		}


		public function custom_type_settings() {

			/**
			 *
			 * Add Etheme section block to permalink setting page.
			 *
			 */
			add_settings_section(
				'et_section',
				__( '8theme permalink settings' , 'royal' ),
				array( $this, 'section_callback' ),
				'permalink'
			);

			/**
			 *
			 * Add "Portfolio base" setting field to Etheme section block.
			 *
			 */
			add_settings_field(
				'portfolio_base',
				__( 'Portfolio base' , 'royal' ),
				array( $this, 'portfolio_callback' ),
				'permalink',
				'optional'
			);

			/**
			 *
			 * Add "Portfolio category base" setting field to Etheme section block.
			 *
			 */
			add_settings_field(
				'portfolio_cat_base',
				__( 'Portfolio category base' , 'royal' ),
				array( $this, 'portfolio_cat_callback' ),
				'permalink',
				'optional'
			);
		}


		public function section_callback() {
			/**
			 *
			 * Callback function for Etheme section block.
			 *
			 */

			$checked['portfolio_def'] = ( get_option( 'et_permalink' ) == 'portfolio_def' || ! get_option( 'et_permalink' ) ) ? 'checked' : '';
			$checked['portfolio_cat_base'] = ( get_option( 'et_permalink' ) == 'portfolio_cat_base' ) ? 'checked' : '';
			$checked['portfolio_custom_base'] = ( get_option( 'et_permalink' ) == 'portfolio_custom_base' ) ? 'checked' : '';

			echo '
				<p>' . esc_html__( '8theme portfolio permalink settings.' , 'royal' ) . '</p>
				</tbody></tr></th>
				<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><input class="et-inp" type="radio" name="et_permalink" value="portfolio_def" ' . $checked['portfolio_def'] . ' >' . esc_html__( 'Default' , 'royal' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base/sample-project/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input class="et-inp" type="radio" name="et_permalink" value="portfolio_cat_base" ' . $checked['portfolio_cat_base'] . '>' . esc_html__( 'Portfolio category base' , 'royal' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base/portfolio-category/sample-project/</code></td>
							</tr>
							<tr>
								<th scope="row"><label><input id="portfolio-custom-base-select" type="radio" name="et_permalink" value="portfolio_custom_base" ' . $checked['portfolio_custom_base'] . '>' . esc_html__( 'Portfolio custom Base' , 'royal' ) . '</label></th>
								<td><code>' . esc_html( home_url() ) . '/portfolio-base</code><input id="portfolio-custom-base" name="portfolio_custom_base" type="text" value="' . get_option( 'portfolio_custom_base' ) . '" class="regular-text code" /></td>
							</tr>
						</tbody>
				</table>

				<script type="text/javascript">
					jQuery( function() {
						jQuery("input.et-inp").change(function() {
							
							var link = "";

							if ( jQuery( this ).val() == "portfolio_cat_base" ) {
								link = "/%portfolio_category%";
							} else {
								link = "";
							}
							jQuery("#portfolio-custom-base").val( link );
						});

						jQuery("input:checked").change();
						jQuery("#portfolio-custom-base").focus( function(){
							jQuery("#portfolio-custom-base-select").click();
						} );
					} );
				</script>

				'
			;
		}


		public function portfolio_callback() {
			/**
			 *
			 * Callback function for "portfolio base" setting field.
			 *
			 */

			echo '<input 
				name="portfolio_base"  
				type="text" 
				value="' . get_option( 'portfolio_base' ) . '" 
				class="regular-text code"
				placeholder="project"
			 />';
		}


		public function portfolio_cat_callback() {
			/**
			 *
			 * Callback function for "portfolio catogory base" setting field.
			 *
			 */

			echo '<input 
				name="portfolio_cat_base"  
				type="text" 
				value="' . get_option( 'portfolio_cat_base' ) . '" 
				class="regular-text code"
				placeholder="portfolio-category"
			 />';
		}


		public function seatings_for_permalink() {
			/**
			 *
			 * Make it work on permalink page.
			 *
			 */

			if ( ! is_admin() ) {
				return;
			} 

			if( isset( $_POST['portfolio_base'] ) ) {
				update_option( 'portfolio_base', sanitize_title_with_dashes( $_POST['portfolio_base'] ) );
			}

			if( isset( $_POST['portfolio_cat_base'] ) ) {
				update_option( 'portfolio_cat_base', sanitize_title_with_dashes( $_POST['portfolio_cat_base'] ) );
			}

			if( isset( $_POST['et_permalink'] ) ) {
				update_option( 'et_permalink', sanitize_title_with_dashes( $_POST['et_permalink'] ) );
			}

			if( isset( $_POST['portfolio_custom_base'] ) ) {
				update_option( 'portfolio_custom_base', $_POST['portfolio_custom_base'] );
			}
		}

	}

endif;


$et_post_types = new ETheme_Post_Types();
$et_post_types->init();


add_shortcode('portfolio', 'etheme_portfolio_shortcode');

function etheme_portfolio_shortcode($atts) {
	$a = shortcode_atts( array(
       'title' => 'Recent Works',
       'limit' => 12
   ), $atts );
   
   
   return etheme_get_recent_portfolio($a['limit'], $a['title']);
    
}


function etheme_get_recent_portfolio($limit, $title = 'Recent Works', $not_in = 0) {
	$args = array(
		'post_type' => 'etheme_portfolio',
		'order' => 'DESC',
		'orderby' => 'date',
		'posts_per_page' => $limit,
		'post__not_in' => array( $not_in )
	);
	
	return etheme_create_portfolio_slider($args, $title);
}

function etheme_create_portfolio_slider($args,$title = false,$width = 540, $height = 340, $crop = true){
	global $wpdb;
    $box_id = rand(1000,10000);
    $multislides = new WP_Query( $args );
    $sliderHeight = etheme_get_option('default_blog_slider_height');
    $class = '';
    
	ob_start();
        if ( $multislides->have_posts() ) :
            $title_output = '';
            if ($title) {
                $title_output = '<h3 class="title"><span>'.$title.'</span></h3>';
            }   
              echo '<div class="slider-container carousel-area '.$class.'">';
	              echo $title_output;
	              echo '<div class="items-slide slider-'.$box_id.'">';
	                    echo '<div class="slider recentCarousel">';
	                    $_i=0;
	                    while ($multislides->have_posts()) : $multislides->the_post();
	                        $_i++;
	                        get_template_part( 'portfolio', 'slide' );

	                    endwhile; 
	                    echo '</div><!-- slider -->'; 
	              echo '</div><!-- products-slider -->';
              echo '</div><!-- slider-container -->'; 

           
            echo '
                <script type="text/javascript">
                    jQuery(".slider-'.$box_id.' .slider").owlCarousel({
                        items:4, 
                        lazyLoad : true,
                        navigation: true,
                        navigationText:false,
                        rewindNav: false,
                        itemsCustom: [[0, 1], [479,2], [619,2], [768, 2],  [1200, 3], [1600, 3]]
                    });

                </script>
            ';
        endif;
        wp_reset_query();

	$html = ob_get_contents();
	ob_end_clean();
	
	return $html;
}


function etheme_portfolio_pagination($wp_query, $paged, $pages = '', $range = 2) {  
     $showitems = ($range * 2)+1;  

     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo "<nav class='pagination-cubic portfolio-pagination'>";
	         echo '<ul class="page-numbers">';
		         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."' class='prev page-numbers'><i class='fa fa-angle-double-left'></i></a></li>";
		
		         for ($i=1; $i <= $pages; $i++)
		         {
		             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		             {
		                 echo ($paged == $i)? "<li><span class='page-numbers current'>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a></li>";
		             }
		         }
		
		         if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."' class='next page-numbers'><i class='fa fa-angle-double-right'></i></a></li>";
	         echo '</ul>';
         echo "</nav>\n";
     }
}

function print_item_cats($id) {

	//Returns Array of Term Names for "categories"
	$term_list = wp_get_post_terms($id, 'portfolio_category');
	$_i = 0;
	foreach ($term_list as $value) { 
		$_i++;
                echo '<a href="'.get_term_link($value).'">';
		echo $value->name; 
                echo '</a>';
		if($_i != count($term_list)) 
			echo ', ';
	}
}



add_shortcode('portfolio_grid', 'etheme_portfolio_grid_shortcode');

function etheme_portfolio_grid_shortcode() {
	$a = shortcode_atts( array(
       'categories' => '',
       'limit' => -1,
   		'show_pagination' => 1
   ), $atts );
   
   
   return get_etheme_portfolio($a['categories'], $a['limit'], $a['show_pagination']);
    
}




function get_etheme_portfolio($categories = false, $limit = false, $show_pagination = true) {

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$cat = get_query_var('portfolio_category');
		
		$tax_query = array();

		if(!$limit) {
			$limit = etheme_get_option('portfolio_count');
		}

		if(is_array($categories) && !empty($categories)) {
			$tax_query = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field' => 'id',
					'terms' => $categories,
					'operator' => 'IN'
				)
			);
		} else if(!empty($cat)) {
			$tax_query = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field' => 'slug',
					'terms' => $cat
				)
			);
		}

		$args = array(
			'post_type' => 'etheme_portfolio',
			'paged' => $paged,	
			'posts_per_page' => $limit,
			'tax_query' => $tax_query
		);

		$loop = new WP_Query($args);
		
		if ( $loop->have_posts() ) : ?>
			<div>
				<ul class="portfolio-filters">
					<li><a href="#" data-filter="*" class="btn big active"><?php esc_html_e('Show All', 'royal'); ?></a></li>
						<?php 
						$categories = get_terms('portfolio_category', array('include' => $categories));
						$catsCount = count($categories);
						$_i=0;
						foreach($categories as $category) {
							$_i++;
							?>
								<li><a href="#" data-filter=".sort-<?php echo $category->slug; ?>" class="btn big"><?php echo $category->name; ?></a></li>
							<?php 
						}
		   				
						?>
				</ul>
			
				<div class="row portfolio masonry">
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

					<?php
						get_template_part( 'content', 'portfolio' );
					?>

				<?php endwhile; ?>
				</div>
			</div>

		<?php if ($show_pagination): ?>
			<?php etheme_portfolio_pagination($loop, $paged); ?>
		<?php endif ?>
		
		<?php wp_reset_query(); ?>
		
	<?php else: ?>

		<h3><?php esc_html_e('No projects were found!', 'royal') ?></h3>

	<?php endif;
}
