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
	            localCache: true,
	            cacheKey: 'AstraJsonCache-' + args.slug,
	            cache: "force-cache",
	        }).then(response => {
				if ( response.status === 200 ) {
		        	return response.json().then(items => ({
						args 		: args,
						items 		: items,
						items_count	: response.headers.get("x-wp-total"),
						item_pages	: response.headers.get("x-wp-totalpages"),
					}))
				} else {
					$(document).trigger( 'astra-sites-api-request-error' );
					return response.json();
				}
	        })
			.then(data => {

				console.log( data );

				if( 'undefined' !== data && '' !== data ) {
					console.log( args.trigger );
					console.log( '------------------------' );
					console.log( data );

					if( 'undefined' !== args.trigger && '' !== args.trigger ) {
						$(document).trigger( args.trigger, [data] );
					}
				}
	        });


			/*// Set API Request Data.
			var data = {
				url: AstraSitesAPI._api_url + args.slug,
			};

			if( astraRenderGrid.headers ) {
				data.headers = astraRenderGrid.headers;
			}

			$.ajax( data )
			.done(function( items, status, XHR ) {

				if( 'success' === status && XHR.getResponseHeader('x-wp-total') ) {

					if( args.id ) {
						AstraSitesAPI._stored_data[ args.id ] = $.merge( AstraSitesAPI._stored_data[ args.id ], items );
					}

					var data = {
						args 		: args,
						items 		: items,
						items_count	: XHR.getResponseHeader('x-wp-total') || 0,
					};

					console.log( args.trigger );
					console.log( '------------------------' );
					console.log( data );

					if( 'undefined' !== args.trigger && '' !== args.trigger ) {
						$(document).trigger( args.trigger, [data] );
					}

				} else {
					$(document).trigger( 'astra-sites-api-request-error' );
				}

			})
			.fail(function( jqXHR, textStatus ) {

				$(document).trigger( 'astra-sites-api-request-fail', [jqXHR, textStatus, args] );

			})
			.always(function() {

				$(document).trigger( 'astra-sites-api-request-always' );

			});*/

		},

	};

})(jQuery);