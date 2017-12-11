<?php
/**
 * Plugin Name: Screets Live Chat
 * Plugin URI: http://apps.screets.org/wordpress-live-chat
 * Description: Chat with your visitors on your website.
 * Version: 2.2.8
 * Author: Screets Team
 * Author URI: http://www.screets.com
 * Requires at least: 4.3
 * Tested up to: 4.7.2
 *
 *
 * Text Domain: schat
 * Domain Path: /languages/
 *
 * @package Live Chat
 * @author Screets
 *
 * COPYRIGHT © 2016 Screets d.o.o. All rights reserved.
 * This  is  commercial  software,  only  users  who have purchased a valid
 * license  and  accept  to the terms of the  License Agreement can install
 * and use this program.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Some useful constants
define( 'SLC_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SLC_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
define( 'SLC_EDITION', 'Unlimited' );
define( 'SLC_PX', $wpdb->prefix . 'slc_' ); // DB Table prefix
define( 'SLC_SLUG', 'screets-lc' ); // Using for updating as slug and in options as prefix

define( 'SLC_FIREBASE_VERSION', '3.2.0' );

require_once( SLC_PATH . '/core/library/titan-framework/titan-framework-embedder.php' );

class SChat
{

	/**
	 * @var Object
	 */
	var $opts;

	/**
	 * @var Object
	 */
	var $__opts;

	/**
	 * @var object 	Plugin meta data
	 */
	var $meta;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Check that function get_plugin_data exists
		if ( !function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Get plugin meta
		$this->meta = get_plugin_data( __FILE__, false );

		// Define app meta constants
		define( 'SLC_NAME', $this->meta['Name'] );
		define( 'SLC_NAME_SHORT', str_replace( 'Screets ', '', SLC_NAME ) );
		define( 'SLC_VERSION', $this->meta['Version'] );
		define( 'SLC_PLUGIN_URL', $this->meta['PluginURI'] );

		// Include required files
		$this->includes();

		// Install the plugin
		register_activation_hook( __FILE__, array( 'SChat_Install', 'install' ) );

		// Get options
		add_action( 'tf_done', array( &$this, 'get_opts' ) );

		// Actions
		add_action( 'init', array( &$this, 'init' ), 10 );
		
		// Loaded action
		do_action( 'schat_loaded' );

	}


	/**
	 * Get options
	 *
	 * @access public
	 * @return void
	 */
	function get_opts() {
		
		// Get options framework
		$this->opts = TitanFramework::getInstance( SLC_SLUG );
		
	}

	/**
	 * Translate fields.
	 *
	 * @access public
	 * @return void
	 */
	function translate() {

		// Register strings to Polylang
		if( function_exists( 'pll_register_string' ) ) {
			foreach( $this->__opts as $id => $opt ) {
				$string = $this->opts->getOption( $id );
				pll_register_string( $opt['name'], $string, SLC_NAME, $opt['multiline'] );
			}
		}

		// Register strings to WPML 3.2+
		echo 'x';
		print_r($this->__opts);
		exit;
		if( has_action( 'wpml_register_single_string' ) ) {
			foreach( $this->__opts as $id => $opt ) {
				$string = $this->opts->getOption( $id );
				do_action( 'wpml_register_single_string', SLC_NAME, $id, $string );
			}
		}
		
	}

	/**
	 * Init the plugin when WordPress initializes
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		// Before init action
		do_action( 'before_schat_init' );

		if( current_user_can( 'schat_answer_visitor' ) )
			define( 'SLC_OP', true ); // User is operator?
		else
			define( 'SLC_VISITOR', true ); // User is visitor?

		// Set up localization
		$this->load_plugin_textdomain();

		// Initialization action
		do_action( 'schat_init' );

	}

	/**
	 * Include required core files
	 *
	 * @access public
	 * @return void
	 */
	function includes() {

		// Include core files
		require SLC_PATH . '/core/install.php';
		require SLC_PATH . '/core/fn.shortcodes.php';
		require SLC_PATH . '/core/fn.firebase.php';
		require SLC_PATH . '/core/fn.post-types.php';
		require SLC_PATH . '/core/fn.common.php';
		require SLC_PATH . '/core/fn.users.php';
		require SLC_PATH . '/core/fn.security.php';
		require SLC_PATH . '/core/fn.options.php';

		// Spyc library
		if ( !class_exists( 'Spyc' ) )
			require SLC_PATH . '/core/library/Spyc.php';

		// Back-end includes
		if( is_admin() ) {
			require SLC_PATH . '/core/class.nav-menu.php';
			require SLC_PATH . '/back-end.php';

		// Front-end includes
		} else {
			require SLC_PATH . '/core/fn.skin.php';
			require SLC_PATH . '/front-end.php';
		}

		// AJAX includes
		if ( defined( 'DOING_AJAX' ) ) {
			$this->ajax_includes();
		}

	}

	/**
	 * Include AJAX files
	 *
	 * @access public
	 * @return void
	 */
	function ajax_includes() {

		// Include back-end files
		require_once SLC_PATH . '/core/library/Pushover.php';
		require SLC_PATH . '/core/fn.ajax.php';

	}


	/**
	 * Get Ajax URL
	 *
	 * @access public
	 * @return string
	 */
	function ajax_url() {

		return admin_url( 'admin-ajax.php', 'relative' );

	}


	/**
	 * Localization
	 *
	 * @access public
	 * @return void
	 */
	function load_plugin_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'schat' );

		load_textdomain( 'schat', WP_LANG_DIR . '/screets-chat/schat-' . $locale . '.mo' );
		load_plugin_textdomain( 'schat', false, SLC_PATH . '/languages/' );

	}

	/**
	 * Get token
	 *
	 * @access public
	 * @return string token
	 */
	public function get_token() {

		// Load required libraries
		if( !class_exists( 'Services_FirebaseTokenGenerator' ) )
			require_once SLC_PATH . '/core/library/firebase/FirebaseToken.php';

		// Create token generator
		$generator = new Services_FirebaseTokenGenerator( trim( $this->opts->getOption('app-secret') ) );

		// Admin user
		$is_admin = ( current_user_can( 'manage_options' ) ) ? true : false;

		// Debugging?
		$debug = $this->opts->getOption('debug' );
		
		// Create token
		$token = $generator->createToken(

			array(
				'uid' => fn_schat_get_user_id(),
				// Is current user an chat operator?
				'is_op' => ( $is_admin || current_user_can( 'schat_answer_visitor' ) ) ? true : false
			),

			array(

				// Admin role disables all security rules for this client
				// This will provide the client with read and write access to your entire Firebase
				'admin' => ( $is_admin ) ? true : false,

				// Debugging
				'debug' => ( !empty( $debug ) ) ? true : false

			)

		);

		return $token;

	}

	/**
	 * Load common scripts
	 *
	 * @access public
	 * @return void
	 */
	function common_scripts() {

		// Twemoji JS
		wp_register_script(
			'twemoji',
			'//twemoji.maxcdn.com/twemoji.min.js',
			null,
			SLC_VERSION,
			true
		);
		wp_enqueue_script( 'twemoji' );

		// Polyfill JS
		wp_register_script(
			'schat-polyfill',
			SLC_URL . '/assets/js/schat.polyfill.js',
			null,
			SLC_VERSION,
			true
		);
		wp_enqueue_script( 'schat-polyfill' );

		// Firebase JS
		wp_register_script(
			'schat-firebase',
			SLC_URL . '/assets/js/schat.firebase.js',
			array( 'firebase', 'firebase-app', 'firebase-auth', 'firebase-db' ),
			SLC_VERSION,
			true
		);
		wp_enqueue_script( 'schat-firebase' );

		// Firebase JS
		wp_register_script(
			'firebase',
			'https://www.gstatic.com/firebasejs/' . SLC_FIREBASE_VERSION . '/firebase.js',
			null, SLC_FIREBASE_VERSION, true
		);
		wp_enqueue_script( 'firebase' );

		// Firebase App JS
		wp_register_script(
			'firebase-app',
			'https://www.gstatic.com/firebasejs/' . SLC_FIREBASE_VERSION . '/firebase-app.js',
			null, SLC_FIREBASE_VERSION, true
		);
		wp_enqueue_script( 'firebase-app' );

		// Firebase Auth JS
		wp_register_script(
			'firebase-auth',
			'https://www.gstatic.com/firebasejs/' . SLC_FIREBASE_VERSION . '/firebase-auth.js',
			null, SLC_FIREBASE_VERSION, true
		);
		wp_enqueue_script( 'firebase-auth' );

		// Firebase DB JS
		wp_register_script(
			'firebase-db',
			'https://www.gstatic.com/firebasejs/' . SLC_FIREBASE_VERSION . '/firebase-database.js',
			null, SLC_FIREBASE_VERSION, true
		);
		wp_enqueue_script( 'firebase-db' );

	}
}

// Init the plugin class
$GLOBALS['SChat'] = new SChat();

/**
 * Get current page URL.
 *
 * @since Live Chat X (3.0.0)
 * @return string URL
 */
function fn_lcx_get_current_url() {

	$page_URL = 'http';
	
	if( !empty( $_SERVER['HTTPS'] ) ) {
		if ( @$_SERVER['HTTPS'] == 'on' )
			$page_URL .= "s";
	}

	$page_URL .= '://';

	if ( @$_SERVER['SERVER_PORT'] != '80' )
		$page_URL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] .$_SERVER['REQUEST_URI'];
	else
		$page_URL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

	return $page_URL;

}

/**
 * Check new plugin updates from Screets server.
 */
if( is_admin() ) {
	global $pagenow;

	if( !empty( $pagenow ) ) {

		switch( $pagenow ) {
			case 'plugin-install.php':
			case 'update-core.php':
			case 'plugins.php':
			case 'admin-ajax.php':
			case 'update.php':

				require SLC_PATH . '/core/library/update-checker/plugin-update-checker.php';

				$checker = PucFactory::buildUpdateChecker(
					'http://support.screets.org/wp-updates/?action=get_metadata&slug=screets-lc',
					__FILE__,
					'screets-lc'
				);
				$checker->addQueryArgFilter( '_fn_lcx_filter_updates' );

			break;

		}

	}

	/**
	 * Filter updates with API key.
	 *
	 * @since Live Chat X (1.0.1)
	 * @return array Query arguments
	 */
	function _fn_lcx_filter_updates( $query_args ) {

		$api_key = get_option( 'screets_key', 'general' );

		// Include API key
		if ( !empty( $api_key ) ) {
			$query_args['key'] = $api_key;
			$query_args['domain'] = fn_lcx_get_current_url();
		}

		return $query_args;
	}
	
}