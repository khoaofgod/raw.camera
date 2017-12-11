<?php
// **********************************************************************// 
// ! Visual Composer Setup
// **********************************************************************//
add_action( 'init', 'etheme_VC_setup');
if(!function_exists('getCSSAnimation')) {
	function getCSSAnimation($css_animation) {
        $output = '';
        if ( $css_animation != '' ) {
            wp_enqueue_script( 'waypoints' );
            $output = ' wpb_animate_when_almost_visible wpb_'.$css_animation;
        }
        return $output;
	}
}
if(!function_exists('buildStyle')) {
    function buildStyle($bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '') {
        $has_image = false;
        $style = '';
        if((int)$bg_image > 0 && ($image_url = wp_get_attachment_url( $bg_image, 'large' )) !== false) {
            $has_image = true;
            $style .= "background-image: url(".$image_url.");";
        }
        if(!empty($bg_color)) {
            $style .= vc_get_css_color('background-color', $bg_color);
        }
        if(!empty($bg_image_repeat) && $has_image) {
            if($bg_image_repeat === 'cover') {
                $style .= "background-repeat:no-repeat;background-size: cover;";
            } elseif($bg_image_repeat === 'contain') {
                $style .= "background-repeat:no-repeat;background-size: contain;";
            } elseif($bg_image_repeat === 'no-repeat') {
                $style .= 'background-repeat: no-repeat;';
            }
        }
        if( !empty($font_color) ) {
            $style .= vc_get_css_color('color', $font_color); // 'color: '.$font_color.';';
        }
        if( $padding != '' ) {
            $style .= 'padding: '.(preg_match('/(px|em|\%|pt|cm)$/', $padding) ? $padding : $padding.'px').';';
        }
        if( $margin_bottom != '' ) {
            $style .= 'margin-bottom: '.(preg_match('/(px|em|\%|pt|cm)$/', $margin_bottom) ? $margin_bottom : $margin_bottom.'px').';';
        }
        return empty($style) ? $style : ' style="'.$style.'"';
    }
}
if(!function_exists('etheme_VC_setup')) {
	function etheme_VC_setup() {
		if (!class_exists('WPBakeryVisualComposerAbstract')) return;
		global $vc_params_list;
		$vc_params_list[] = 'icon';
		
		vc_remove_element("vc_carousel");
		vc_remove_element("vc_images_carousel");
		vc_remove_element("vc_tour");
		
		
		
		$target_arr = array(esc_html__("Same window", "js_composer") => "_self", esc_html__("New window", "js_composer") => "_blank");
		$add_css_animation = array(
		  "type" => "dropdown",
		  "heading" => esc_html__("CSS Animation", "js_composer"),
		  "param_name" => "css_animation",
		  "admin_label" => true,
		  "value" => array(esc_html__("No", "js_composer") => '', esc_html__("Top to bottom", "js_composer") => "top-to-bottom", esc_html__("Bottom to top", "js_composer") => "bottom-to-top", esc_html__("Left to right", "js_composer") => "left-to-right", esc_html__("Right to left", "js_composer") => "right-to-left", esc_html__("Appear from center", "js_composer") => "appear"),
		  "description" => esc_html__("Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "js_composer")
		);
		

	    // **********************************************************************// 
	    // ! Separator
	    // **********************************************************************//
	    $setting_vc_separator = array (
	    "show_settings_on_create" => true,
	      'params' => array(
	          array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Type", "js_composer"),
	            "param_name" => "type",
	            "value" => array( 
	                "", 
	                esc_html__("Default", 'royal') => "",
	                esc_html__("Double", 'royal') => "double",
	                esc_html__("Dashed", 'royal') => "dashed", 
	                esc_html__("Dotted", 'royal') => "dotted", 
	                esc_html__("Double Dotted", 'royal') => "double dotted", 
	                esc_html__("Double Dashed", 'royal') => "double dashed", 
	                esc_html__("Horizontal break", 'royal') => "horizontal-break", 
	                esc_html__("Space", 'royal') => "space"
	              )
	          ),
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("Height", "js_composer"),
	            "param_name" => "height",
	            "dependency" => Array('element' => "type", 'value' => array('space'))
	          ),
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("Extra class", "js_composer"),
	            "param_name" => "class"
	          )
	        ) 
	    );
	    vc_map_update('vc_separator', $setting_vc_separator);
	
	    function vc_theme_vc_separator($atts, $content = null) {
	      $output = $color = $el_class = $css_animation = '';
	      extract(shortcode_atts(array(
	          'type' => '',
	          'class' => '',
	          'height' => ''
	      ), $atts));
	
	      $output .= do_shortcode('[hr class="'.$type.' '.$class.'" height="'.$height.'"]');
	      return $output;
	    }
	
	
	    // **********************************************************************// 
	    // ! FAQ toggle elements
	    // **********************************************************************//
		$toggle_params = array(
			"name" => esc_html__("FAQ", "js_composer"),
			"icon" => "icon-wpb-toggle-small-expand",
			"category" => esc_html__('Content', 'js_composer'),
			"description" => esc_html__('Toggle element for Q&A block', 'js_composer'),
			"params" => array(
				array(
					"type" => "textfield",
					"holder" => "h4",
					"class" => "toggle_title",
					"heading" => esc_html__("Toggle title", "js_composer"),
					"param_name" => "title",
					"value" => esc_html__("Toggle title", "js_composer"),
					"description" => esc_html__("Toggle block title.", "js_composer")
				),
				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "toggle_content",
					"heading" => esc_html__("Toggle content", "js_composer"),
					"param_name" => "content",
					"value" => esc_html__("<p>Toggle content goes here, click edit button to change this text.</p>", "js_composer"),
				"description" => esc_html__("Toggle block content.", "js_composer")
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Default state", "js_composer"),
					"param_name" => "open",
					"value" => array(esc_html__("Closed", "js_composer") => "false", esc_html__("Open", "js_composer") => "true"),
					"description" => esc_html__('Select "Open" if you want toggle to be open by default.', "js_composer")
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Style", "js_composer"),
					"param_name" => "style",
					"value" => array(esc_html__("Default", "js_composer") => "default", esc_html__("Bordered", "js_composer") => "bordered")
				),
				$add_css_animation,
				array(
					"type" => "textfield",
					"heading" => esc_html__("Extra class name", "js_composer"),
					"param_name" => "el_class",
					"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
				)
			),
			"js_view" => 'VcToggleView'
		);

	    vc_map_update('vc_toggle', $toggle_params);

	    function vc_theme_vc_toggle($atts, $content = null) {
	      $output = $title = $css_class = $el_class = $open = $css_animation = '';
	      extract(shortcode_atts(array(
	          'title' => esc_html__("Click to toggle", "js_composer"),
	          'el_class' => '',
	          'style' => 'default',
	          'open' => 'false',
	          'css_animation' => ''
	      ), $atts));
	
	
	      $open = ( $open == 'true' ) ? 1 : 0;
	
	      $css_class .= getCSSAnimation($css_animation);
	      $css_class .= ' '.$el_class;
	
	      $output .= '<div class="toggle-block '.$css_class.' '.$style.'">'.do_shortcode('[toggle title="'.$title.'" class="'.$css_class.'" active="'.$open.'"]'.wpb_js_remove_wpautop($content).'[/toggle]').'</div>';
	
	
	      return $output;
	    }
	
	    // **********************************************************************// 
	    // ! Sliders
	    // **********************************************************************//
	   $setting_vc_gallery = array(
		  "name" => esc_html__("Image Gallery", "js_composer"),
		  "icon" => "icon-wpb-images-stack",
		  "category" => esc_html__('Content', 'js_composer'),
		  "params" => array(
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Widget title", "js_composer"),
		      "param_name" => "title",
		      "description" => esc_html__("What text use as a widget title. Leave blank if no title is needed.", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Gallery type", "js_composer"),
		      "param_name" => "type",
		      "value" => array(esc_html__("OWL slider", "js_composer") => "owl", esc_html__("Nivo slider", "js_composer") => "nivo", esc_html__("Carousel", "js_composer") => "carousel", esc_html__("Image grid", "js_composer") => "image_grid"),
		      "description" => esc_html__("Select gallery type.", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Auto rotate slides", "js_composer"),
		      "param_name" => "interval",
		      "value" => array(3, 5, 10, 15, esc_html__("Disable", "js_composer") => 0),
		      "description" => esc_html__("Auto rotate slides each X seconds.", "js_composer"),
		      "dependency" => Array('element' => "type", 'value' => array('flexslider_fade', 'flexslider_slide', 'nivo'))
		    ),
		    array(
		      "type" => "attach_images",
		      "heading" => esc_html__("Images", "js_composer"),
		      "param_name" => "images",
		      "value" => "",
		      "description" => esc_html__("Select images from media library.", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Image size", "js_composer"),
		      "param_name" => "img_size",
		      "description" => esc_html__("Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("On click", "js_composer"),
		      "param_name" => "onclick",
		      "value" => array(esc_html__("Open prettyPhoto", "js_composer") => "link_image", esc_html__("Do nothing", "js_composer") => "link_no", esc_html__("Open custom link", "js_composer") => "custom_link"),
		      "description" => esc_html__("What to do when slide is clicked?", "js_composer")
		    ),
		    array(
		      "type" => "exploded_textarea",
		      "heading" => esc_html__("Custom links", "js_composer"),
		      "param_name" => "custom_links",
		      "description" => esc_html__('Enter links for each slide here. Divide links with linebreaks (Enter).', 'js_composer'),
		      "dependency" => Array('element' => "onclick", 'value' => array('custom_link'))
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Custom link target", "js_composer"),
		      "param_name" => "custom_links_target",
		      "description" => esc_html__('Select where to open  custom links.', 'js_composer'),
		      "dependency" => Array('element' => "onclick", 'value' => array('custom_link')),
		      'value' => $target_arr
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Extra class name", "js_composer"),
		      "param_name" => "el_class",
		      "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		    )
		  )
		);
	    
	    vc_map_update('vc_gallery', $setting_vc_gallery);
	    
	    function vc_theme_vc_gallery($atts, $content = null) {
	      $output = $title = $type = $onclick = $custom_links = $img_size = $custom_links_target = $images = $el_class = $interval = '';
	      extract(shortcode_atts(array(
	          'title' => '',
	          'type' => 'owl',
	          'onclick' => 'link_image',
	          'custom_links' => '',
	          'custom_links_target' => '',
	          'img_size' => 'thumbnail',
	          'images' => '',
	          'el_class' => '',
	          'interval' => '5',
	      ), $atts));
	      $gal_images = '';
	      $link_start = '';
	      $link_end = '';
	      $el_start = '';
	      $el_end = '';
	      $slides_wrap_start = '';
	      $slides_wrap_end = '';
	      $rand = rand(1000,9999);
	
	      $el_class = ' '.$el_class.' ';
	
	      if ( $type == 'nivo' ) {
	          $type = ' wpb_slider_nivo theme-default';
	          wp_enqueue_script( 'nivo-slider' );
	          wp_enqueue_style( 'nivo-slider-css' );
	          wp_enqueue_style( 'nivo-slider-theme' );
	
	          $slides_wrap_start = '<div class="nivoSlider">';
	          $slides_wrap_end = '</div>';
	      } else if ( $type == 'flexslider' || $type == 'flexslider_fade' || $type == 'flexslider_slide' || $type == 'fading' ) {
	          $el_start = '<li>';
	          $el_end = '</li>';
	          $slides_wrap_start = '<ul class="slides">';
	          $slides_wrap_end = '</ul>';
	      } else if ( $type == 'image_grid' ) {

			  wp_enqueue_script( 'vc_grid-js-imagesloaded' );
			  wp_enqueue_script( 'isotope' );
			  wp_enqueue_style( 'isotope-css' );
	
	          $el_start = '<li class="gallery-item">';
	          $el_end = '</li>';
	          $slides_wrap_start = '<ul class="wpb_images_grid_ul">';
	          $slides_wrap_end = '</ul>';
	      } else if ( $type == 'carousel' ) {
	
	          $el_start = '<li class="">';
	          $el_end = '</li>';
	          $slides_wrap_start = '<ul class="images-carousel carousel-'.$rand.'">';
	          $slides_wrap_end = '</ul>';
	      }
	
	      $flex_fx = '';
	      $flex = false;
	      $owl = false;
	      if ( $type == 'flexslider' || $type == 'flexslider_fade' || $type == 'fading' ) {
	          $flex = true;
	          $type = ' wpb_flexslider'.$rand.' flexslider_fade flexslider';
	          $flex_fx = ' data-flex_fx="fade"';
	      } else if ( $type == 'flexslider_slide' ) {
	          $flex = true;
	          $type = ' wpb_flexslider'.$rand.' flexslider_slide flexslider';
	          $flex_fx = ' data-flex_fx="slide"';
	      } else if ( $type == 'image_grid' ) {
	          $type = ' wpb_image_grid';
	      } else if ( $type == 'owl' ) {
	          $type = ' owl_slider'.$rand.' owl_slider';
	          $owl = true;
	      }
	
	
	      /*
	       else if ( $type == 'fading' ) {
	          $type = ' wpb_slider_fading';
	          $el_start = '<li>';
	          $el_end = '</li>';
	          $slides_wrap_start = '<ul class="slides">';
	          $slides_wrap_end = '</ul>';
	          wp_enqueue_script( 'cycle' );
	      }*/
	
	      //if ( $images == '' ) return null;
	      if ( $images == '' ) $images = '-1,-2,-3';
	
	      $pretty_rel_random = 'rel-'.rand();
	
	      if ( $onclick == 'custom_link' ) { $custom_links = explode( ',', $custom_links); }
	      $images = explode( ',', $images);
	      $i = -1;
	
	      foreach ( $images as $attach_id ) {
	          $i++;
	          if ($attach_id > 0) {
	              $post_thumbnail = wpb_getImageBySize(array( 'attach_id' => $attach_id, 'thumb_size' => $img_size ));
	          }
	          else {
	              $different_kitten = 400 + $i;
	              $post_thumbnail = array();
	              $post_thumbnail['thumbnail'] = '<img src="http://placekitten.com/g/'.$different_kitten.'/300" />';
	              $post_thumbnail['p_img_large'][0] = 'http://placekitten.com/g/1024/768';
	          }
	
	          $thumbnail = $post_thumbnail['thumbnail'];
	          $p_img_large = $post_thumbnail['p_img_large'];
	          $link_start = $link_end = '';
	
	          if ( $onclick == 'link_image' ) {
	              $link_start = '<a rel="lightboxGall" href="'.$p_img_large[0].'">';
	              $link_end = '</a>';
	          }
	          else if ( $onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != '' ) {
	              $link_start = '<a href="'.$custom_links[$i].'"' . (!empty($custom_links_target) ? ' target="'.$custom_links_target.'"' : '') . '>';
	              $link_end = '</a>';
	          }
	          $gal_images .= $el_start . $link_start . $thumbnail . $link_end . $el_end;
	      }
	      $css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_gallery wpb_content_element'.$el_class.' clearfix');
	      $output .= "\n\t".'<div class="'.$css_class.'">';
	      $output .= "\n\t\t".'<div class="wpb_wrapper">';
	      $output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_gallery_heading'));
	      $output .= '<div class="wpb_gallery_slides'.$type.'" data-interval="'.$interval.'"'.$flex_fx.'>'.$slides_wrap_start.$gal_images.$slides_wrap_end.'</div>';
	      $output .= "\n\t\t".'</div> ';
	      $output .= "\n\t".'</div> ';
	
	      if ( $owl ) {
	          
      		   $items = '[[0, 1], [479, 1], [619, 1], [768, 1],  [1200, 1], [1600, 1]]';
	           $output .=  '<script type="text/javascript">';
	           //$output .=  '     jQuery(".images-carousel").etFullWidth();';
	           $output .=  '     jQuery(".owl_slider'.$rand.'").owlCarousel({';
	           $output .=  '         items:4, ';
	           $output .=  '         navigation: true,';
	           $output .=  '         navigationText:false,';
	           $output .=  '         rewindNav: false,';
	           $output .=  '         itemsCustom: '.$items.'';
	           $output .=  '    });';
	
	           $output .=  ' </script>';
	      }
	      
	      if( $type == 'carousel' ) {
	      		   $items = '[[0, 1], [479,2], [619,2], [768,4],  [1200, 4], [1600, 4]]';
		           $output .=  '<script type="text/javascript">';
		           //$output .=  '     jQuery(".images-carousel").etFullWidth();';
		           $output .=  '     jQuery(".carousel-'.$rand.'").owlCarousel({';
		           $output .=  '         items:4, ';
		           $output .=  '         navigation: true,';
		           $output .=  '         navigationText:false,';
		           $output .=  '         rewindNav: false,';
		           $output .=  '         itemsCustom: '.$items.'';
		           $output .=  '    });';
		
		           $output .=  ' </script>';
	      }
	
	      return $output;
	    }
	
	
	    // **********************************************************************// 
	    // ! Accordion
	    // **********************************************************************//
	
	    function vc_theme_vc_accordion($atts, $content = null) {
			wp_enqueue_script('jquery-ui-accordion');
	      $output = $title = $interval = $el_class = $collapsible = $active_tab = '';
	      //
	      extract(shortcode_atts(array(
	          'title' => '',
	          'interval' => 0,
	          'el_class' => '',
	          'collapsible' => 'no',
	          'active_tab' => '1'
	      ), $atts));
	
	      $el_class = ' '.$el_class.' ';
	      $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion wpb_content_element '.$el_class.' not-column-inherit');
	
	
	      $output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_accordion_heading'));
	
	      $output .= "\n\t".'<div class=" tabs accordion" data-active="'.$active_tab.'">'; 
	      $output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
	      $output .= "\n\t".'</div> ';
	      return $output;
	    }
	
	    function vc_theme_vc_accordion_tab($atts, $content = null) {
	      global $tab_count;
	      $output = $title = '';
	
	      extract(shortcode_atts(array(
	        'title' => esc_html__("Section", "js_composer")
	      ), $atts));
	
	      $tab_count++;
	
	          $output .= "\n\t\t\t\t" . '<a href="#tab_'.$tab_count.'" id="tab_'.$tab_count.'" class="tab-title">'.$title.'</a>';
	          $output .= "\n\t\t\t\t" . '<div id="content_tab_'.$tab_count.'" class="tab-content"><div class="tab-content-inner">';
	              $output .= ($content=='' || $content==' ') ? esc_html__("Empty section. Edit page to add content here.", "js_composer") : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
	              $output .= "\n\t\t\t\t" . '</div></div>';
	      return $output;
	    }
	
	    // **********************************************************************// 
	    // ! Tabs
	    // **********************************************************************//
	
	    $tab_id_1 = time().'-1-'.rand(0, 100);
	    $tab_id_2 = time().'-2-'.rand(0, 100);
	    $setting_vc_tabs = array(
	      "name"  => esc_html__("Tabs", "js_composer"),
	      "show_settings_on_create" => true,
	      "is_container" => true,
	      "icon" => "icon-wpb-ui-tab-content",
	      "category" => esc_html__('Content', 'js_composer'),
	      "params" => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Widget title", "js_composer"),
	          "param_name" => "title",
	          "description" => esc_html__("What text use as a widget title. Leave blank if no title is needed.", "js_composer")
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Tabs type", "js_composer"),
	          "param_name" => "type",
	          "value" => array(esc_html__("Default", "js_composer") => '', 
	              esc_html__("Products Tabs", "js_composer") => 'products-tabs', 
	              esc_html__("Accordion", "js_composer") => 'accordion', 
	              esc_html__("Left bar", "js_composer") => 'left-bar', 
	              esc_html__("Right bar", "js_composer") => 'right-bar')
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra class name", "js_composer"),
	          "param_name" => "el_class",
	          "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
	        )
	      ),
	      "custom_markup" => '
	      <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
	      <ul class="tabs_controls">
	      </ul>
	      %content%
	      </div>'
	      ,
	      'default_content' => '
	      [vc_tab title="'.esc_html__('Tab 1','js_composer').'" tab_id="'.$tab_id_1.'"][/vc_tab]
	      [vc_tab title="'.esc_html__('Tab 2','js_composer').'" tab_id="'.$tab_id_2.'"][/vc_tab]
	      '
	    );
	    vc_map_update('vc_tabs', $setting_vc_tabs);
	
	
	    // **********************************************************************// 
	    // ! Posts Slider
	    // **********************************************************************//
	    $setting_vc_posts_slider = array (
	      'params' => array(
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Widget title", "js_composer"),
	      "param_name" => "title",
	      "description" => esc_html__("What text use as a widget title. Leave blank if no title is needed.", "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Slides count", "js_composer"),
	      "param_name" => "count",
	      "description" => esc_html__('How many slides to show? Enter number or word "All".', "js_composer")
	    ),
	    array(
	      "type" => "posttypes",
	      "heading" => esc_html__("Post types", "js_composer"),
	      "param_name" => "posttypes",
	      "description" => esc_html__("Select post types to populate posts from.", "js_composer")
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => esc_html__("Layout", "js_composer"),
	      "param_name" => "layout",
	      "value" => array( esc_html__("Horizontal", "js_composer") => "horizontal", esc_html__("Vertical", "js_composer") => "vertical"),
	    ),
            array(
              "type" => "textfield",
              "heading" => esc_html__("Number of items on desktop", 'royal'),
              "param_name" => "desktop",
            ),
            array(
              "type" => "textfield",
              "heading" => esc_html__("Number of items on notebook", 'royal'),
              "param_name" => "notebook",
            ),
            array(
              "type" => "textfield",
              "heading" => esc_html__("Number of items on tablet", 'royal'),
              "param_name" => "tablet",
            ),
            array(
              "type" => "textfield",
              "heading" => esc_html__("Number of items on phones", 'royal'),
              "param_name" => "phones",
            ),
	    array(
	      "type" => 'checkbox',
	      "heading" => esc_html__("Output post date?", "js_composer"),
	      "param_name" => "slides_date",
	      "description" => esc_html__("If selected, date will be printed before the teaser text.", "js_composer"),
	      "value" => Array(esc_html__("Yes, please", "js_composer") => true)
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => esc_html__("Description", "js_composer"),
	      "param_name" => "slides_content",
	      "value" => array(esc_html__("No description", "js_composer") => "", esc_html__("Teaser (Excerpt)", "js_composer") => "teaser" ),
	      "description" => esc_html__("Some sliders support description text, what content use for it?", "js_composer"),
	    ),
	    array(
	      "type" => 'checkbox',
	      "heading" => esc_html__("Output post title?", "js_composer"),
	      "param_name" => "slides_title",
	      "description" => esc_html__("If selected, title will be printed before the teaser text.", "js_composer"),
	      "value" => Array(esc_html__("Yes, please", "js_composer") => true),
	      "dependency" => Array('element' => "slides_content", 'value' => array('teaser')),
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => esc_html__("Link", "js_composer"),
	      "param_name" => "link",
	      "value" => array(esc_html__("Link to post", "js_composer") => "link_post", esc_html__("Link to bigger image", "js_composer") => "link_image", esc_html__("Open custom link", "js_composer") => "custom_link", esc_html__("No link", "js_composer") => "link_no"),
	      "description" => esc_html__("Link type.", "js_composer")
	    ),
	    array(
	      "type" => "exploded_textarea",
	      "heading" => esc_html__("Custom links", "js_composer"),
	      "param_name" => "custom_links",
	      "dependency" => Array('element' => "link", 'value' => 'custom_link'),
	      "description" => esc_html__('Enter links for each slide here. Divide links with linebreaks (Enter).', 'js_composer')
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Thumbnail size", "js_composer"),
	      "param_name" => "thumb_size",
	      "description" => esc_html__('Enter thumbnail size. Example: 200x100 (Width x Height).', "js_composer")
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Post/Page IDs", "js_composer"),
	      "param_name" => "posts_in",
	      "description" => esc_html__('Fill this field with page/posts IDs separated by commas (,), to retrieve only them. Use this in conjunction with "Post types" field.', "js_composer")
	    ),
	    array(
	      "type" => "exploded_textarea",
	      "heading" => esc_html__("Categories", "js_composer"),
	      "param_name" => "categories",
	      "description" => esc_html__("If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter).", "js_composer")
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => esc_html__("Order by", "js_composer"),
	      "param_name" => "orderby",
	      "value" => array( "", esc_html__("Date", "js_composer") => "date", esc_html__("ID", "js_composer") => "ID", esc_html__("Author", "js_composer") => "author", esc_html__("Title", "js_composer") => "title", esc_html__("Modified", "js_composer") => "modified", esc_html__("Random", "js_composer") => "rand", esc_html__("Comment count", "js_composer") => "comment_count", esc_html__("Menu order", "js_composer") => "menu_order" ),
	      "description" => sprintf(esc_html__('Select how to sort retrieved posts. More at %s.', 'js_composer'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
	    ),
	    array(
	      "type" => "dropdown",
	      "heading" => esc_html__("Order by", "js_composer"),
	      "param_name" => "order",
	      "value" => array( esc_html__("Descending", "js_composer") => "DESC", esc_html__("Ascending", "js_composer") => "ASC" ),
	      "description" => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'js_composer'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Extra class name", "js_composer"),
	      "param_name" => "el_class",
	      "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
	    )
	  ) 
	    );
	    vc_map_update('vc_posts_slider', $setting_vc_posts_slider);
	
	    function vc_theme_vc_posts_slider($atts, $content = null) {
	      $output = $title = $type = $count = $interval = $slides_content = $link = '';
	      $custom_links = $thumb_size = $posttypes = $posts_in = $categories = '';
	      $orderby = $order = $el_class = $link_image_start = '';
	      extract(shortcode_atts(array(
                'title' => '',
                'type' => 'flexslider_fade',
                'count' => 10,
                'interval' => 3,
                'layout' => 'horizontal',
                'slides_content' => '',
                'slides_title' => '',
                'link' => 'link_post',
                'more_link' => 1,
                'custom_links' => site_url().'/',
                'thumb_size' => '300x200',
                'posttypes' => '',
                'posts_in' => '',
                'slides_date' => false,
                'categories' => '',
                'orderby' => NULL,
                'order' => 'DESC',
                'el_class' => '',
                'desktop' => 3,
                'notebook' => 3,
                'tablet' => 2,
                'phones' => 1
	      ), $atts));
	
	      $gal_images = '';
	      $link_start = '';
	      $link_end = '';
	      $el_start = '';
	      $el_end = '';
	      $slides_wrap_start = '';
	      $slides_wrap_end = '';
	
	      $el_class = ' '.$el_class.' ';
	
	      $query_args = array();
	
	      //exclude current post/page from query
	      if ( $posts_in == '' ) {
	          global $post;
	          $query_args['post__not_in'] = array($post->ID);
	      }
	      else if ( $posts_in != '' ) {
	          $query_args['post__in'] = explode(",", $posts_in);
	      }
	
	      // Post teasers count
	      if ( $count != '' && !is_numeric($count) ) $count = -1;
	      if ( $count != '' && is_numeric($count) ) $query_args['posts_per_page'] = $count;
	
	      // Post types
	      $pt = array();
	      if ( $posttypes != '' ) {
	          $posttypes = explode(",", $posttypes);
	          foreach ( $posttypes as $post_type ) {
	              array_push($pt, $post_type);
	          }
	          $query_args['post_type'] = $pt;
	      }
	
	      // Narrow by categories
	      if ( $categories != '' ) {
	          $categories = explode(",", $categories);
	          $gc = array();
	          foreach ( $categories as $grid_cat ) {
	              array_push($gc, $grid_cat);
	          }
	          $gc = implode(",", $gc);
	          ////http://snipplr.com/view/17434/wordpress-get-category-slug/
	          $query_args['category_name'] = $gc;
	
	          $taxonomies = get_taxonomies('', 'object');
	          $query_args['tax_query'] = array('relation' => 'OR');
	          foreach ( $taxonomies as $t ) {
	              if ( in_array($t->object_type[0], $pt) ) {
	                  $query_args['tax_query'][] = array(
	                      'taxonomy' => $t->name,//$t->name,//'portfolio_category',
	                      'terms' => $categories,
	                      'field' => 'slug',
	                  );
	              }
	          }
	      }
	
	      // Order posts
	      if ( $orderby != NULL ) {
	          $query_args['orderby'] = $orderby;
	      }
	      $query_args['order'] = $order;
	
	      $thumb_size = explode('x', $thumb_size);
	      $width = $thumb_size[0];
	      $height = $thumb_size[1];
	
	      $crop = true;
	
			$customItems = array(
			    'desktop' => $desktop,
			    'notebook' => $notebook,
			    'tablet' => $tablet,
			    'phones' => $phones
			);
                
	      ob_start();
	      etheme_create_posts_slider($query_args, $title, $more_link, $slides_date, $slides_content, $width, $height, $crop, $layout, $customItems, $el_class );
	      $output = ob_get_contents();
	      ob_end_clean();
	
	      return $output;
	    }
	
	
	
	    // **********************************************************************// 
	    // ! Button
	    // **********************************************************************//
	    $setting_vc_button = array (
	      "params" => array(
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("Text on the button", "js_composer"),
	            "holder" => "button",
	            "class" => "wpb_button",
	            "param_name" => "title",
	            "value" => esc_html__("Text on the button", "js_composer"),
	            "description" => esc_html__("Text on the button.", "js_composer")
	          ),
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("URL (Link)", "js_composer"),
	            "param_name" => "href",
	            "description" => esc_html__("Button link.", "js_composer")
	          ),
	          array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Target", "js_composer"),
	            "param_name" => "target",
	            "value" => $target_arr,
	            "dependency" => Array('element' => "href", 'not_empty' => true)
	          ),
	          array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Type", "js_composer"),
	            "param_name" => "type",
	            "value" => array('bordered', 'filled'),
	            "description" => esc_html__("Button type.", "js_composer")
	          ),
				array(
					'type' => 'icon',
					"heading" => esc_html__("Icon", 'royal'),
					"param_name" => "icon"
				),
	          array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Size", "js_composer"),
	            "param_name" => "size",
	            "value" => array('small','medium','big'),
	            "description" => esc_html__("Button size.", "js_composer")
	          ),
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("Extra class name", "js_composer"),
	            "param_name" => "el_class",
	            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
	          )
	        )
	    );
	    vc_map_update('vc_button', $setting_vc_button);
	
	    function vc_theme_vc_button($atts, $content = null) {
	    	return etheme_btn_shortcode($atts, $content);
	    }
	
	
	    // **********************************************************************// 
	    // ! Call To Action
	    // **********************************************************************//
	    $setting_cta_button = array (
	      "params" => array(
	          array(
	            "type" => "textarea_html",
	            "heading" => esc_html__("Text", "js_composer"),
	            "param_name" => "content",
	            "value" => esc_html__("Click edit button to change this text.", "js_composer"),
	            "description" => esc_html__("Enter your content.", "js_composer")
	          ),
	          array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Block Style", "js_composer"),
	            "param_name" => "style",
	            "value" => array(
	              "" => "",
	              esc_html__("Default", "js_composer") => "default", 
	              esc_html__("Full width", "js_composer") => "fullwidth", 
	              esc_html__("Filled", "js_composer") => "filled", 
	              esc_html__("Without Border", "js_composer") => "without-border", 
	              esc_html__("Dark", "js_composer") => "dark"
	            )
	          ),
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("Text on the button", "js_composer"),
	            "param_name" => "title",
	            "description" => esc_html__("Text on the button.", "js_composer")
	          ),
	          array(
	            "type" => "textfield",
	            "heading" => esc_html__("URL (Link)", "js_composer"),
	            "param_name" => "href",
	            "description" => esc_html__("Button link.", "js_composer")
	          ),
	          array(
	            "type" => "dropdown",
	            "heading" => esc_html__("Button position", "js_composer"),
	            "param_name" => "position",
	            "value" => array(esc_html__("Align right", "js_composer") => "right", esc_html__("Align left", "js_composer") => "left"),
	            "description" => esc_html__("Select button alignment.", "js_composer")
	          )
	        )
	    );
	    vc_map_update('vc_cta_button', $setting_cta_button);
	
	    function vc_theme_vc_cta_button($atts, $content = null) {
	      $output = $call_title = $href = $title = $call_text = $el_class = '';
	      extract(shortcode_atts(array(
	          'href' => '',
	          'style' => '',
	          'title' => '',
	          'position' => 'right'
	      ), $atts));
	
	      return do_shortcode('[callto btn_position="'.$position.'" btn="'.$title.'" style="'.$style.'" link="'.$href.'"]'.$content.'[/callto]');
	    }
	
	    // **********************************************************************// 
	    // ! Teaser grid
	    // **********************************************************************//
		$setting_vc_posts_grid = array(
		  "params" => array(
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Widget title", "js_composer"),
		      "param_name" => "title",
		      "description" => esc_html__("What text use as a widget title. Leave blank if no title is needed.", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Columns count", "js_composer"),
		      "param_name" => "grid_columns_count",
		      "value" => array( 4, 3, 2, 1),
		      "admin_label" => true,
		      "description" => esc_html__("Select columns count.", "js_composer")
		    ),
		    array(
		      "type" => "posttypes",
		      "heading" => esc_html__("Post types", "js_composer"),
		      "param_name" => "grid_posttypes",
		      "description" => esc_html__("Select post types to populate posts from.", "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Teasers count", "js_composer"),
		      "param_name" => "grid_teasers_count",
		      "description" => esc_html__('How many teasers to show? Enter number or word "All".', "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Pagination", "js_composer"),
		      "param_name" => "pagination",
		      "value" => array(esc_html__("Show Pagination", "js_composer") => "show", esc_html__("Hide", "js_composer") => "hide")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Content", "js_composer"),
		      "param_name" => "grid_content",
		      "value" => array(esc_html__("Teaser (Excerpt)", "js_composer") => "teaser", esc_html__("Full Content", "js_composer") => "content"),
		      "description" => esc_html__("Teaser layout template.", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("'Posted by' block", "js_composer"),
		      "param_name" => "posted_block",
		      "value" => array(esc_html__("Show", "js_composer") => "show", esc_html__("Hide", "js_composer") => "hide")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Hover mask", "js_composer"),
		      "param_name" => "hover_mask",
		      "value" => array(esc_html__("Show", "js_composer") => "show", esc_html__("Hide", "js_composer") => "hide")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Layout", "js_composer"),
		      "param_name" => "grid_layout",
		      "value" => array(esc_html__("Title + Thumbnail + Text", "js_composer") => "title_thumbnail_text", esc_html__("Thumbnail + Title + Text", "js_composer") => "thumbnail_title_text", esc_html__("Thumbnail + Text", "js_composer") => "thumbnail_text", esc_html__("Thumbnail + Title", "js_composer") => "thumbnail_title", esc_html__("Thumbnail only", "js_composer") => "thumbnail", esc_html__("Title + Text", "js_composer") => "title_text"),
		      "description" => esc_html__("Teaser layout.", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Teaser grid layout", "js_composer"),
		      "param_name" => "grid_template",
		      "value" => array(esc_html__("Grid", "js_composer") => "grid", esc_html__("Grid with filter", "js_composer") => "filtered_grid"),
		      "description" => esc_html__("Teaser layout template.", "js_composer")
		    ),
		    array(
		      "type" => "taxonomies",
		      "heading" => esc_html__("Taxonomies", "js_composer"),
		      "param_name" => "grid_taxomonies",
		      "dependency" => Array('element' => 'grid_template' /*, 'not_empty' => true*/, 'value' => array('filtered_grid'), 'callback' => 'wpb_grid_post_types_for_taxonomies_handler'),
		      "description" => esc_html__("Select taxonomies from.", "js_composer") //TODO: Change description
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Thumbnail size", "js_composer"),
		      "param_name" => "grid_thumb_size",
		      "description" => esc_html__('Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height).', "js_composer")
		    ),  
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Post/Page IDs", "js_composer"),
		      "param_name" => "posts_in",
		      "description" => esc_html__('Fill this field with page/posts IDs separated by commas (,) to retrieve only them. Use this in conjunction with "Post types" field.', "js_composer")
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Exclude Post/Page IDs", "js_composer"),
		      "param_name" => "posts_not_in",
		      "description" => esc_html__('Fill this field with page/posts IDs separated by commas (,) to exclude them from query.', "js_composer")
		    ),
		    array(
		      "type" => "exploded_textarea",
		      "heading" => esc_html__("Categories", "js_composer"),
		      "param_name" => "grid_categories",
		      "description" => esc_html__("If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter).", "js_composer")
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Order by", "js_composer"),
		      "param_name" => "orderby",
		      "value" => array( "", esc_html__("Date", "js_composer") => "date", esc_html__("ID", "js_composer") => "ID", esc_html__("Author", "js_composer") => "author", esc_html__("Title", "js_composer") => "title", esc_html__("Modified", "js_composer") => "modified", esc_html__("Random", "js_composer") => "rand", esc_html__("Comment count", "js_composer") => "comment_count", esc_html__("Menu order", "js_composer") => "menu_order" ),
		      "description" => sprintf(esc_html__('Select how to sort retrieved posts. More at %s.', 'js_composer'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Order way", "js_composer"),
		      "param_name" => "order",
		      "value" => array( esc_html__("Descending", "js_composer") => "DESC", esc_html__("Ascending", "js_composer") => "ASC" ),
		      "description" => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'js_composer'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
		    ),
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Extra class name", "js_composer"),
		      "param_name" => "el_class",
		      "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		    )
		  )
		);
	
	    vc_map_update('vc_posts_grid', $setting_vc_posts_grid);
	    
	    function vc_theme_vc_posts_grid($atts, $content = null) {
	      return etheme_teaser($atts, $content = null);
	    }
	    
	    // **********************************************************************// 
	    // ! Video player
	    // **********************************************************************//
	    $setting_video = array (
		    "params" => array(
		      array(
		        "type" => "textfield",
		        "heading" => esc_html__("Widget title", "js_composer"),
		        "param_name" => "title",
		        "description" => esc_html__("Enter text which will be used as widget title. Leave blank if no title is needed.", "js_composer")
		      ),
		      array(
		        "type" => "textfield",
		        "heading" => esc_html__("Video link", "js_composer"),
		        "param_name" => "link",
		        "admin_label" => true,
		        "description" => sprintf(esc_html__('Link to the video. More about supported formats at %s.', "js_composer"), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>')
		      ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Open in popup", "js_composer"),
		      "param_name" => "popup",
		      "value" => array( "", esc_html__("Yes", "js_composer") => "yes", esc_html__("No", "js_composer") => "no"),
		      
		    ),

	        array(
	          'type' => 'attach_image',
	          "heading" => esc_html__("Image placeholder", 'royal'),
	          "dependency" => Array('element' => "popup", 'value' => array('yes')),
	          "param_name" => "img"
	        ),
		    
		    array(
		      "type" => "textfield",
		      "heading" => esc_html__("Image size", "js_composer"),
		      "param_name" => "img_size",
	          "dependency" => Array('element' => "popup", 'value' => array('yes')),
		      "description" => esc_html__("Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "js_composer")
		    ),
		      array(
		        "type" => "textfield",
		        "heading" => esc_html__("Extra class name", "js_composer"),
		        "param_name" => "el_class",
		        "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
		      ),
		      array(
		        "type" => "css_editor",
		        "heading" => esc_html__('Css', "js_composer"),
		        "param_name" => "css",
		        // "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer"),
		        "group" => esc_html__('Design options', 'js_composer')
		      )
		    )
	    );
	    vc_map_update('vc_video', $setting_video);
	
	    function vc_theme_vc_video($atts) {
			$output = $title = $link = $size = $el_class = $img_src = '';
			extract(shortcode_atts(array(
				'title' => '',
				'link' => 'http://vimeo.com/23237102',
				'size' => ( isset($content_width) ) ? $content_width : 500,
				'popup' => 'no',
				'img' => '',
				'img_size' => '300x200',
				'el_class' => '',
			  'css' => ''
			
			), $atts));
			
			if ( $link == '' ) { return null; }
					
		    $src_img = '';
		    
		    if($popup == 'yes') {
			    $img_size = explode('x', $img_size); 
			
				if ( ! in_array( $img_size[0], array( "thumbnail", "medium", "large", "full" ))) {

				    $width = $img_size[0];
				    $height = $img_size[1];

				    if($img != '') {
				        $src = etheme_get_image($img, $width, $height);
				        $src_img = $src;  
				    }elseif ($img_src != '') {
				        $src = do_shortcode($img_src);
				        $src_img = $src;  
				    }
			   }
			   else {
			   	$src = wp_get_attachment_image_src( $img, $img_size[0]);
			   	$src_img = $src[0];  
			   }
			    $text = esc_html__('Show video', 'royal');
			    if($src_img != '') {
				    $text = '<img src="'. $src_img .'">';
			    }
		    }

			$video_w = ( isset($content_width) ) ? $content_width : 500;
			$video_h = $video_w/1.61; //1.61 golden ratio
			global $wp_embed;
			$embed = $wp_embed->run_shortcode('[embed width="'.$video_w.'"'.$video_h.']'.$link.'[/embed]');
			
			$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_video_widget wpb_content_element'.$el_class.$el_class.vc_shortcode_custom_css_class($css, ' '), 'vc_video');
			$rand = rand(1000,9999);
			$css_class .= ' video-'.$rand;
			
			
			$output .= "\n\t".'<div class="'.$css_class.'">';
			    $output .= "\n\t\t".'<div class="wpb_wrapper">';
			        $output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_video_heading'));
					if($popup == 'yes') {
						$output .= '<a href="#" class="open-video-popup">'.$text.'</a>';
					    $output .= "\n\t".'<script type="text/javascript">';
					    $output .= "\n\t\t".'jQuery(document).ready(function() {
						    jQuery(".video-'.$rand.' .open-video-popup").magnificPopup({
							    items: [
							      {
							        src: "'.$link.'",
							        type: "iframe" 
							      },
							    ],
						    });
					    });';
				    	$output .= "\n\t".'</script> ';
					} else {
			        	$output .= '<div class="wpb_video_wrapper">' . $embed . '</div>';
					}
		        $output .= "\n\t\t".'</div> ';
		    $output .= "\n\t".'</div> ';
			    
			
			return $output;
	    }
	    // **********************************************************************// 
	    // ! Register New Element: Product categories
	    // **********************************************************************//
	
	    $brands_params = array(
	      'name' => 'Product categories',
	      'base' => 'etheme_product_categories',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of categories", 'royal'),
	          "param_name" => "number"
	        ),
	        /*
		    array(
		      "type" => "dropdown",
		      "heading" => __("Order by", "js_composer"),
		      "param_name" => "orderby",
		      "value" => array( "", __("ID", "js_composer") => "id", __("Count", "js_composer") => "count", __("Name", "js_composer") => "name",  __("Slug", "js_composer") => "slug"),
		      
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => __("Order way", "js_composer"),
		      "param_name" => "order",
		      "value" => array( __("Descending", "js_composer") => "DESC", __("Ascending", "js_composer") => "ASC" ),
		      "description" => sprintf(__('Designates the ascending or descending order. More at %s.', 'js_composer'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
		    ),
	        array(
	          "type" => "dropdown",
	          "heading" => __("Hide empty", ETHEME_DOMAIN),
	          "param_name" => "hide_empty",
	          "value" => array( 
	              __("Enable", ETHEME_DOMAIN) => 1,
	              __("Disable", ETHEME_DOMAIN) => 0
	            )
	        ),*/
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Parent ID", 'royal'),
	          "param_name" => "parent",
              "description" => esc_html__('Get direct children of this term (only terms whose explicit parent is this value). If 0 is passed, only top-level terms are returned. Default is an empty string.', 'royal')
		    ),
		    array(
	          "type" => "textfield",
	          "heading" => esc_html__("Categories IDs", 'royal'),
	          "param_name" => "cat_ids",
              "description" => esc_html__('Write down ids of categories you want to show. ( It will work only if Parent ID area is empty. )', 'royal')
		    ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Display type", 'royal'),
	          "param_name" => "display_type",
	          "value" => array( 
	              esc_html__("Grid", 'royal') => 'grid',
	              esc_html__("Slider", 'royal') => 'slider',
	              esc_html__("Menu", 'royal') => 'menu'
	            )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Show empty categories", 'royal'),
	          "param_name" => "hide_empty",
	          "value" => array( 
	          	esc_html__("Hide", 'royal') => 1,
	              esc_html__("Show", 'royal') => 0
	            )
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class"
	        )
	      )
	
	    );  
	
	    vc_map($brands_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Brands
	    // **********************************************************************//
	
	    $brands_params = array(
	      'name' => 'Brands',
	      'base' => 'brands',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Display type", 'royal'),
	          "param_name" => "display_type",
	          "value" => array( 
	              esc_html__("Slider", 'royal') => 'slider',
	              esc_html__("Grid", 'royal') => 'grid'
	            )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Number of columns", 'royal'),
	          "param_name" => "columns",	          
	          "dependency" => Array('element' => "display_type", 'value' => array('grid')),
	          "value" => array( 
	              '2' => 2,
	              '3' => 3,
	              '4' => 4,
	              '5' => 5,
	              '6' => 6,
	            )
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of brands", 'royal'),
	          "param_name" => "number"
	        ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Order by", "js_composer"),
		      "param_name" => "orderby",
		      "value" => array( "", esc_html__("ID", "js_composer") => "id", esc_html__("Count", "js_composer") => "count", esc_html__("Name", "js_composer") => "name",  esc_html__("Slug", "js_composer") => "slug"),
		      
		    ),
		    array(
		      "type" => "dropdown",
		      "heading" => esc_html__("Order way", "js_composer"),
		      "param_name" => "order",
		      "value" => array( esc_html__("Descending", "js_composer") => "DESC", esc_html__("Ascending", "js_composer") => "ASC" ),
		      "description" => sprintf(esc_html__('Designates the ascending or descending order. More at %s.', 'js_composer'), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>')
		    ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Parent ID", 'royal'),
	          "param_name" => "parent",
              "description" => esc_html__('Get direct children of this term (only terms whose explicit parent is this value). If 0 is passed, only top-level terms are returned. Default is an empty string.', 'royal')
		    ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class"
	        )
	      )
	
	    );  
	
	    vc_map($brands_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Search Form
	    // **********************************************************************//
	
	    $search_params = array(
	      'name' => 'Mega Search Form',
	      'base' => 'etheme_search',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(

	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Search type", "js_composer"),
	          "param_name" => "post_type",
	          "value" => array( 
	          	esc_html__("Products", 'royal') => 'product',
	          	esc_html__("Posts", 'royal') => 'post',
	          	esc_html__("Portfolio", 'royal') => 'etheme_portfolio',
	          	esc_html__("Pages", 'royal') => 'page',
	          	esc_html__("Testimonial", 'royal') => 'testimonial',
	          	esc_html__("All", 'royal') => 'any',
	          	
	          )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Display images", 'royal'),
	          "param_name" => "images",
	          "value" => array( 
	              "", 
	              esc_html__("Yes", 'royal') => 1,
	              esc_html__("No", 'royal') => 0
	            )
	        ),
	        // array(
	        //   "type" => "dropdown",
	        //   "heading" => esc_html__("Search for products", "js_composer"),
	        //   "param_name" => "products",
	        //   "value" => array( 
	        //       "", 
	        //       __("Yes", ETHEME_DOMAIN) => 1,
	        //       __("No", ETHEME_DOMAIN) => 0
	        //     )
	        // ),
	        // array(
	        //   "type" => "dropdown",
	        //   "heading" => __("Search for posts", "js_composer"),
	        //   "param_name" => "posts",
	        //   "value" => array( 
	        //       "", 
	        //       __("Yes", ETHEME_DOMAIN) => 1,
	        //       __("No", ETHEME_DOMAIN) => 0
	        //     )
	        // ),
	        // array(
	        //   "type" => "dropdown",
	        //   "heading" => __("Search in portfolio", "js_composer"),
	        //   "param_name" => "portfolio",
	        //   "value" => array( 
	        //       "", 
	        //       __("Yes", ETHEME_DOMAIN) => 1,
	        //       __("No", ETHEME_DOMAIN) => 0
	        //     )
	        // ),
	        // array(
	        //   "type" => "dropdown",
	        //   "heading" => __("Search for pages", "js_composer"),
	        //   "param_name" => "pages",
	        //   "value" => array( 
	        //       "", 
	        //       __("Yes", ETHEME_DOMAIN) => 1,
	        //       __("No", ETHEME_DOMAIN) => 0
	        //     )
	        // ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of items", 'royal'),
	          "param_name" => "count"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class"
	        )
	      )
	
	    );  
	
	    vc_map($search_params);
	
	
	
	    // **********************************************************************// 
	    // ! Register New Element: Twitter Slider
	    // **********************************************************************//
	
	    $twitter_params = array(
	      'name' => 'Twitter Slider',
	      'base' => 'twitter_slider',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("User account name", 'royal'),
	          "param_name" => "user"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Consumer Key", 'royal'),
	          "param_name" => "consumer_key"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Consumer Secret", 'royal'),
	          "param_name" => "consumer_secret"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("User Token", 'royal'),
	          "param_name" => "user_token"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("User Secret", 'royal'),
	          "param_name" => "user_secret"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Limit", 'royal'),
	          "param_name" => "limit"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class"
	        )
	      )
	
	    );  
	
	    vc_map($twitter_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Testimonials Widget
	    // **********************************************************************//
	
	    $testimonials_params = array(
	      'name' => 'Testimonials widget',
	      'base' => 'testimonials',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Limit", 'royal'),
	          "param_name" => "limit",
	          "description" => esc_html__('How many testimonials to show? Enter number.', 'royal')
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Display type", "js_composer"),
	          "param_name" => "type",
	          "value" => array( 
	              "", 
	              esc_html__("Slider", 'royal') => 'slider',
	              esc_html__("Grid", 'royal') => 'grid'
	            )
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Interval", 'royal'),
	          "param_name" => "interval",
	          "description" => esc_html__('Interval between slides. In milliseconds. Default: 10000', 'royal'),
	          "dependency" => Array('element' => "type", 'value' => array('slider'))
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Show Control Navigation", "js_composer"),
	          "param_name" => "navigation",
	          "dependency" => Array('element' => "type", 'value' => array('slider')),
	          "value" => array( 
	              "", 
	              esc_html__("Hide", 'royal') => false,
	              esc_html__("Show", 'royal') => true
	            )
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Category", 'royal'),
	          "param_name" => "category",
	          "description" => esc_html__('Display testimonials from category.', 'royal')
	        ),
	      )
	
	    );  
	
	    vc_map($testimonials_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Recent Comments Widget
	    // **********************************************************************//
	
	    $recent_comments_params = array(
	      'name' => 'Recent comments widget',
	      'base' => 'et_recent_comments',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Widget title", 'royal'),
	          "param_name" => "title",
	          "description" => esc_html__("What text use as a widget title. Leave blank if no title is needed.", 'royal')
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Limit", 'royal'),
	          "param_name" => "number",
	          "description" => esc_html__('How many testimonials to show? Enter number.', 'royal')
	        )
	      )
	
	    );  
	
	    vc_map($recent_comments_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Recent Posts Widget
	    // **********************************************************************//
	
	    $recent_posts_params = array(
	      'name' => 'Recent posts widget',
	      'base' => 'et_recent_posts_widget',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Widget title", 'royal'),
	          "param_name" => "title",
	          "description" => esc_html__("What text use as a widget title. Leave blank if no title is needed.", 'royal')
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Enable slider", "js_composer"),
	          "param_name" => "slider",
	          "value" => array( 
	              "", 
	              esc_html__("Enable", 'royal') => 1,
	              esc_html__("Disable", 'royal') => 0
	            )
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Limit", 'royal'),
	          "param_name" => "number",
	          "description" => esc_html__('How many testimonials to show? Enter number.', 'royal')
	        )
	      )
	
	    );  
	
	    vc_map($recent_posts_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Team Member
	    // **********************************************************************//
	
	    $team_member_params = array(
	      'name' => 'Team member',
	      'base' => 'team_member',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Member name", 'royal'),
	          "param_name" => "name"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Member email", 'royal'),
	          "param_name" => "email"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Position", 'royal'),
	          "param_name" => "position"
	        ),
	        array(
	          'type' => 'attach_image',
	          "heading" => esc_html__("Avatar", 'royal'),
	          "param_name" => "img"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Image size", "js_composer"),
	          "param_name" => "img_size",
	          "description" => esc_html__("Enter image size. Example in pixels: 200x100 (Width x Height).", "js_composer")
	        ),
	        array(
	          "type" => "textarea_html",
	          "holder" => "div",
	          "heading" => esc_html__("Member information", "js_composer"),
	          "param_name" => "content",
	          "value" => esc_html__("Member description", "js_composer")
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Display Type", "js_composer"),
	          "param_name" => "type",
	          "value" => array( 
	              "", 
	              esc_html__("Vertical", 'royal') => 1,
	              esc_html__("Horizontal", 'royal') => 2
	            )
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Twitter link", 'royal'),
	          "param_name" => "twitter"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Facebook link", 'royal'),
	          "param_name" => "facebook"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Linkedin", 'royal'),
	          "param_name" => "linkedin"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Skype name", 'royal'),
	          "param_name" => "skype"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Instagram", 'royal'),
	          "param_name" => "instagram"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	    vc_map($team_member_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Icon
	    // **********************************************************************//
	
	    $icon_params = array(
	      'name' => 'Awesome Icon',
	      'base' => 'icon',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          'type' => 'icon',
	          "heading" => esc_html__("Icon", 'royal'),
	          "param_name" => "name"
	        ),
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Size", 'royal'),
	          "param_name" => "size",
	          "description" => esc_html__('For example: 64', 'royal')
	        ),
	        array(
	          'type' => 'colorpicker',
	          "heading" => esc_html__("Color", 'royal'),
	          "param_name" => "color"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	
	    //vc_map($icon_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Icon Box
	    // **********************************************************************//
	
	    $icon_box_params = array(
	      'name' => 'Icon Box',
	      'base' => 'icon_box',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          'type' => 'textfield',
	          "heading" => esc_html__("Box title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          'type' => 'icon',
	          "heading" => esc_html__("Icon", 'royal'),
	          "param_name" => "icon"
	        ),
	        array(
	          'type' => 'colorpicker',
	          "heading" => esc_html__("Icon Color", 'royal'),
	          "param_name" => "color"
	        ),
	        array(
	          'type' => 'colorpicker',
	          "heading" => esc_html__("Background Color", 'royal'),
	          "param_name" => "bg_color"
	        ),
	        array(
	          'type' => 'colorpicker',
	          "heading" => esc_html__("Icon Color [HOVER]", 'royal'),
	          "param_name" => "color_hover"
	        ),
	        array(
	          'type' => 'colorpicker',
	          "heading" => esc_html__("Background Color [HOVER]", 'royal'),
	          "param_name" => "bg_color_hover"
	        ),
	        array(
	          "type" => "textarea_html",
	          'admin_label' => true,
	          "heading" => esc_html__("Text", "js_composer"),
	          "param_name" => "content",
	          "value" => esc_html__("Click edit button to change this text.", "js_composer"),
	          "description" => esc_html__("Enter your content.", "js_composer")
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Icon Position", "js_composer"),
	          "param_name" => "icon_position",
	          "value" => array( 
	              "", 
	              esc_html__("Top", 'royal') => 'top',
	              esc_html__("Left", 'royal') => 'left'
	            )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Icon Style", "js_composer"),
	          "param_name" => "icon_style",
	          "value" => array( 
	              esc_html__("Encircled", 'royal') => 'encircled',
	              esc_html__("Small", 'royal') => 'small',
	              esc_html__("Large", 'royal') => 'large'
	            )
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	
	    //vc_map($icon_box_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Banner with mask
	    // **********************************************************************//
	
	    $banner_params = array(
	      'name' => 'Banner',
	      'base' => 'banner',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          'type' => 'attach_image',
	          "heading" => esc_html__("Banner Image", 'royal'),
	          "param_name" => "img"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Banner size", "js_composer"),
	          "param_name" => "img_size",
	          "description" => esc_html__("Enter image size. Example in pixels: 200x100 (Width x Height).", "js_composer")
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Link", "js_composer"),
	          "param_name" => "link"
	        ),
	        array(
	          "type" => "textarea_html",
	          "holder" => "div",
	          "heading" => "Banner Mask Text",
	          "param_name" => "content",
	          "value" => "Some promo text"
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Horizontal align", 'royal'),
	          "param_name" => "align",
	          "value" => array( "", esc_html__("Left", 'royal') => "left", esc_html__("Center", 'royal') => "center", esc_html__("Right", 'royal') => "right")
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Vertical align", 'royal'),
	          "param_name" => "valign",
	          "value" => array( esc_html__("Top", 'royal') => "top", esc_html__("Middle", 'royal') => "middle", esc_html__("Bottom", 'royal') => "bottom")
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Hover effect", 'royal'),
	          "param_name" => "hover",
	          "value" => array( "", esc_html__("zoom", 'royal') => "zoom", esc_html__("fade", 'royal') => "fade")
	        ),
		    array(
		      "type" => 'checkbox',
		      "heading" => esc_html__("Responsive fonts", 'royal'),
		      "param_name" => "responsive_zoom",
		      "value" => Array(esc_html__("Yes, please", "js_composer") => 'yes')
		    ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	
	    vc_map($banner_params);
	
	    // **********************************************************************// 
	    // ! Register New Element:Pricing Table
	    // **********************************************************************//
	    $demoTable = "\n\t".'<ul>';
	    $demoTable .= "\n\t\t".'<li class="row-title">Free</li>';
	    $demoTable .= "\n\t\t".'<li class="row-price"><sup class="currency">$</sup>19<sup>00</sup><sub>per month</sub></li>';
	    $demoTable .= "\n\t\t".'<li>512 mb</li>';
	    $demoTable .= "\n\t\t".'<li>0.6 GHz</li>';
	    $demoTable .= "\n\t\t".'<li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>';
	    $demoTable .= "\n\t\t".'<li><a href="#" class="button">Add to Cart</a></li>';
	    $demoTable .= "\n\t".'</ul>';
	    
	    
	    $ptable_params = array(
	      'name' => 'Pricing Table',
	      'base' => 'ptable',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textarea_html",
	          "holder" => "div",
	          "heading" => "Table",
	          "param_name" => "content",
	          "value" => $demoTable
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Style", 'royal'),
	          "param_name" => "style",
	          "value" => array( "", esc_html__("default", 'royal') => "default", esc_html__("Style 2", 'royal') => "style2")
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	
	    vc_map($ptable_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Single post
	    // **********************************************************************//
	
	    $fpost_params = array(
	      'name' => 'Single blog post',
	      'base' => 'single_post',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Post ID", 'royal'),
	          "param_name" => "id"
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Show more posts link", 'royal'),
	          "param_name" => "more_posts",
	          "value" => array( "", esc_html__("Show", 'royal') => 1, esc_html__("Hide", 'royal') => 0)
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	
	    //vc_map($fpost_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Teaser Box
	    // **********************************************************************//
	
	    $teaser_box_params = array(
	      'name' => 'Teaser Box',
	      'base' => 'teaser_box',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          'type' => 'attach_image',
	          "heading" => esc_html__("Image", 'royal'),
	          "param_name" => "img"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Image size", "js_composer"),
	          "param_name" => "img_size",
	          "description" => esc_html__("Enter image size. Example in pixels: 200x100 (Width x Height).", "js_composer")
	        ),
	        array(
	          "type" => "textarea_html",
	          'admin_label' => true,
	          "heading" => esc_html__("Text", "js_composer"),
	          "param_name" => "content",
	          "value" => esc_html__("Click edit button to change this text.", "js_composer"),
	          "description" => esc_html__("Enter your content.", "js_composer")
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Style", 'royal'),
	          "param_name" => "style",
	          "value" => array( esc_html__("Default", 'royal') => 'default', esc_html__("Bordered", 'royal') => 'bordered')
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Extra Class", 'royal'),
	          "param_name" => "class",
	          "description" => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'royal')
	        )
	      )
	
	    );  
	
	    vc_map($teaser_box_params);
	
	    // **********************************************************************// 
	    // ! Register New Element: Products
	    // **********************************************************************//
	    
	    $static_blocks = array('--choose--' => '');
	    
	    foreach(et_get_static_blocks() as $value) {
		    $static_blocks[$value['label']] = $value['value'];
	    }
	    
	    $fpost_params = array(
	      'name' => 'Products',
	      'base' => 'etheme_products',
	      'icon' => 'icon-wpb-etheme',
	      'category' => 'Eight Theme',
	      'params' => array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("IDs", 'royal'),
	          "param_name" => "ids"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("SKUs", 'royal'),
	          "param_name" => "skus"
	        ),
	       	array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Order by", 'royal'),
	          "param_name" => "order",
	          "value" => array( 
	          	esc_html__("Default", 'royal') => 'default',
	          	esc_html__("Date", 'royal') => 'date',
	          	esc_html__("Comments", 'royal') => 'comment_count',
	          	esc_html__("Title", 'royal') => 'title',
	          	esc_html__("ID", 'royal') => 'ID',
	          	esc_html__('Custom Order') => 'postesc_html__in',
	          	esc_html__("Rand", 'royal') => 'rand',
	          )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Sort order", 'royal'),
	          "param_name" => "orderby",
	          "value" => array( 
	          	esc_html__("Ascending", 'royal') => 'ASC',
	          	esc_html__("Descending", 'royal') => 'DESC',
	          )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Hover effect", 'royal'),
	          "param_name" => "product_img_hover",
	          "value" => array( 
	          	'' => '',
	          	esc_html__("Disable", 'royal') => 'disable',
	          	esc_html__("Swap", 'royal') => 'swap',
	          	esc_html__("Images Slider", 'royal') => 'slider',
	          	esc_html__("Mask with information", 'royal') => 'mask',
	          )
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Display Type", 'royal'),
	          "param_name" => "type",
	          "value" => array( esc_html__("Slider", 'royal') => 'slider',esc_html__("Slider full width (LOOK BOOK)", 'royal') => 'full-width', esc_html__("Grid", 'royal') => 'grid', esc_html__("List", 'royal') => 'list')
	        ),
	        array(
	          "type" => "dropdown",
	          "dependency" => Array('element' => "type", 'value' => array('full-width')),
	          "heading" => esc_html__("Static block for the first slide of the LOOK BOOK", 'royal'),
	          "param_name" => "block_id",
	          "value" => $static_blocks
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Columns", 'royal'),
	          "param_name" => "columns",
	          "dependency" => Array('element' => "type", 'value' => array('grid'))
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Product view", 'royal'),
	          "param_name" => "style",
	          "dependency" => Array('element' => "type", 'value' => array('slider')),
	          "value" => array( esc_html__("Default", 'royal') => 'default', esc_html__("Advanced", 'royal') => 'advanced')
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of items on desktop", 'royal'),
	          "param_name" => "desktop",
	          "dependency" => Array('element' => "type", 'value' => array('slider'))
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of items on notebook", 'royal'),
	          "param_name" => "notebook",
	          "dependency" => Array('element' => "type", 'value' => array('slider'))
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of items on tablet", 'royal'),
	          "param_name" => "tablet",
	          "dependency" => Array('element' => "type", 'value' => array('slider'))
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Number of items on phones", 'royal'),
	          "param_name" => "phones",
	          "dependency" => Array('element' => "type", 'value' => array('slider'))
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Products type", 'royal'),
	          "param_name" => "products",
	          "value" => array( esc_html__("All", 'royal') => '', esc_html__("Featured", 'royal') => 'featured', esc_html__("New", 'royal') => 'new', esc_html__("Sale", 'royal') => 'sale', esc_html__("Recently viewed", 'royal') => 'recently_viewed', esc_html__("Bestsellings", 'royal') => 'bestsellings')
	        ),
	        array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Hide out of stock products", 'royal'),
	          "param_name" => "outofstock",
	          "value" => array( 
	          	'No' => false, 
	          	'Yes' => true
	           ) 
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Limit", 'royal'),
	          "param_name" => "limit"
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Categories or Tags IDs", 'royal'),
	          "param_name" => "categories"
	        )
	      )
	
	    );  
	
	    vc_map($fpost_params);


		// **********************************************************************// 
		// ! Register New Element: Follow
		// **********************************************************************//

	    $follow_params = array(
          'name' => 'Social links',
          'base' => 'follow',
          'icon' => 'icon-wpb-etheme',
          'category' => 'Eight Theme',
          'params' => array(
            array(
              "type" => "textfield",
              "heading" => "Title",
              "param_name" => "title"
            ),
            array(
              "type" => "textfield",
              "heading" => "Facebook",
              "param_name" => "facebook"
            ),
            array(
              "type" => "textfield",
              "heading" => "Twitter",
              "param_name" => "twitter"
            ),
            array(
              "type" => "textfield",
              "heading" => "Instagram",
              "param_name" => "instagram"
            ),
            array(
              "type" => "textfield",
              "heading" => "Google",
              "param_name" => "google"
            ),
            array(
              "type" => "textfield",
              "heading" => "Pinterest",
              "param_name" => "pinterest"
            ),
            array(
              "type" => "textfield",
              "heading" => "Linkedin",
              "param_name" => "linkedin"
            ),
            array(
              "type" => "textfield",
              "heading" => "Tumblr",
              "param_name" => "tumblr"
            ),
            array(
              "type" => "textfield",
              "heading" => "Youtube",
              "param_name" => "youtube"
            ),
             array(
              "type" => "textfield",
              "heading" => "Vimeo",
              "param_name" => "vimeo"
            ),
              array(
              "type" => "textfield",
              "heading" => "RSS",
              "param_name" => "rss"
            ),
            array(
              "type" => "checkbox",
              "heading" => esc_html__("Colorfull icons", 'royal'),
              "param_name" => "colorfull",
            ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Link Target", 'royal'),
              "param_name" => "target",
              "value" => array( esc_html__("Blank", 'royal') => "_blank", esc_html__("Current window", 'royal') => "_self")
            ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Size", 'royal'),
              "param_name" => "size",
              "value" => array( esc_html__("Normal", 'royal') => "normal", esc_html__("Large", 'royal') => "large", esc_html__("Small", 'royal') => "small"),
            ),
            array(
              "type" => "textfield",
              "heading" => esc_html__("Extra class name", 'royal'),
              "param_name" => "class"
            ),

          )
    
        );  
    
        vc_map($follow_params);



        // **********************************************************************// 
		// ! Register New Element: etheme top cart
		// **********************************************************************//

	    $et_top_cart = array(
          'name' => 'Top Cart',
          'base' => 'et_top_cart',
          'icon' => 'icon-wpb-etheme',
          'category' => 'Eight Theme',
          'params' => array(
     		array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Style", 'royal'),
	          "param_name" => "color",
	          "value" => array( esc_html__( 'Dark', 'royal' ) => 'dark', esc_html__( 'light', 'royal' ) => 'light' ),
	        ),
            array(
	          "type" => "dropdown",
	          "heading" => esc_html__("Enable dropdown", 'royal'),
	          "param_name" => "dropdown",
	          "value" => array( esc_html__( 'on', 'royal' ) => 'on', esc_html__( 'off', 'royal' ) => 'off'),
	        ),
	        array(
              "type" => "dropdown",
              "heading" => esc_html__("Position", 'royal'),
              "param_name" => "position",
              "value" => array( esc_html__( "Right", 'royal' ) => "right", esc_html__( "Left", 'royal' ) => "left", esc_html__( "Center", 'royal') => "center" ),
            ),
	        array(
              "type" => "textfield",
              "heading" => esc_html__("Extra class name", 'royal'),
              "param_name" => "class"
            ),
            array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'CSS box', 'js_composer' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design Options', 'js_composer' ),
			),
          )
    
        );  
    
        vc_map($et_top_cart);


        // **********************************************************************// 
		// ! Register New Element: etheme menu
		// **********************************************************************//

	    $et_menu = array(
          'name' => 'Etheme Main Menu',
          'base' => 'et_menu',
          'icon' => 'icon-wpb-etheme',
          'category' => 'Eight Theme',
          'params' => array(
          	 array(
				"type" => "dropdown",
				"heading" => esc_html__("Position", 'royal'),
				"param_name" => "position",
				"value" => array( esc_html__("Left", 'royal') => "left", esc_html__("Right", 'royal') => "right", esc_html__("Center", 'royal') => "center"),
            ),
            array(
				"type" => 'checkbox',
				"heading" => esc_html__("Mobile Trigger", 'royal' ),
				"param_name" => "mobile",
				"value" => Array(esc_html__("Yes, please", 'royal' ) => 'on')
		    ),
            array(
				"type" => "textfield",
				"heading" => esc_html__( "Extra class name", 'royal' ),
				"param_name" => "class"
            ),
          )
        );  
    
        vc_map($et_menu);


        // **********************************************************************// 
		// ! Register New Element: Etheme Search
		// **********************************************************************//

	    $et_top_search = array(
          'name' => 'Etheme Search',
          'base' => 'et_top_search',
          'icon' => 'icon-wpb-etheme',
          'category' => 'Eight Theme',
          'params' => array(
            array(
	          "type" => "dropdown",
	          "heading" => esc_html__("View", 'royal'),
	          "param_name" => "view",
	          "value" => array( esc_html__( 'Popup', 'royal' ) => 'modal', esc_html__( 'Hover', 'royal' ) => 'hover', esc_html__( 'Default', 'royal' ) => 'default'),
	        ),
	        array(
              "type" => "textfield",
              "heading" => esc_html__("Extra class name", 'royal'),
              "param_name" => "class"
            ),
          )
        );  
    
        vc_map($et_top_search);



        // **********************************************************************// 
		// ! Register New Element: etheme links
		// **********************************************************************//

	    $et_links = array(
          'name' => 'Etheme Links',
          'base' => 'et_links',
          'icon' => 'icon-wpb-etheme',
          'category' => 'Eight Theme',
          'params' => array(
          	array(
              "type" => "colorpicker",
              "heading" => esc_html__("Links text color", 'royal'),
              "param_name" => "color",
            ),
            array(
              "type" => "colorpicker",
              "heading" => esc_html__("Links hover text color", 'royal'),
              "param_name" => "hover_color",
            ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Position", 'royal'),
              "param_name" => "position",
              "value" => array( esc_html__("Left", 'royal') => "left", esc_html__("Right", 'royal') => "right", esc_html__("Center", 'royal') => "center"),
            ),
	        array(
              "type" => "checkbox",
              "heading" => esc_html__("Enable Register Link", 'royal'),
              "param_name" => "register",
            ),
            array(
              "type" => "checkbox",
              "heading" => esc_html__("Enable Newsletter Link", 'royal'),
              "param_name" => "newsletter",
            ),
	        array(
              "type" => "textfield",
              "heading" => esc_html__("Extra class name", 'royal'),
              "param_name" => "class"
            ),
          )

        );  
    
        vc_map($et_links);


        // **********************************************************************// 
		// ! Register New Element: Etheme Promo Link
		// **********************************************************************//

	    $et_promo = array(
          'name' => 'Etheme Promo Link',
          'base' => 'et_promo',
          'icon' => 'icon-wpb-etheme',
          'category' => 'Eight Theme',
          'params' => array(
          	array(
              "type" => "colorpicker",
              "heading" => esc_html__("Links text color", 'royal'),
              "param_name" => "color",
            ),
            array(
              "type" => "colorpicker",
              "heading" => esc_html__("Links hover text color", 'royal'),
              "param_name" => "hover_color",
            ),
            array(
              "type" => "dropdown",
              "heading" => esc_html__("Position", 'royal'),
              "param_name" => "position",
              "value" => array( esc_html__("Left", 'royal') => "left", esc_html__("Right", 'royal') => "right", esc_html__("Center", 'royal') => "center"),
            ),
	        array(
              "type" => "textfield",
              "heading" => esc_html__("Extra class name", 'royal'),
              "param_name" => "class"
            ),
          )

        );  
    
        vc_map($et_promo);

        // **********************************************************************// 
		// ! Register New Element: [8THEME] Instagram
		// **********************************************************************//

        $et_instagram = array(
	      'name' => '[8THEME] Instagram',
	      'base' => 'et_instagram',
		  'icon' => ETHEME_BASE_URI . 'images/el-instagram.png',
	      'category' => 'Eight Theme',
	      'params' => array_merge(array(
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Title", 'royal'),
	          "param_name" => "title",
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Username or hashtag", 'royal'),
	          "param_name" => "username",
	        ),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Numer of photos", 'royal'),
	          "param_name" => "number",
	        ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Photo size', 'royal' ),
				'param_name' => 'size',
				'value' => array(
					__( 'Thumbnail', 'royal' ) => 'thumbnail',
					__( 'Medium', 'royal' ) => 'medium',
					__( 'Large', 'royal' ) => 'large',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Columns', 'royal' ),
				'param_name' => 'columns',
				'value' => array(
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Open links in', 'royal' ),
				'param_name' => 'target',
				'value' => array(
					__( 'Current window (_self)', 'royal' ) => '_self',
					__( 'New window (_blank)', 'royal' ) => '_blank',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Photo filters', 'royal' ),
				'param_name' => 'filter_img',
				'value' => array(
					__('None', 'royal') => '',
					__( 'Grey', 'royal' ) => 'grey-scale',
					__( 'Saturate', 'royal' ) => 'saturate',
					__( 'Contrast', 'royal' ) => 'contrast',
				),
			),
	        array(
	          "type" => "textfield",
	          "heading" => esc_html__("Link text", 'royal'),
	          "param_name" => "link",
	        ),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Slider', 'royal' ),
				'param_name' => 'slider',
				'value' => array(
					__( 'Yes', 'royal' ) => 1,
				),
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Without spacing', 'royal' ),
				'param_name' => 'spacing',
				'value' => array(
					__( 'Yes', 'royal' ) => 1,
				),
			),
	      ), etheme_get_slider_params() )
	
	    );  
	
	    vc_map($et_instagram);

        
	}
}
