/* global elementor */
jQuery( document ).ready( function( $ ) {
	function handleFonts( font ) {
		elementor.helpers.enqueueFont( font );
	}

	const keys = [
		'ast_heading_1',
		'ast_heading_2',
		'ast_heading_3',
		'ast_heading_4',
		'ast_heading_5',
		'ast_heading_6',
		'ast_default_heading',
		'ast_body',
		'ast_paragraph',
		'ast_main_color',
		'ast_text_color',
		'ast_link_color',
		'ast_link_hover_color'
	];

	for ( let index = 0; index < keys.length; index++ ) {
		const element = keys[ index ] + '_font_family';

		elementor.settings.page.addChangeCallback( element, handleFonts );
	}
} );
