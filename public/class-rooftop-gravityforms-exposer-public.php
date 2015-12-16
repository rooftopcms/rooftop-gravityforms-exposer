<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://errorstudio.co.uk
 * @since      1.0.0
 *
 * @package    Rooftop_Forms_Exposer
 * @subpackage Rooftop_Forms_Exposer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rooftop_Forms_Exposer
 * @subpackage Rooftop_Forms_Exposer/public
 * @author     Error <info@errorstudio.co.uk>
 */
class Rooftop_Forms_Exposer_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rooftop-gravityforms-exposer-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rooftop-gravityforms-exposer-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * @internal param $data
     * @internal param $post
     * @internal param $request
     * @return mixed
     *
     * Parse the post_content for gravity form shortcodes, collect the form ID's with a regex and
     * then collect & cleanup the form object to include in our response
     */
    public function add_forms_to_response() {
        $types = get_post_types(array(
            'public' => true
        ));

        foreach($types as $key => $post_type) {
            register_rest_field($post_type, 'forms', array(
                    'get_callback'    => array( $this, 'get_forms' ),
                    'update_callback' => null,
                    'schema'          => null,
            ));
        }

    }

    /**
     * @param $object
     * @param $field
     * @param $request
     * @return array
     *
     * return all the forms associated with this post as the value to $field
     *
     */
    public function get_forms($object, $field, $request){
        $post = get_post($object['id']);
        $forms = [];

        preg_match_all('/\[gravityform id="(\d+)"[^\]]+]/', $post->post_content, $form_matches);

        if(count($form_matches) && count($form_matches[1])) {
            $forms = array_map(function($f) {
                $form = GFAPI::get_form($f);
                return $this->sanitise_form_object($form);
            }, $form_matches[1]);
        }

        return $forms;
    }

    /**
     * @param $form
     * @return mixed
     *
     * remove un-needed fields from the form field object
     */
    function sanitise_form_object(&$form){
        unset($form['version']);
        unset($form['useCurrentUserAsAuthor']);
        unset($form['postContentTemplateEnabled']);
        unset($form['postTitleTemplateEnabled']);
        unset($form['postTitleTemplate']);
        unset($form['postContentTemplate']);
        unset($form['pagination']);
        unset($form['firstPageCssClass']);
        unset($form['is_trash']);
        unset($form['is_active']);

        foreach($form['fields'] as $id => $field){
            unset($field['adminLabel']);
            unset($field['formId']);
            unset($field['pageNumber']);
            unset($field['allowsPrepopulate']);
            unset($field['displayAllCategories']);
        }

        return $form;
    }
}
