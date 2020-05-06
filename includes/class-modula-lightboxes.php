<?php

/**
 *
 */
class Modula_Lightboxes {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The name of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $plugin_name = 'Modula Lightboxes';

	/**
	 * Unique plugin slug identifier.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $plugin_slug = 'modula-lightboxes';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Load the plugin textdomain.
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_lightboxes' ) );

		add_action( 'modula_scripts_after_wp_modula', array($this,'modula_lightbox_backbone'));

		// Filter Modula Lightboxes Fields
		add_filter( 'modula_gallery_fields', array( $this, 'modula_lightboxes_fields' ) );

		add_filter( 'modula_necessary_scripts', array( $this, 'lightboxes_scripts' ), 999, 2 );

		add_filter( 'modula_necessary_styles', array( $this, 'lightboxes_styles' ), 999, 2 );

		add_filter( 'modula_lightbox_values', array( $this, 'extra_lightboxes' ),15 ,1 );

		add_filter( 'modula_gallery_settings', array( $this, 'modula_lightboxes_js_config' ), 20, 2 );

		add_filter( 'modula_shortcode_item_data', array( $this, 'lightboxes_item_data' ), 15, 3 );

		add_filter( 'modula_disable_lightboxes', '__return_false' );

	}


	/**
	 * Loads the plugin textdomain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_slug, false, MODULA_LIGHTBOXES_PATH . '/languages/' );
	}


	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object The Modula_Lighboxes object.
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {

		if ( !isset( self::$instance ) && !( self::$instance instanceof Modula_Lightboxes ) ) {
			self::$instance = new Modula_Lightboxes();
		}

		return self::$instance;

	}


	/**
	 * Add extra lightboxes to lightbox options
	 *
	 * @param $lightboxes
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function extra_lightboxes( $lightboxes ) {

		$lightboxes = array_merge( $lightboxes, array(
			"magnific",
			"prettyphoto",
			"swipebox",
			"lightbox2",
			"lightgallery",
		) );

		return $lightboxes;
	}


	/**
	 * Register our lightboxes
	 *
	 * @since 1.0.0
	 */
	public function register_lightboxes() {
		// Register lightgallery
		wp_register_style( 'modula-lightgallery', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/lightgallery/css/lightgallery.min.css', MODULA_LIGHTBOXES_VERSION, null );
		wp_register_script( 'modula-lightgallery', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/lightgallery/js/lightgallery.min.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );

		// Register magnific popup
		wp_register_style( 'modula-magnific-popup', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/magnific/magnific-popup.css', MODULA_LIGHTBOXES_VERSION, null );
		wp_register_script( 'modula-magnific-popup', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/magnific/jquery.magnific-popup.min.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );

		// Register prettyphoto
		wp_register_style( 'modula-prettyphoto', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/prettyphoto/style.css', MODULA_LIGHTBOXES_VERSION, null );
		wp_register_script( 'modula-prettyphoto', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/prettyphoto/script.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );

		// Register swipebox
		wp_register_style( 'modula-swipebox', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/swipebox/css/swipebox.min.css', MODULA_LIGHTBOXES_VERSION, null );
		wp_register_script( 'modula-swipebox', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/swipebox/js/jquery.swipebox.min.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );

		// Register lightbox2
		wp_register_style( 'modula-lightbox2', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/lightbox/lightbox.css', MODULA_LIGHTBOXES_VERSION, null );
		wp_register_script( 'modula-lightbox2', MODULA_LIGHTBOXES_URL . 'assets/lightboxes/lightbox/lightbox.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );

		wp_register_script( 'modula-lightboxes', MODULA_LIGHTBOXES_URL . 'assets/js/modula-lightboxes.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );
	}

  /**
    * Add Modula Lightboxes field
    *
    * @param $fields
    *
    * @return mixed
    *
    * @since 1.0.0
	*/
    public function modula_lightboxes_fields( $fields ) {

        $fancybox_notice = array(
        "use-fancybox" => array (
            "name"        => '',
            "type"        => "content",
            "content"     => $this->fancybox_features(),
            "priority"    => 5,
        ));

        $other_lightboxes = array(
            "fancybox"     => esc_html__( 'Fancybox', 'modula-lightboxes' ),
            "swipebox"     => esc_html__( 'Swipebox', 'modula-lightboxes' ),
            "magnific"     => esc_html__( 'Magnific Gallery', 'modula-lightboxes' ),
            "lightgallery" => esc_html__( 'LightGallery', 'modula-lightboxes' ),
            "lightbox2"    => esc_html__( 'Lightbox', 'modula-lightboxes' ),
            "prettyphoto"  => esc_html__( 'PrettyPhoto', 'modula-lightboxes' ),
        );

        $fields['lightboxes'] = wp_parse_args( $fancybox_notice, $fields['lightboxes'] );
        $fields['lightboxes']['lightbox']['values'] = wp_parse_args( $other_lightboxes, $fields['lightboxes']['lightbox']['values'] );

        return $fields;
    }

    /**
     * Display fancybox features 
     * 
     * @return $html
     */
    public function fancybox_features() {

        $featuresTooltips = array(
            array(
                'tooltip' => esc_html__('Enable this to allow loop navigation inside lightbox','modula-lightboxes'),
                'feature' => esc_html__('Loop Navigation','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Toggle on to show the navigation arrows','modula-lightboxes'),
                'feature' => esc_html__('Navigation Arrows','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Toggle on to show the image title in the lightbox above the caption.','modula-lightboxes'),
                'feature' => esc_html__('Show Image Title','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Toggle on to show the image caption in the lightbox.','modula-lightboxes'),
                'feature' => esc_html__('Show Image Caption','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Select the position of the caption and title inside the lightbox.','modula-lightboxes'),
                'feature' => esc_html__('Title and Caption Position','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Enable or disable keyboard/mousewheel navigation inside lightbox','modula-lightboxes'),
                'feature' => esc_html__('Keyboard/mousewheel Navigation','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Display the toolbar which contains the action buttons on top right corner.','modula-lightboxes'),
                'feature' => esc_html__('Toolbar','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Close the slide if user clicks/double clicks on slide( not image ).','modula-lightboxes'),
                'feature' => esc_html__(' Close on slide click / double click','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Display the counter at the top left corner.','modula-lightboxes'),
                'feature' => esc_html__('Infobar','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Open the lightbox automatically in Full Screen mode.','modula-lightboxes'),
                'feature' => esc_html__('Auto start in Fullscreen','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Place the thumbnails at the bottom of the lightbox. This will automatically put `y` axis for thumbnails.','modula-lightboxes'),
                'feature' => esc_html__('Thumbnails at bottom ','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Select vertical or horizontal scrolling for thumbnails','modula-lightboxes'),
                'feature' => esc_html__('Thumb axis','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Display thumbnails on lightbox opening.','modula-lightboxes'),
                'feature' => esc_html__('Auto start thumbnail ','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Choose the lightbox transition effect between slides.','modula-lightboxes'),
                'feature' => esc_html__('Transition Effect ','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Allow panning/swiping','modula-lightboxes'),
                'feature' => esc_html__('Allow Swiping ','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Toggle ON to show all images','modula-lightboxes'),
                'feature' => esc_html__('Show all images ','modula-lightboxes'),
            ),
            array(
                'tooltip' => esc_html__('Choose the open/close animation effect of the lightbox','modula-lightboxes'),
                'feature' => esc_html__('Open/Close animation','modula-lightboxes') ,
            ),
            array(
                'tooltip' => esc_html__('Set the lightbox background color','modula-lightboxes'),
                'feature' => esc_html__('Lightbox background color','modula-lightboxes'),
            ));

        $html = esc_html__('Did you consider using Fancybox ? It features more options such as :','modula-lightboxes');

        $html .= '<ul class="modula-lightbox-features">';
        foreach( $featuresTooltips as $feature ) {

            $html .= '<li>';
            $html .= '<div class="modula-tooltip"><span>[?]</span>';
            $html .= '<div class="modula-tooltip-content">' . esc_html( $feature['tooltip']) . '</div>';
            $html .= '</div>';
            $html .= "<p>" . esc_html($feature['feature']) . "</p>";
            $html .= '</li>';
            
        }
        $html .= '</ul>';

        $html .= '<p><strong> '.esc_html__('Since Modula v2.3.0 Fancybox is the only officially supported lightbox.','modula-lightboxes').' </strong> </p>';

        return $html ;
    }


	/**
	 * Filter the js_config. Need to add lightbox to options
	 *
	 * @param $js_config
	 * @param $settings
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function modula_lightboxes_js_config( $js_config, $settings ) {

		if ( isset( $settings['lightbox'] ) ) {
			$js_config['lightbox'] = $settings['lightbox'];
		}

		return $js_config;
	}
	/**
	 * Add lightbox conditions
	 */
	public function modula_lightbox_backbone() {

		wp_enqueue_script( 'modula-lightbox-conditions', MODULA_LIGHTBOXES_URL . 'assets/js/wp-modula-lightboxes-conditions.js', array( 'jquery' ), MODULA_LIGHTBOXES_VERSION, true );
		wp_enqueue_style( 'modula-lightbox-css', MODULA_LIGHTBOXES_URL . 'assets/css/lightbox-admin.css', MODULA_LIGHTBOXES_VERSION );
	}

	/**
	 * Add required scripts
	 *
	 * @param $scripts
	 * @param $settings
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function lightboxes_scripts( $scripts, $settings ) {

		// If not Fancybox lets do stuff
		if ( 'fancybox' != $settings['lightbox'] ) {

			$ligtboxes_options = array(
				'lightboxes' => array(
					'magnific'     => array(
						'options' => array(
							'type'            => 'image',
							'image'           => array( 'titleSrc' => 'data-title' ),
							'gallery'         => array( 'enabled' => 'true' ),
							'delegate'        => 'a.tile-inner',
							'fixedContentPos' => 'true'
						),
					),
					'prettyphoto'  => array(
						'options' => array( 'social_tools' => '', 'overlay_gallery_max' => 300 ),
					),
					'swipebox'     => array(
						'options' => array( 'loopAtEnd' => 'true' ),
					),
					'lightgallery' => array(
						'options' => array( 'selector' => 'a.tile-inner' ),
					)
				),
			);

			// We need this
			$scripts[] = 'modula-lightboxes';
			wp_localize_script( 'modula-lightboxes', 'wpModulaLightboxHelper', $ligtboxes_options );

			foreach ( $scripts as $key => $script ) {
				if ( 'modula-fancybox' == $script ) {
					unset( $scripts[$key] );
				}
			}

			switch ( $settings['lightbox'] ) {
				case 'lightgallery':
					$scripts[] = ( 'modula-lightgallery' );
					break;
				case 'magnific':
					$scripts[] = ( 'modula-magnific-popup' );
					break;
				case 'prettyphoto':
					$scripts[] = ( 'modula-prettyphoto' );
					break;
				case 'swipebox':
					$scripts[] = ( 'modula-swipebox' );
					break;
				case 'lightbox2':
					$scripts[] = ( 'modula-lightbox2' );
					wp_localize_script( 'modula-lightbox2', 'modulaLightboxHelper', array() );
					break;
			}

		}

		return $scripts;
	}


	/**
	 * Add required styles
	 *
	 * @param $styles
	 * @param $settings
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function lightboxes_styles( $styles, $settings ) {

		// If not Fancybox lets do stuff
		if ( 'fancybox' != $settings['lightbox'] ) {

			foreach ( $styles as $key => $style ) {
				if ( 'modula-fancybox' == $style ) {
					unset( $styles[$key] );
				}
			}

			switch ( $settings['lightbox'] ) {
				case 'lightgallery':
					$styles[] = ( 'modula-lightgallery' );
					break;
				case 'magnific':
					$styles[] = ( 'modula-magnific-popup' );
					break;
				case 'prettyphoto':
					$styles[] = ( 'modula-prettyphoto' );
					break;
				case 'swipebox':
					$styles[] = ( 'modula-swipebox' );
					break;
				case 'lightbox2':
					$styles[] = ( 'modula-lightbox2' );
					break;
			}

		}

		return $styles;
	}


	/**
	 * Add required attributes for lightboxes
	 *
	 * @param $item
	 * @param $image
	 * @param $settings
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function lightboxes_item_data( $item, $image, $settings ) {
		//@todo - check why we search for data-fancybox when it is not used anymore && isset( $item['link_attributes']['data-fancybox'] )

		if ( 'fancybox' != $settings['lightbox']  ) {
			unset( $item['link_attributes']['data-fancybox'] );

			if ( 'lightbox2' == $settings['lightbox'] ) {
				$item['link_attributes']['data-lightbox'] = esc_attr( $settings['gallery_id'] );
			} else if ( 'prettyphoto' == $settings['lightbox'] ) {
				$item['link_attributes']['rel'] = esc_attr( 'prettyPhoto[' . $settings['gallery_id'] . ']' );
			} else {
				$item['link_attributes']['rel'] = esc_attr( $settings['gallery_id'] );
			}
		}

		return $item;
	}

}