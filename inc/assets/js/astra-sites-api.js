(function($){

	AstraSitesAPI = {

		_api_url      : astraSitesApi.ApiURL,
		_stored_data  : {
			'astra-site-category' : [],
			'astra-site-page-builder': [],
			'astra-sites' : [],
		},

		/**
		 * API Request
		 */
		_api_request: function( args ) {

			fetch( AstraSitesAPI._api_url + args.slug, {
	            method: 'GET',
	            cache: 'force-cache',
	        }).then(response => {
				if ( response.status === 200 ) {
		        	return response.json().then(items => ({
						args 		: args,
						items 		: items,
						items_count	: response.headers.get('x-wp-total'),
						item_pages	: response.headers.get('x-wp-totalpages'),
					}))
				} else {
					$(document).trigger( 'astra-sites-api-request-error' );
					return response.json();
				}
	        })
			.then(data => {
				if( 'undefined' !== data && '' !== data ) {
					if( 'undefined' !== args.trigger && '' !== args.trigger ) {
						$(document).trigger( args.trigger, [data] );
					}
				}
	        });

		},

	};

})(jQuery);