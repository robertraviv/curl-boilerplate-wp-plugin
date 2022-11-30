<?php
/**
 * Plugin Name: cURL Boilerplate
 * Description: Boilerplate plugin for interacting with external API using cURL.
 * Version: 1.0.3
 * Author: Robert R
 * Text Domain: curl-boilerplate
 * 
**/

Namespace CurlBoilerplate;

defined('ABSPATH') or die;

class CurlBoilerplate {
    
    function __construct() {
        add_action( 'admin_menu', array($this,'plugin_setting_page'));
        require_once plugin_dir_path( __FILE__ ) . 'shortcode.php';
    }

    function plugin_setting_page() {
        $menuPageHook = add_menu_page('Plugin Boilerplate Settings',
        'Plugin Boilerplate',
        'manage_options',
        'api-settings-page',
        array($this,'admin_page_html'),
        'dashicons-smiley',
        100
    );    
        // Load assets (css, js...) only on settings menu page!
        add_action("load-{$menuPageHook}",array($this,'load_assets'));
    }

    function load_assets() {
        wp_enqueue_style(
            'adminCss',
            plugin_dir_url( __FILE__ ) . 'css/admin-styles.css'
        );
    }

    function handle_form() {
        // Adding nonce check for admin settings page
        if(wp_verify_nonce( $_POST['secureNonce'],'saveAPIToken') AND current_user_can('manage_options')) {
            update_option('api_token_store', sanitize_text_field($_POST['api_token_id']));?>
                <div class="updated">
                    <p>API Saved!</p>
                </div>
            <?php } else { ?>
                <div class="error">
                    <p>You don't have permission.</p>
                </div>
            <?php }
    }

    function admin_page_html() {?>
        <div class="wrap">
            <h1>Plugin Boilerplate Settings</h1>
            <?php isset($_POST['justsubmitted']) ? $this->handle_form() : ''; ?>
            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php wp_nonce_field('saveAPIToken','secureNonce') ?>
                <label for="api_token_id"><p>Enter your API Key</p></label>
                <div class="admin-container">
                    <input type="text" name="api_token_id" id="api_token_id" placeholder='your API token id' value="<?php echo esc_attr(get_option('api_token_store'))?>">
                </div>
                <input type="submit" name="submit" id="submit" value="Save Changes" class="button button-primary">
            </form>
        </div>
    <?php }
}
    
    $curlBoilerplate = new CurlBoilerplate();
    $shortcode = new Shortcode();