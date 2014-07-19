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
	protected $plugin_version = '1.4.0';

	/**
	 * fluidvids version, used for cache-busting of script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $fluidvids_version = '2.4.1';

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

		$this->setting_fluidvids = get_option( 'fluidvids' );

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

		$standard_selectors = "'iframe', 'object', ";
		$standard_players   = "'www.youtube.com', 'player.vimeo.com', ";

		$selectors = isset( $this->setting_fluidvids['selectors'] ) ? $standard_selectors . $this->setting_fluidvids['selectors'] : $standard_selectors;
		$players = isset( $this->setting_fluidvids['players'] ) ? $standard_players . $this->setting_fluidvids['players'] : $standard_players;

		$html = '<script>';
			$html .= 'fluidvids.init({';
				$html .= 'selector: [' . $this->esc_fluidvids( $selectors ) . '],';
				$html .= 'players: [' . $this->esc_fluidvids( $players ) . ']';
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

		if( false == $this->setting_fluidvids ) {
			add_option( 'fluidvids', apply_filters( 'fluidvids_default_options', '' ) );
		}

		add_settings_section(
			'fluidvids',
			__( 'FluidVids', $this->plugin_slug ),
			array( $this, 'fluidvids_callback' ),
			'media'
		);

		add_settings_field(
			'players',
			'<label for="players">' . __( 'Video Players Site URLs', $this->plugin_slug ) . '</label>',
			 array( $this, 'field_players' ),
			'media',
			'fluidvids'
		);

		add_settings_field(
			'selectors',
			'<label for="selectors">' . __( 'Selectors', $this->plugin_slug ) . '</label>',
			 array( $this, 'field_selectors' ),
			'media',
			'fluidvids'
		);

		register_setting(
			'media',
			'fluidvids',
			array( $this, 'sanitize' )
		);

	}

	/**
	 * fluidvids callback
	 *
	 * @since    1.0.0
	 */
	public function fluidvids_callback() {

		_e( 'Define additional players and selectors', $this->plugin_slug );

	}

	/**
	 * URL Field
	 *
	 * @since    1.0.0
	 */
	public function field_players() {

		$setting_value = isset( $this->setting_fluidvids['players'] ) ? $this->setting_fluidvids['players'] : '';
		echo '<input type="text" id="players" name="fluidvids[players]" value="' . $this->esc_fluidvids( $setting_value ) . '" /> e.g &#39;www.youtube.com&#39;, &#39;player.vimeo.com&#39;';

	}

	/**
	 * Selector Field
	 *
	 * @since    1.3.0
	 */
	public function field_selectors() {

		$setting_value = isset( $this->setting_fluidvids['selectors'] ) ? $this->setting_fluidvids['selectors'] : '';
		echo '<input type="text" id="selectors" name="fluidvids[selectors]" value="' . $this->esc_fluidvids( $setting_value ) . '" /> e.g &#39;iframe&#39;, &#39;object&#39;';

	}

	/**
	 * Sanitize
	 *
	 * @since    1.3.0
	 */
	public function sanitize( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {
				$output[$key] = sanitize_text_field( $input[ $key ] );
			}
		}

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'fluidvids_sanitize', $output, $input );

	}

	/**
	 * Escaping for HTML attributes.
	 *
	 * @since    1.3.0
	 */
	function esc_fluidvids( $text ) {
		$safe_text = wp_check_invalid_utf8( $text );
		$safe_text = _wp_specialchars( $safe_text, ENT_COMPAT );
		$safe_text = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", stripslashes( $safe_text ) );
		$safe_text = str_replace( "\r", '', $safe_text );

		return $safe_text;
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

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.3.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();

					restore_current_blog();
				}

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.3.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.3.0
	 */
	private static function single_activate() {
		$old_options = get_option( 'fluidvids-urls' );
		// Update keys
		if( isset( $old_options ) ) {
			$fluidvids['players'] = $old_options;
			// Update entire array
			update_option( 'fluidvids', $fluidvids );
			// Delete old array
			delete_option( 'fluidvids-urls' );
		}

	}

}
