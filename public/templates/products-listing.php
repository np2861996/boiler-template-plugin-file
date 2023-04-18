<?php
/**
 * Template for display single post.
 * 
 * * php version 7.2.10
 *
 * @category Testing_Plugin
 * @package  Testing_Plugin
 * @author   Nikhil Patel <test@gmail.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link     https://nikhilpatel.com
 * @since    1.0.0
 */

if (! defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}
?>
<div class="products_products_list products_listing_page">
    <!-- Blog Event Listing -->
    <div class="products_products_listing">
        <div class="products_listing_container">
            <!-- Blog Listing Tabs & Search Box -->
            <div class="bl_search_tab">
                <!-- Blog Listing Search Bar -->
                <form id="products_search_form" method="post">
                    <div class="bl_search_box flex_box">
                        <input type="text" class="search_box products_search_text" />
                        <button type="submit" 
                        class="button elementor-button ql_products_search">
                        Search
                    </button>
                    </div>
                </form>
            </div>
            <!-- Blog Listing Tabs Content -->
            <div class="products_listing_tabs_content">
                <div class="products_listing_related_post homepage_products_section" 
                style="position:relative;">
                    <div class="products_listing_container products_column">
                        <div class="homepage_products">
                        </div>
                        <div class="bl_content_btn flex_box" style="display:none;">
                            <a href="javascript:void(0);" 
                            class="button elementor-button products_loadmore">
                            Load More
                        </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
