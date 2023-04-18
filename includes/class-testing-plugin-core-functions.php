<?php

/**
 * The public-facing functionality of the plugin.
 * php version 7.2.10
 *
 * @category Testing_Plugin
 * @package  Testing_Plugin
 * @author   Nikhil Patel <test@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link     https://nikhilpatel.com
 * @since    1.0.0
 **/

    /**
     * Check if the function exists.
     */
if (! function_exists('Ql_Products_Custom_Post_type') ) {
    /**
     * Function for register event custom post type.
     * 
     * @return void
     */
    function Ql_Products_Custom_Post_type()
    {
        $labels = array(
        'name'                  => _x(
            'Products', 
            'Products General Name', 
            'products'
        ),
        'singular_name'         => _x(
            'Products', 
            'Products Singular Name', 
            'products'
        ),
        'menu_name'             => __('Products', 'products'),
        'name_admin_bar'        => __('Products', 'products'),
        'archives'              => __('Products Archives', 'products'),
        'attributes'            => __('Products Attributes', 'products'),
        'parent_item_colon'     => __('Parent Products:', 'products'),
        'all_items'             => __('All Products', 'products'),
        'add_new_item'          => __('Add New Products', 'products'),
        'add_new'               => __('Add Products', 'products'),
        'new_item'              => __('New Products', 'products'),
        'edit_item'             => __('Edit Products', 'products'),
        'update_item'           => __('Update Products', 'products'),
        'view_item'             => __('View Products', 'products'),
        'view_items'            => __('View Products', 'products'),
        'search_items'          => __('Search Products', 'products'),
        'not_found'             => __('Not Products found', 'products'),
        'not_found_in_trash'    => __('Not Products found in Trash', 'products'),
        'featured_image'        => __('Featured Image', 'products'),
        'set_featured_image'    => __('Set featured image', 'products'),
        'remove_featured_image' => __('Remove featured image', 'products'),
        'use_featured_image'    => __('Use as featured image', 'products'),
        'insert_into_item'      => __('Insert into Team', 'products'),
        'uploaded_to_this_item' => __('Uploaded to this Training', 'products'),
        'items_list'            => __('Products list', 'products'),
        'items_list_navigation' => __('Products list navigation', 'products'),
        'filter_items_list'     => __('Filter products list', 'products'),
        );
        $args   = array(
        'label'               => __('Products', 'products'),
        'description'         => __('Its custom post type of Products', 'products'),
        'labels'              => $labels,
        'taxonomies'          => array( 'gender' ),
        'supports'            => array( 
            'title', 
            'editor', 
            'author', 
            'thumbnail', 
            'custom-fields', 
            'page-attributes', 
            'post-formats' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-calendar-alt',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'show_in_rest'        => true,
        );
        register_post_type('products', $args);
    }
}

// Check if function exists.
if (! function_exists('Ql_Gender_Custom_taxonomy') ) {
    /**
     * Function for register genre custom post type.
     * 
     * @return void
     */
    function Ql_Gender_Custom_taxonomy()
    {
        $labels = array(
        'name'              => _x('Gender', 'taxonomy gender name'),
        'singular_name'     => _x('Gender', 'taxonomy singular name'),
        'search_items'      => __('Search Gender'),
        'all_items'         => __('All Gender'),
        'parent_item'       => __('Parent Gender'),
        'parent_item_colon' => __('Parent Gender:'),
        'edit_item'         => __('Edit Gender'),
        'update_item'       => __('Update Gender'),
        'add_new_item'      => __('Add New Gender'),
        'new_item_name'     => __('New Gender'),
        'menu_name'         => __('Gender'),
        );

        register_taxonomy(
            'gender',
            array( 'products' ),
            array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'gender' ),
            )
        );
    }
}


if (! function_exists('Add_Page_On_activation') ) {
    /**
     *  Function
     *
     * @return void
     */
    function Add_Page_On_activation()
    {
        $page = get_page_by_title('Products Page');
        
        if (! $page ) {
            $page = array(
            'post_title'    => 'Products Page',
            'post_content'  => '[products]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page'
            );

            // Insert the page into the database
            $page_id = wp_insert_post($page);

            // Store the page ID in the plugin options
            update_option('my_plugin_page_id', $page_id);
        } else {
            return;
        }
    }
}


/**
 * Check if the function exists.
 */
if (! function_exists('ql_get_posts') ) {
    /**
     *  Function
     *
     * @param string $post_type      Post type.
     * @param int    $paged          Paged value.
     * @param int    $posts_per_page Posts per page.
     * @param array  $search_term    Get the posts.
     * 
     * @return void
     */
    function Ql_Get_posts( 
        $post_type = 'post', 
        $paged = 1, 
        $posts_per_page = -1,  
        $search_term = '' 
    ) {
        // Prepare the arguments array.
        $args = array(
        'post_type'      => $post_type,
        'paged'          => $paged,
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'orderby'        => 'date',
        'order'          => 'DESC',
        's'              => (!empty($search_term) ? $search_term : ''),
        );

        /**
         * Posts/custom posts listing arguments filter.
         *
         * This filter helps to modify the arguments for 
         * retreiving posts of default/custom post types.
         *
         * @param array $args Holds the post arguments.
         */
        $args = apply_filters('ql_posts_args', $args);

        return new WP_Query($args);
    }
}
    /**
 * Check if function exists.
 */
if (! function_exists('Products_Listing_html') ) {
    /**
     * Function for prepare html for listing movies.
     *
     * @param array $products_ids Holds movies id.
     * 
     * @return Products_Listing_html
     */
    function Products_Listing_html( $products_ids )
    {
        ob_start();
        // Check if products id is empty.
        if (empty($products_ids) ) {
            return;
        }
        ?>
        <?php
        foreach ( $products_ids as $products_id ) {
            $post_obj          
                = get_post($products_id);
            $post_title        
                = wp_trim_words(get_the_title($products_id), 5, '...');
            $post_terms        
                = get_the_terms($products_id, 'category');
            $post_content      
                = wp_trim_words(get_the_excerpt($products_id), 19, '...'); 
            $post_link         
                = get_permalink($products_id);
            $fetured_image_arr 
                = wp_get_attachment_image_src(
                    get_post_thumbnail_id($products_id), 
                    'full'
                );
            $fetured_image     
                = ! empty($fetured_image_arr) ? 
            $fetured_image_arr[0] : '';
            $post_date         = get_the_date('F j, Y', $products_id);
            $post_term         = get_the_category($products_id);
            $post_url          = get_permalink($products_id);
            $term_name         = array();
            foreach ( $post_term as $term ) {
                $term_name[] = $term->name;
            }
            $cat_name = ! empty($term_name) ? implode(',', $term_name) . ' - ' : '';
            ?>
            <div class="products_one_column products_column">
                <div class="products_column_container">
                    <div class="products_img bg_gradient">
                        <a href="<?php echo esc_url($post_url); ?>">
                            <img 
                            src="<?php echo esc_url($fetured_image); ?>" 
                            alt="image_1" />
                        </a>
                        <div class="products_post_type">
                            <span class="bg_gradient">
            <?php echo esc_html($post_date); ?>
                            </span>
                        </div>
                    </div>
                    <div class="products_content">
                        <div class="products_description">
                            <h4><a href="<?php echo esc_url($post_url); ?>">
            <?php echo esc_html($post_title); ?>
                                </a>
                            </h4>
                            <p><?php echo $post_content; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        return ob_get_clean();
    }
}
