<?php
/**
 * FluidVids for WordPress
 *
 * @package   FluidVids for WordPress
 * @author    Ulrich Pogson <ulrich@pogson.ch>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/fluidvids/
 * @copyright 2013 Ulrich Pogson
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Initial FluidVids class
 *
 * @since   1.0.0
 */
class FluidVids {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $plugin_version = '1.1.0';

	/**
	 * fluidvids version, used for cache-busting of script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $fluidvids_version = '2.1.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'fluidvids';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_settings_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load public-facing JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'fluidvids_options' ), 21 );

		// Add action links
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'fluidvids.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Add fluidvids settings to media settings page
		add_filter( 'admin_init' , array( $this , 'register_fields' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug, plugins_url( '/js/fluidvids.min.js', __FILE__ ), array(), $this->fluidvids_version, true );

	}

	/**
	 * Add fluidvids options
	 *
	 * @since    1.0.0
	 */
	public function fluidvids_options() {

		$standard_video_urls = "'www.youtube.com', 'player.vimeo.com',";
		$video_urls = get_option( 'fluidvids-urls' );

		$html = '<script>';
			$html .= 'Fluidvids.init({';
				if ( ! empty( $video_urls ) ) {
					$html .= 'players: [' . $standard_video_urls . $video_urls . ']';
				}
			$html .= '});';
		$html .=' </script>';

		echo $html;

	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function register_fields() {

		add_settings_section(
			'fluidvids',
			__( 'FluidVids', $this->plugin_slug ),
			array( $this, 'fluidvids_callback' ),
			'media'
		);

		add_settings_field(
			'fluidvids-urls',
			'<label id="fluidvids-urls" for="fluidvids-urls">' . __( 'Video Site URLs', $this->plugin_slug ) . '</label>',
			 array( $this, 'field_url' ),
			'media',
			'fluidvids'
		);

		register_setting(
			'media',
			'fluidvids-urls'
		);

	}

	/**
	 * fluidvids callback
	 *
	 * @since    1.0.0
	 */
	public function fluidvids_callback() {

		echo sprintf(
			__( 'Add video player URLs with this format %s. To add more players, specify the domains that you need FluidVids to check against, be sure to include the subdomain for any videos that have a src with a subdomain.', $this->plugin_slug ),
			"'www.youtube.com', 'player.vimeo.com'"
		);

	}

	/**
	 * URL Field
	 *
	 * @since    1.0.0
	 */
	public function field_url() {

		$setting_value = esc_html( get_option( 'fluidvids-urls' ) );
		echo '<input type="text" id="fluidvids-urls" name="fluidvids-urls" value="' . $setting_value . '" />';

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-media.php#fluidvids-urls' ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

}