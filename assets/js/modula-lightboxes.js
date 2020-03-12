function modula_lightboxes_enable_lightbox( data ){

    if ( 'undefined' == typeof wpModulaLightboxHelper.lightboxes[data.options.lightbox] ) { return; }

    var currentLightbox = wpModulaLightboxHelper.lightboxes[data.options.lightbox];

    if ('magnific' == data.options.lightbox && 'function' == typeof jQuery.fn['magnificPopup']) {
        currentLightbox['options'].delegate = "a.tile-inner";
        currentLightbox['options'].callbacks = {
            beforeOpen: function() {
                jQuery(document).trigger('modula_magnific_lightbox_before_open', [event, data, this]);
            },
            elementParse: function(item) {
                jQuery(document).trigger('modula_magnific_lightbox_elementparse', [event, data, this, item]);
            },
            change: function() {
                jQuery(document).trigger('modula_magnific_lightbox_change', [event, data, this]);
            },
            resize: function() {
                jQuery(document).trigger('modula_magnific_lightbox_resize', [event, data, this]);
            },
            open: function () {
                jQuery(document).trigger('modula_magnific_lightbox_open', [event, data, this]);
            },
            beforeClose: function() {
                jQuery(document).trigger('modula_magnific_lightbox_before_close', [event, data, this]);
            },
            close: function() {
                jQuery(document).trigger('modula_magnific_lightbox_close', [event, data, this]);
            },
            afterClose: function() {
                jQuery(document).trigger('modula_magnific_lightbox_after_close', [event, data, this]);
            },
            imageLoadComplete: function () {
                jQuery(document).trigger('modula_magnific_lightbox_image_load_complete', [event, data, this]);
            }
        };
        jQuery( data.element ).magnificPopup( currentLightbox['options'] );
    } else if ('prettyphoto' == data.options.lightbox && 'function' == typeof jQuery.fn['prettyPhoto']) {
        // Callbacks
        currentLightbox['options']['changepicturecallback'] = function() {
            jQuery(document).trigger('modula_prettyphoto_lightbox_change', [ data, this ]);
        };
        currentLightbox['options']['callback'] = function() {
            jQuery(document).trigger('modula_prettyphoto_lightbox_close', [ data, this ]);
        };

        jQuery(data.element).find('a.tile-inner').prettyPhoto(currentLightbox['options']);
    }  else if ('swipebox' == data.options.lightbox && 'function' == typeof jQuery.fn['swipebox']) {
        // Callbacks
        currentLightbox['options']['beforeOpen'] = function() {
            jQuery(document).trigger('modula_swipebox_lightbox_before_open', [ data, this ]);
        };
        currentLightbox['options']['afterOpen'] = function() {
            jQuery(document).trigger('modula_swipebox_lightbox_after_open', [ data, this ]);
        };
        currentLightbox['options']['afterClose'] = function() {
            jQuery(document).trigger('modula_swipebox_lightbox_after_close', [ data, this ]);
        };
        currentLightbox['options']['nextSlide'] = function() {
            setTimeout( function(){ jQuery(document).trigger('modula_swipebox_lightbox_next_slide', [ data, this ]) }, 500);
        };
        currentLightbox['options']['prevSlide'] = function() {
            setTimeout( function(){ jQuery(document).trigger('modula_swipebox_lightbox_prev_slide', [ data, this ]) },500);
        };

        jQuery(data.element).find('a.tile-inner').swipebox(currentLightbox['options']);
    } else if ('lightgallery' == data.options.lightbox && 'function' == typeof jQuery.fn['lightGallery']) {
        if ( typeof jQuery(data.element).data('lightGallery') != 'undefined' ) {
            jQuery(data.element).data('lightGallery').destroy(true);
        }
        currentLightbox['options'].selector = "a.tile-inner";
        jQuery(data.element).lightGallery(currentLightbox['options']);
    }

}

jQuery(document).on( 'modula_api_after_init', function (event, data) {

    // initialize lightboxes
    modula_lightboxes_enable_lightbox( data );

});