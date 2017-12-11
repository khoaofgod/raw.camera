<?php  

class ETheme_Version_Check {

    private $current_version = '';
    private $new_version = '';
    private $theme_name = 'royal';
    private $api_url = '';
    private $ignore_key = 'etheme_notice';
    public $information;
    public $api_key;
    public $url = 'https://8theme.com/demo/docs/royal/royal-changelog.txt';
    public $notices;


    function __construct() {
        $theme_data = wp_get_theme('royal');
        $this->current_version = $theme_data->get('Version');
        $this->api_url = ETHEME_API;
        $this->api_key = get_option( 'etheme_api_key' );

        add_action('admin_init', array($this, 'dismiss_notices'));
        add_action('admin_notices', array($this, 'show_notices'), 50 );

        if( ! get_option( 'envato_setup_complete', false ) ) {
            $this->setup_notice();
        }
        if( ! etheme_is_activated() ) {
            add_action( 'admin_menu', array( $this, 'activation_page_menu' ) );
            #$this->activation_notice();
            return;
        }

        if( $this->is_update_available() ) {
            $this->update_notice();
        }

        add_action( 'switch_theme', array( $this, 'update_dismiss' ) );
        
        add_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );
        add_filter( 'pre_set_site_transient_update_themes', array( $this, 'set_update_transient' ) );
        add_filter( 'themes_api', array(&$this, 'api_results'), 10, 3);

    }

    public function activation_page_menu() {
        add_menu_page( 
            esc_html__( '8theme Options', 'etheme' ), 
            esc_html__( '8theme Options', 'etheme' ), 
            'manage_options', 
            'etheme_activation_page', 
            array( $this, 'activation_page' ),
            ETHEME_CODE_CSS_URL.'/images/etheme.png',
            58.925
        );
    }

    public function activation_page() {
        $this->process_form();
        ?>
            <h1><?php esc_html_e( 'Activate Theme', 'etheme' ); ?></h1>
            <?php if ( etheme_is_activated() ): ?>
                <div class="etheme-options-success">
                    <p><strong><?php esc_html_e('Thank you for activation', 'etheme'); ?></strong></p>
                    <p><?php esc_html_e('Now you have lifetime updates, 6 months of free top-notch support, 24/7 live support and much more.', 'etheme' ); ?></p>
                </div>
            <?php else: ?>
                <p><?php esc_html_e('Use your purchase code to activate Royal template. Please, note, that you won’t be able to use it without activation.', 'etheme'); ?></p>
                <p><?php esc_html_e('To find your Purchase code, please, enter your ThemeForest account > Downloads tab > choose etheme > Download > License Certificate & Purchase code', 'etheme'); ?> <a href="http://prntscr.com/d23p2c" target="_blank">http://prntscr.com/d23p2c</a></p>
                <p><?php esc_html_e('Activate Royal template and get lifetime updates, 6 months of free top-notch support, 24/7 live support and much more.', 'etheme'); ?></p>
                <p><?php esc_html_e('A purchase code (license) is only valid for One Project. Do you want to use this theme for a one more project? Purchase a ', 'theme'); ?> <a href="https://themeforest.net/item/xstore-responsive-woocommerce-theme/8611976?license=regular&open_purchase_for_item_id=8611976&purchasable=source&ref=8theme"><?php esc_html_e('new license here', 'etheme'); ?></a><?php esc_html_e(' to get a new purchase code.', 'etheme'); ?></p>
                <form action="" class="etheme-form" method="post">
                    <p>
                        <label for="purchase-code"><?php esc_html_e('Purchase code', 'etheme'); ?></label>
                        <input type="text" name="purchase-code" placeholder="Example: f20b1cdd-ee2a-1c32-a146-66eafea81761" id="purchase-code" />
                    </p>
                    <p>
                            <input class="button-primary" name="etheme-purchase-code" type="submit" value="<?php esc_attr_e( 'Activate theme', 'etheme' ); ?>" />

                    </p>
                </form>

                <p><img src="<?php echo ETHEME_CODE_IMAGES_URL . '/purchase.jpg'; ?>" alt="purchase"></p>
            <?php endif ?>
        <?php 
    }

    public function old_purchase_code() {
        $code = '';

        $option = get_option( 'etheme_purchase_code', false );

        if( $option && ! empty( $option ) ) {
            $code = $option;
        }

        if( isset( $_POST['purchase-code'] ) && ! empty( $_POST['purchase-code'] ) ) $code = $_POST['purchase-code'];

        return $code;
    }

    public function show_notices() {
        global $current_user;
        $user_id = $current_user->ID;
        if( ! empty( $this->notices ) ) {
            foreach ($this->notices as $key => $notice) {
                if ( ! get_user_meta($user_id, $this->ignore_key . $key) ) {
                    echo '<div class="updated etheme-notification">'; 
                    echo $notice['message'];
                    echo "</div>";
                }
            }
        }
    }

    public function dismiss_notices() {
        global $current_user;
        $user_id = $current_user->ID;
        if ( isset( $_GET['et-hide-notice'] ) && isset( $_GET['_et_notice_nonce'] ) ) {
            if ( ! wp_verify_nonce( $_GET['_et_notice_nonce'], 'etheme_hide_notices_nonce' ) ) {
                return;
            }

            add_user_meta($user_id, $this->ignore_key . '_' . $_GET['et-hide-notice'], 'true', true);
        }
    }

    public function setup_notice() {
        $this->notices['_setup'] = array(
            'message' => '
                <p><strong>' . esc_html__('Welcome to Royal', 'royal') . '</strong> – '. esc_html__('You‘re almost ready to start selling :', 'royal') . '</p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=etheme-setup' ) . '" class="button-primary">'. esc_html__('Run the Setup Wizard', 'royal') . '</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'setup' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">' . esc_html__('Skip Setup', 'royal') . '</a></p>
            '
        );
    }

    public function activation_notice() {
        $this->notices['_activation'] = array(
            'message' => '
                <p><strong>' . esc_html__('You need to activate Royal', 'royal') . '</strong></p>
                <p class="submit"><a href="' . admin_url( 'themes.php?page=etheme-setup' ) . '" class="button-primary">' . esc_html__('Activate theme', 'royal') . '</a></p>
            '
        );
    }

    public function update_notice() {
        if( isset( $_GET['_wpnonce'] )) return;
        $this->notices['_update'] = array(
            'message' => '<p>There is a new version of ' . ETHEME_THEME_NAME .  ' Theme available.</p>
                    <p class="submit"><a href="' . admin_url( 'update-core.php?force-check=1&theme_force_check=1' ) . '" class="button-primary">' . esc_html__('Update now', 'royal') .'</a> <a class="button-secondary skip" href="' . esc_url( wp_nonce_url( add_query_arg( 'et-hide-notice', 'update' ), 'etheme_hide_notices_nonce', '_et_notice_nonce' ) ). '">' . esc_html__('Dismiss', 'royal') . '</a></p>
                '
        );
    }

    private function api_get_version() {

        $raw_response = wp_remote_get($this->api_url . '?theme=' . ETHEME_THEME_SLUG);
        if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200)) {
            $response = json_decode($raw_response['body'], true);
            if(!empty($response['version'])) $this->new_version = $response['version'];
        }
    }

    public function update_dismiss() {
        global $current_user;
        #$user_id = $current_user->ID;
        #delete_user_meta($user_id, $this->ignore_key);
    }


    public function update_transient($value, $transient) {
        if(isset($_GET['theme_force_check']) && $_GET['theme_force_check'] == '1') return false;
        return $value;
    }


    public function set_update_transient($transient) {
    
        $this->check_for_update();

        if( isset( $transient ) && ! isset( $transient->response ) ) {
            $transient->response = array();
        }

        if( ! empty( $this->information ) && is_object( $this->information ) ) {
            if( $this->is_update_available() ) {
                $transient->response[ $this->theme_name ] = json_decode( json_encode( $this->information ), true );
            }
        }

        remove_filter( 'site_transient_update_themes', array( $this, 'update_transient' ), 20, 2 );

        return $transient;
    }


    public function api_results($result, $action, $args) {
    
        $this->check_for_update();

        if( isset( $args->slug ) && $args->slug == $this->theme_name && $action == 'theme_information') {
            if( is_object( $this->information ) && ! empty( $this->information ) ) {
                $result = $this->information;
            }
        }

        return $result;
    }


    protected function check_for_update() {
        $force = false;

        if( isset( $_GET['theme_force_check'] ) && $_GET['theme_force_check'] == '1') $force = true;
        
        // Get data
        if( empty( $this->information ) ) {
            $version_information = get_option( 'etheme-update-info', false );
            $version_information = $version_information ? $version_information : new stdClass;
            if ( ! empty ( $version_information->version ) ) {
                $check = $version_information->version;
            }
           $this->information = ( is_object( $version_information ) && ( isset ( $check ) ) ) ?   $version_information : maybe_unserialize( $version_information );
            
        }
        
        $last_check = get_option( 'etheme-update-time' );
        if( $last_check == false ){ 
            update_option( 'etheme-update-time', time() );
        }
        
        if( time() - $last_check > 172800 || $force || $last_check == false ){
            
            $version_information = $this->api_info();

            if( isset( $version_information ) ) {
                update_option( 'etheme-update-time', time() );
                
                $this->information          = $version_information;
                $this->information->checked = time();
                $this->information->url     = $this->url;
                $this->information->package = $this->download_url();

            }

        }
        
        // Save results
        update_option( 'etheme-update-info', $this->information );
    }

    public function api_info() {
        $version_information = new stdClass;

        $response = wp_remote_get( $this->api_url . 'info/' . $this->theme_name );
        $response_code = wp_remote_retrieve_response_code( $response );

        if( $response_code != '200' ) {
            return array();
        }

        $response = json_decode( wp_remote_retrieve_body( $response ) );
        if( ! @$response->new_version ) {
            return $version_information;
        } 

        $version_information = $response;

        return $version_information;
    }

    public function is_update_available() {
        return version_compare( $this->current_version, $this->release_version(), '<' );
    }

    public function download_url() {
        return ETHEME_API . 'files/get/' . $this->theme_name . '.zip?token=' . $this->api_key;
    }
    public function release_version() {
        $this->check_for_update();
        return $this->information->new_version;
    }


    public function activate( $purchase, $api_key ) {
        update_option( 'etheme_api_key', $api_key );
        update_option( 'etheme_is_activated', true );
        update_option( 'etheme_activated_theme', ETHEME_DOMAIN );
        update_option( 'etheme_purchase_code', $purchase );
    }

    public function process_form() {
        if( isset( $_POST['etheme-purchase-code'] ) && ! empty( $_POST['etheme-purchase-code'] ) ) {
            $code = trim( $_POST['purchase-code'] );

            if( empty( $code ) ) {
               echo  '<p class="error">' . esc_html__('Enter the purchase code', 'royal') . '</p>';
                return;
            }

            $theme_id = 8611976;
            $response = wp_remote_get( $this->api_url . 'activate/' . $code . '?envato_id='. $theme_id .'&domain=' .$this->domain() );
            $response_code = wp_remote_retrieve_response_code( $response );

            if( $response_code != '200' ) {
                echo  '<p class="error">' . esc_html__('API request call error.', 'royal') . "<br/>" . esc_html__('1) Contact your server provider and make sure that OpenSSL system library is 1.0 or higher', 'royal') . "<br/>" . esc_html__('2) Make sure that your purshase code is associated with buyer. If you bought theme using guest account create account with the same email that was used during purchase process.', 'royal') . '</p>';
                return;
            }

            $data = json_decode( wp_remote_retrieve_body($response), true );

            if( isset( $data['error'] ) ) {
               echo  '<p class="error">' . $data['error'] . '</p>';
                return;
            } 

            if( ! $data['verified'] ) {
               echo  '<p class="error">' . esc_html__('Code is not verified!', 'royal') . '</p>';
                return;
            } 

            $this->activate( $code, $data['token'] );

            $redirect_url = ( class_exists( 'Redux' ) ) ? admin_url( 'admin.php?page=ot-theme-options' ) : admin_url( 'themes.php?page=install-required-plugins' ) ;

             echo  '<p class="updated">' . esc_html__('Theme is activated! You will be redirected in a few seconds', 'royal') . '
                <script type="text/javascript"> setTimeout( function() { window.location.href = "' . $redirect_url . '"; }, 3000 ); </script>
             </p>';

        }
    }
    
    public function domain() {
        $domain = get_option('siteurl'); //or home
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('www', '', $domain); //add the . after the www if you don't want it
        return urlencode($domain);
    }
}

if(!function_exists('etheme_check_theme_update')) {
    add_action('init', 'etheme_check_theme_update');
    function etheme_check_theme_update() {
        new ETheme_Version_Check();
    }
}