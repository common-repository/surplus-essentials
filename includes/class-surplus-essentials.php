<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wordpress.org/plugins/surplus-essentials/
 * @since      1.0.0
 *
 * @package    surplus_essentials
 * @subpackage surplus_essentials/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    surplus_essentials
 * @subpackage surplus_essentials/includes
 * @author     surplusthemes <info@surplusthemes.com>
 */
class Surplus_Essentials {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Surplus_Essentials_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'STEP_VERSION' ) ) {
			$this->version = STEP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'surplus-essentials';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Surplus_Essentials_Loader. Orchestrates the hooks of the plugin.
	 * - Surplus_Essentials_i18n. Defines internationalization functionality.
	 * - Surplus_Essentials_Admin. Defines all hooks for the admin area.
	 * - Surplus_Essentials_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-surplus-essentials-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-surplus-essentials-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-surplus-essentials-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-surplus-essentials-public.php';

		/*
		 * All the required functions.
		 */
		require_once STEP_BASE_PATH . '/includes/class-surplus-essentials-functions.php';
		
		/**
		 * Popular Post Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-popular-post.php';

		/**
		 * Recent Post Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-recent-post.php';

		/**
		 * Custom Categories Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-custom-categories.php';

		/**
		 * Image Text Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-image-text.php';

		
		/**
		 * Posts Category Twitter Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-twitter-feeds.php';

		/**
		 * Facebook Page Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-facebook-page.php';

		/**
		 * Advertisement Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-advertisement.php';

		/**
		 * Social Media Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-socialmedia.php';

		/**
		 * Logo Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-client-logo.php';

		/**
		 * Featured page Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-featured-page.php';

		/**
		 * Call to action Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-cta.php';

		/**
		 * Testimonial Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-testimonial.php';

		/**
		 * Stat counter Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-stat-counter.php';

		/**
		 * Team member Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-team-member.php';

		/**
		 * Icon text Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-icon-text.php';

		/**
		 * Contact Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-contact.php';

		/**
		 * Event Widget.
		 */
		require_once STEP_BASE_PATH . '/includes/widgets/widget-event.php';

		/**
		 * The class responsible for defining all actions that occur in setting
		 * side.
		 */
		require_once STEP_BASE_PATH . '/includes/class-surplus-essentials-settings.php';
		
		/**
		 * The class responsible for creating blocks for guttenberg.
		 */
		// require_once STEP_BASE_PATH . '/includes/blocks/class-surplus-essentials-blocks.php';


		$this->loader = new Surplus_Essentials_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Surplus_Essentials_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Surplus_Essentials_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Surplus_Essentials_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'ste_icon_list_enqueue' );
	    $options = get_option( 'surplus_essentials_settings', true );
        if( isset($options) && $options!='' && is_array($options))
        {
          	$size = sizeof($options['posttype_name']);
          	for ($i=0 ; $i < $size ; $i++) {
          		if(isset($options['taxonomy_slug'][$i]) && $options['taxonomy_slug'][$i]!='')
          		{
          			$this->loader->add_action( $options['taxonomy_slug'][$i].'_add_form_fields', $plugin_admin, 'ste_add_category_image' );
				    $this->loader->add_action( 'created_'.$options['taxonomy_slug'][$i], $plugin_admin, 'ste_save_category_image' );
				    $this->loader->add_action( $options['taxonomy_slug'][$i].'_edit_form_fields', $plugin_admin, 'ste_update_category_image' );
				    $this->loader->add_action( 'edited_'.$options['taxonomy_slug'][$i], $plugin_admin, 'ste_updated_category_image' );
				    if( isset($_GET['taxonomy']) && $_GET['taxonomy'] == $options['taxonomy_slug'][$i] )
				    {
				    	$this->loader->add_action( 'admin_footer', $plugin_admin, 'ste_add_script' );
					}
					$this->loader->add_filter( 'manage_edit-'.$options['taxonomy_slug'][$i].'_columns', $plugin_admin, 'ste_custom_column_header', 10);
					$this->loader->add_action( 'manage_'.$options['taxonomy_slug'][$i].'_custom_column', $plugin_admin, 'ste_custom_column_content', 10, 3);
          		}
          	}
        }
        else{
        	$plugin_admin = new Surplus_Essentials_Admin('abc','1.0.0');
        	$myarray = $plugin_admin->ste_get_posttype_array();
			foreach ($myarray as $key => $value) {
				if(isset($value['taxonomy_slug']))
				{
          			$this->loader->add_action( $value['taxonomy_slug'].'_add_form_fields', $plugin_admin, 'ste_add_category_image' );
				    $this->loader->add_action( 'created_'.$value['taxonomy_slug'], $plugin_admin, 'ste_save_category_image' );
				    $this->loader->add_action( $value['taxonomy_slug'].'_edit_form_fields', $plugin_admin, 'ste_update_category_image' );
				    $this->loader->add_action( 'edited_'.$value['taxonomy_slug'], $plugin_admin, 'ste_updated_category_image' );
				    if( isset($_GET['taxonomy']) && $_GET['taxonomy'] == $value['taxonomy_slug'] )
				    {
				    	$this->loader->add_action( 'admin_footer', $plugin_admin, 'ste_add_script' );
					}
					$this->loader->add_filter( 'manage_edit-'.$value['taxonomy_slug'].'_columns', $plugin_admin, 'ste_custom_column_header', 10);
					$this->loader->add_action( 'manage_'.$value['taxonomy_slug'].'_custom_column', $plugin_admin, 'ste_custom_column_content', 10, 3);
				}
			}
		}
	    ////////////////
		$this->loader->add_action( 'category_add_form_fields', $plugin_admin, 'ste_add_category_image' );
	    $this->loader->add_action( 'created_category', $plugin_admin, 'ste_save_category_image' );
	    $this->loader->add_action( 'category_edit_form_fields', $plugin_admin, 'ste_update_category_image' );
	    $this->loader->add_action( 'edited_category', $plugin_admin, 'ste_updated_category_image' );
	    if( isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'category' )
	    {
	    	$this->loader->add_action( 'admin_footer', $plugin_admin, 'ste_add_script' );
		}
		$this->loader->add_filter( 'manage_edit-category_columns', $plugin_admin, 'ste_custom_column_header', 10);
		$this->loader->add_action( 'manage_category_custom_column', $plugin_admin, 'ste_custom_column_content', 10, 3);

		$this->loader->add_action( 'admin_print_footer_scripts',  $plugin_admin,'ste_client_logo_template' );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'ste_faq_template' );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'ste_date_picker_enqueue' );
		$this->loader->add_action( 'init',  $plugin_admin, 'ste_register_post_types' );
		$this->loader->add_action( 'init',  $plugin_admin,'ste_create_post_type_taxonomies', 0 );	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Surplus_Essentials_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Surplus_Essentials_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
