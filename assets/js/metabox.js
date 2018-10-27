( function( $ ) {
	"use strict";

	$( document ).on( 'ready', function() {

		// Show/hide options
		var styleField      = $( '#butterbean-control-oig_instagram_style select' ),
			styleFieldVal  	= styleField.val(),
			styleSettings 	= $( '#butterbean-control-oig_instagram_likes, #butterbean-control-oig_instagram_comments, #butterbean-control-oig_instagram_caption, #butterbean-control-oig_instagram_caption_length' );

		styleSettings.hide();

		if ( styleFieldVal === 'default' ) {
			styleSettings.show();
		}

		styleField.change( function () {

			styleSettings.hide();

			if ( $( this ).val() == 'default' ) {
				styleSettings.show();
			}

		} );

	} );

} ) ( jQuery );