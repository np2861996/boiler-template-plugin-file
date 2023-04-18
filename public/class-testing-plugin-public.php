<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 * 
 * php version 7.2.10
 *
 * @category Testing_Plugin
 * @package  Testing_Plugin
 * @author   Nikhil Patel <test@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link     https://nikhilpatel.com
 * @since    1.0.0
 */
class Testing_Plugin_Public
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     * 
     * @return enqueue_styles
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Testing_Plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Testing_Plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name, 
            plugin_dir_url(__FILE__) . 'css/testing-plugin-public.css', 
            array(), 
            $this->version, 
            'all'
        );
        wp_enqueue_style(
            'main-js', 
            plugin_dir_url(__FILE__) . 'js/main.js', 
            array('jquery'), 
            $this->version, false
        );
        wp_enqueue_script(
            $this->plugin_name, plugin_dir_url(__FILE__) 
            . 'js/testing-plugin-public.js', 
            array( 'jquery' ), 
            $this->version, false
        );
        wp_localize_script(
            $this->plugin_name,
            'TEST_Public_JS_OBJ',
            array(

            'ajaxurl' => admin_url('admin-ajax.php'),

            )
        );

    }



    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     * 
     * @return enqueue_scripts
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Testing_Plugin_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Testing_Plugin_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script(
            $this->plugin_name, plugin_dir_url(__FILE__) 
            . 'js/testing-plugin-public.js', 
            array( 'jquery' ), $this->version, false
        );

    }

    /**
     * Function to return on init callback functions.
     *
     * @since 1.0.0
     * 
     * @return ql_init_callback
     */
    public function ql_init_callback()
    {
        ql_products_custom_post_type(); // Register movies custom post type.
        ql_gender_custom_taxonomy(); // Genre Texonomy
        add_page_on_activation(); // add page on init
    }

    /**
     * Movies list
     * 
     * @return products_listing_filter_ajax_callback
     */
    function products_listing_filter_ajax_callback()
    {

        
        $post_type    = filter_input(
            INPUT_POST, 
            'post_type', 
            FILTER_SANITIZE_STRING
        );
        $paged          = filter_input(
            INPUT_POST,
            'paged', 
            FILTER_SANITIZE_NUMBER_INT
        );
        $search_text    = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
        
        $paged          = ! empty($paged) ? (int) $paged : 1;
        $post_types     = ! empty($post_type)? 
        array( $post_type ) : array( 'products' );
        $all_products      = ql_get_posts('products', 1, -1, $search_text);
        $get_post_count = count($all_products->posts);
        $post_per_page  = $get_post_count > 9 ? 9 : -1;
        $get_products      = ql_get_posts(
            'products', 
            $paged, 
            $post_per_page, 
            $search_text
        );
        $html           = products_listing_html($get_products->posts);

        $page_count = (int) ceil($get_post_count / $post_per_page);
        $loadmore   = 0;
        if ($page_count === $paged ) {
            $loadmore = 0;
        } elseif ($page_count > 1 ) {
            $loadmore = $paged + 1;
        }

        if (! empty($all_products->posts) ) {
            $code = 'products-fetch-success';
            $html = $html;
        } else {
            $code = 'blog-fetch-failed';
            $html = 'Ooops..! Posts Not Found.';
        }

        // Send back the AJAX response.
        wp_send_json_success(
            array(
            'code'  => $code,
            'html'  => $html,
            'paged' => $loadmore,
            )
        );
        

        wp_die();
    }

    /**
     * Function for display blog listing.
     * 
     * @return products_Listing_Callback
     */
    public function products_Listing_Callback()
    {
        // Prepare HTML.
        ob_start();
        include TESTING_PLUGIN_PATH . 'public/templates/products-listing.php';

        return ob_get_clean();
    }

    



}
