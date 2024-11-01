<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wordpress.org/plugins/surplus-essentials/
 * @since      1.0.0
 *
 * @package    surplus_essentials
 * @subpackage surplus_essentials/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    surplus_essentials
 * @subpackage surplus_essentials/includes
 * @author     surplusthemes <info@surplusthemes.com>
 */
class Surplus_Essentials_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'surplus-essentials',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
