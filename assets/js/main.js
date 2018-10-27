var $j = jQuery.noConflict();

$j( document ).on( 'ready', function() {
	// Fit images
	oceanwpFitImages();
} );

/* ==============================================
FIT IMAGES
============================================== */
function oceanwpFitImage( $this ) {
	"use strict"

	var $imageParent = $this.find( '.ocean-instagram-image' ),
		$image 		 = $imageParent.find( 'img' ),
		image 		 = $image[0];

	if ( ! image ) {
		return;
	}

	var imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
		imageRatio 		 = image.naturalHeight / image.naturalWidth;

	$imageParent.toggleClass( 'ocean-fit-height', imageRatio < imageParentRatio );

}

function oceanwpFitImages() {
	"use strict"

	 $j( '.ocean-instagram-item' ).each( function() {
		var $this 	= $j( this ),
			$image  = $this.find( '.ocean-instagram-image img' );

		oceanwpFitImage( $this );

		$image.on( 'load', function() {
			oceanwpFitImage( $this );
		} );
	} );

}