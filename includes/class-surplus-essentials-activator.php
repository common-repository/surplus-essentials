<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wordpress.org/plugins/surplus-essentials/
 * @since      1.0.0
 *
 * @package    surplus_essentials
 * @subpackage surplus_essentials/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    surplus_essentials
 * @subpackage surplus_essentials/includes
 * @author     surplusthemes <info@surplusthemes.com>
 */
class Surplus_Essentials_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$plugin_admin = new Surplus_Essentials_Admin('abc','1.0.0');
        $arr = $plugin_admin->ste_get_posttype_array();
  		foreach ($arr as $key => $value) {
  			$new_arr['posttype_label'][] = $value['posttype_label'];
  			$new_arr['posttype_name'][] = $value['posttype_name'];
  			$new_arr['posttype_slug'][] = $value['posttype_slug'];
  			$new_arr['posttype_icon'][] = $value['posttype_icon'];
  			$new_arr['taxonomy_slug'][] = $value['taxonomy_slug'];
  		}
        $options = get_option( 'surplus_essentials_settings_history', true );                
		if( !isset( $options ) || $options=='' || !is_array( $options ) )
		{
			update_option( 'surplus_essentials_settings_history', $new_arr );
		}
	}
}
