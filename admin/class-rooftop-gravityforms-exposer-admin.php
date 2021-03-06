<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://errorstudio.co.uk
 * @since      1.0.0
 *
 * @package    Rooftop_Forms_Exposer
 * @subpackage Rooftop_Forms_Exposer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rooftop_Forms_Exposer
 * @subpackage Rooftop_Forms_Exposer/admin
 * @author     Error <info@errorstudio.co.uk>
 */
class Rooftop_Forms_Exposer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rooftop_Forms_Exposer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rooftop_Forms_Exposer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rooftop-gravityforms-exposer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rooftop_Forms_Exposer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rooftop_Forms_Exposer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rooftop-gravityforms-exposer-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * We dont do any rendering of gravityforms in WP so we remove the shortcode tags to maintain
     * and replace it with our own callback which returns a <form> with data attributes (similar to how we handle internal site links)
     */
    public function remove_gravityforms_shortcodes() {
        remove_shortcode('gravityform');
        remove_shortcode('gravityforms');

        add_shortcode('gravityform', array($this, 'parse_gravityforms_shortcode'));
        add_shortcode('gravityforms', array($this, 'parse_gravityforms_shortcode'));
    }

    public function parse_gravityforms_shortcode($shortcode_config) {
        $attributes = [];
        foreach($shortcode_config as $key => $value) {
            $attributes["data-$key"] = $value;
        }

        $form_data_attributes = http_build_query($attributes, 'x', ' ');
        return "<form ${form_data_attributes}></form>";
    }
}
