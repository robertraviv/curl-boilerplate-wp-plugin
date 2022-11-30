<?php

Namespace CurlBoilerplate;

class Shortcode{

    function __construct() {
        add_shortcode( 'search-block', array($this,'search_shortcode') );
        add_action( 'rest_api_init', array($this,'register_form_api') );
        require_once plugin_dir_path( __FILE__ ) . 'lexica-url.php';
        }

        function search_shortcode(){
            // Load javascript for frontend search
            wp_enqueue_script(
                'search-form-query',
                plugin_dir_url( __FILE__ ) . 'js/SearchQuery.js',
                array('jquery'),
                1, // Version
                true // Load in Footer
            );
            wp_enqueue_style(
                'shortcodeCss',
                plugin_dir_url( __FILE__ ) . 'css/frontend-styles.css'
            );
            // Define the root url and nonce for custom end point
            wp_localize_script( 'search-form-query', 'searchQuery', array(
                'root_url' => get_site_url(),
                'nonce' => wp_create_nonce('wp_rest')
            ));
        
            ob_start();
            ?>   
            <div id="search-shortcode" class="class-shortcode">
                <div class="wrap">
                    <h1>Search AI Generated Images from Lexica.Art</h1>
                    <form id="form_lexica" method="POST">
                        <input type="text" id="search_lexica_term" placeholder="Search...">
                        <input type="submit" name="search_lexica_btn" id="search_lexica_btn" value="Search API" class="button button-primary">
                    </form>
                </div>
                <div class="loader hidden">Loading...</div>
                <div id="query-results" class="hidden">
            </div>

            <?php 
            $output = ob_get_clean();
            return $output;
        }
              
            function register_form_api(){
                register_rest_route( 'search-lexica-form/v1', 'send-query', array(
                    'methods'=>'POST',
                    'callback'=>array($this,'handle_query')
                ));
            }

            function handle_query($data) {    
                $q = sanitize_text_field($_POST['search_query']);
                // check if entered query to search
                if($q) {
                    $url = LexicaUrl::search_lexica($q);
                    $response = LexicaUrl::send_request($url,'GET');
                    $decoded_json = json_decode($response,true);      
                    $images = $decoded_json['images'];
                    wp_send_json_success( $images );   
                } else {
                    wp_die( 'No search query entered' );
                }
                wp_die( 'Finished' );
            }  
}