(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	var ajax_url           = TEST_Public_JS_OBJ.ajaxurl;

	get_products_list();

	/**
	 * Get QUery string variable value.
	 */
	function ql_get_query_variable(  ) {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}

	// Ajax call function for fetch products.
	function get_products_list( category_id = null, paged = null, searchtext = null ) {
		var params       = ql_get_query_variable();
		if ( $.inArray( 'category', params ) !== -1 ) {
			category_id = $('.ql_products_category[data-slug="'+ params['category'] +'"]').data( 'category_id' ); 
		}
		if ( $.inArray( 'text', params ) !== -1 ) {
			searchtext = params['text'];
		}
		//alert(searchtext)
		var post_data = { 'paged': paged, 'search' : searchtext, 'action' : 'products_listing_filter_ajax_callback' };
		$.ajax( {
			url: ajax_url,
			type: 'POST',
			data: post_data,
			dataType: 'json',
			beforeSend: function() {
				$('.products_listing_tabs_content .normal_loader').addClass('active');
			},
			success: function( response ) {
				if ( 'products-fetch-success' === response.data.code ) {
					if ( null != category_id ) {
						$( '.ql_products_category' ).removeClass( 'active' );
						$( '.ql_products_category[data-category_id="'+ category_id +'"]' ).addClass( 'active' );
					}
					if ( null != searchtext ) {
						$('.ql_search_text').val( searchtext );
					}
					if ( ( null != category_id || null != searchtext ) && null == paged ) {
						$( '.products_listing_tabs_content .homepage_products' ).html( response.data.html );
					} else {
						$( '.products_listing_tabs_content .homepage_products' ).append( response.data.html );
					}
					if( 0 === response.data.paged ) {
						$( '.products_listing_tabs_content .bl_content_btn' ).hide();
					}else{
						$( '.products_listing_tabs_content .bl_content_btn' ).show();
						$( '.products_listing_tabs_content .products_loadmore' ).attr( 'data-paged', response.data.paged );
					}
					//ql_equal_heights('.products_listing_tabs_content .products_description h4', 115 );
					/*$( 'html, body' ).animate( {
						scrollTop: $( '.products_listing_tabs_content .homepage_products' ).offset().top - 200
					}, 2000 );*/
				}else{
					$( '.products_listing_tabs_content .homepage_products' ).html( response.data.html );
					$( '.products_listing_tabs_content .bl_content_btn' ).hide();
				}
			},
			complete: function() {
				$('.products_listing_tabs_content .normal_loader').removeClass('active');
			}
		} );
	}


	$( document ).on( 'click', '.products_loadmore', function( e ) {
		e.preventDefault();
		var this_element = $( this );
		var category_id  = null;
		var paged        = this_element.attr('data-paged');
		var searchtext   = null;
		var params       = ql_get_query_variable();
		const url = new URL(window.location);
		if ( $.inArray( 'text', params ) !== -1 ) {
			url.searchParams.set( 'text', params['text'] );
			searchtext = params['text']
		}
		if ( $.inArray( 'category', params ) !== -1 ) {
			url.searchParams.set( 'category', params['category'] );
			category_id = $('.ql_event_category[data-slug="'+ params['category'] +'"]').data( 'category_id' ); 
		}
		get_products_list( category_id, paged, searchtext );
	} );

	// Search button click in event listing.
	// Search button click in event listing.
	$( document ).on( 'click', '.ql_products_search' ,function(e) {
		e.preventDefault();
		var searchtext   = $( '.products_search_text' ).val();
		var category_id  = null;
		const url        = new URL(window.location);
		var params       = ql_get_query_variable();
		if ( '' !== searchtext ) {
			url.searchParams.set( 'text', searchtext );
		} else {
			url.searchParams.delete( 'text' );
		}
		if ( $.inArray( 'category', params ) !== -1 ) {
			url.searchParams.set( 'category', params['category'] );
			category_id = $('.ql_event_category[data-slug="'+ params['category'] +'"]').data( 'category_id' ); 
		}
		window.history.pushState({},'',url);

		alert(searchtext)
		get_products_list( category_id, null, searchtext );
	} );

})( jQuery );
