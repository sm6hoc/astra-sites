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

			console.log( args.id );
			// fetch.umd.js

			fetch( AstraSitesAPI._api_url + args.slug, {
	            method: 'GET',
	            // credentials: 'include',
	            // localCache: true,
	            // cacheKey: 'astraJsonCache' + args.id,
	            cache: "force-cache",
	        }).then((response) => {
	        	console.log( response.headers );
	            return response.json();
	        }).then(( items ) => {
	        	// console.log( '------------t--------------' );
	        	// console.log( t );
	            // console.log( items );

	            // if( 'success' === status && XHR.getResponseHeader('x-wp-total') ) {

					// if( args.id ) {
					// 	AstraSitesAPI._stored_data[ args.id ] = $.merge( AstraSitesAPI._stored_data[ args.id ], items );
					// }

					var data = {
						args 		: args,
						items 		: items,
						items_count	: items.length, // XHR.getResponseHeader('x-wp-total') || 0,
					};

					console.log( args.trigger );
					console.log( '------------------------' );
					console.log( data );

					if( 'undefined' !== args.trigger && '' !== args.trigger ) {
						$(document).trigger( args.trigger, [data] );
					}

				// } else {
				// 	$(document).trigger( 'astra-sites-api-request-error' );
				// }
	            // Your json parsed response is available here, either direct from the server,
	            // or pulled from the cache if a cached value for the specified cacheKey is available.
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