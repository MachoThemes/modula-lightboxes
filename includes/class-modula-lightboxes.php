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

		// Filter Modula Lightboxes Fields
		add_filter( 'modula_gallery_fields', array( $this, 'modula_lightboxes_fields' ) );

		add_filter( 'modula_necessary_scripts', array( $this, 'lightboxes_scripts' ), 999, 2 );

		add_filter( 'modula_necessary_styles', array( $this, 'lightboxes_styles' ), 999, 2 );

		add_filter( 'modula_lightbox_values', array( $this, 'extra_lightboxes' ),15 ,1 );

		add_filter( 'modula_gallery_settings', array( $this, 'modula_lightboxes_js_config' ), 20, 2 );

		add_filter( 'modula_shortcode_item_data', array( $this, 'lightboxes_item_data' ), 15, 3 );

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

		$other_lightboxes = array(

			"fancybox"     => esc_html__( 'Fancybox', 'modula-lightboxes' ),
			"swipebox"     => esc_html__( 'Swipebox', 'modula-lightboxes' ),
			"magnific"     => esc_html__( 'Magnific Gallery', 'modula-lightboxes' ),
			"lightgallery" => esc_html__( 'LightGallery', 'modula-lightboxes' ),
			"lightbox2"    => esc_html__( 'Lightbox', 'modula-lightboxes' ),
			"prettyphoto"  => esc_html__( 'PrettyPhoto', 'modula-lightboxes' ),
		);

		$fields['lightboxes']['lightbox']['values'] = wp_parse_args( $other_lightboxes, $fields['lightboxes']['lightbox']['values'] );

		return $fields;
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

		if ( 'fancybox' != $settings['lightbox'] && isset( $item['link_attributes']['data-fancybox'] ) ) {
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