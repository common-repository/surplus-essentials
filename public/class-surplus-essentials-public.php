<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/surplus-essentials/
 * @since      1.0.0
 *
 * @package    surplus_essentials
 * @subpackage surplus_essentials/public
 */

class Surplus_Essentials_Public {

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
		$this->version = STEP_PLUGIN_VERSION;

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
		 * defined in Surplus_Essentials_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Surplus_Essentials_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/surplus-essentials-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Surplus_Essentials_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Surplus_Essentials_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

       	wp_enqueue_script( 'isotope-pkgd', plugin_dir_url( __FILE__ ) . 'js/isotope.pkgd.min.js', array( 'jquery'), '3.0.5', true );
    			
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/surplus-essentials-public.js', array( 'jquery', 'masonry','jquery-ui-datepicker' ), $this->version, true );

        wp_enqueue_script( 'all', plugin_dir_url( __FILE__ ) . 'js/all.min.js', array(), '5.6.3', true );

		wp_enqueue_script( 'v4-shims', plugin_dir_url( __FILE__ ) . 'js/v4-shims.min.js', array(), '5.6.3', true );
	}
}
