<?php
/**
 * Plugin Name: ShortCode DropDown
 * Description: ShortCode DropDown is an invaluable tool which enables authors to select the site's shortcodes from a dropdown box in WordPress's richtext editor.
 * Version: 1.0
 * Author: WPShowCase
 * Author URI: http://codecanyon.net/user/WPShowCase/portfolio?ref=WPShowCase
 *
 * ShortCode DropDown is an invaluable tool that allows shortcodes to be selected from a dropdown menu
 * which means that authors can see the shortcodes that are available and that do not need to
 * memorize the names of shortcodes.
 *
 * @package ShortCodeDropDown
 * @version 1.0
 * @author WPShowCase <info@wpshowcase.net>
 * @copyright Copyright (c) 2013, WPShowCase
 * @license See license.txt
 */
class ShortCodeDropDown {
	
	/**
	 * Add actions to load javascript/css in the constructor.
	 */
	function ShortCodeDropDown() {
		add_action('admin_enqueue_scripts', array($this, 'shortcode_dropdown_load_css') );
		add_action('admin_init', array( $this, 'add_dropdown' ) );
		add_action('admin_footer', array($this, 'get_shortcodes'));
	}
	
	//Adds a css file
	function shortcode_dropdown_load_css() {
			wp_register_style( 'shortcode-dropdown.css', WP_PLUGIN_URL . '/shortcode-dropdown/shortcode-dropdown.css' );
			wp_enqueue_style( 'shortcode-dropdown.css');
	}
	
	/**
	 * Adds the dropdown in php and the javascript plugin which makes the dropdown work.
	 * Only rich text editor supports dropdowns!
	 */
	function add_dropdown() {
		if((current_user_can('edit_posts') || current_user_can('edit_pages')) && get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_buttons', array( $this, 'add_dropdowntobuttons' ) );
			add_filter( 'mce_external_plugins', array( $this, 'load_js_plugin' ) );
		}
	}
	
	/**
	 * adds a separator between the previous button and this button then adds this button.
	 */
	function add_dropdowntobuttons( $buttons ) {
		array_push( $buttons, 'separator', 'shortcodedropdown' );
		return $buttons;
	}
	
	/**
	 * Loads the plugin (which does the selecting).
	 */
	function load_js_plugin( $plugin_array ) {
		$plugin_array['shortcodedropdown'] = WP_PLUGIN_URL . '/shortcode-dropdown/shortcode-dropdown.js';
		return $plugin_array;
	}

	/**
	* Gets all the site's shortcodes into javascript for the plugin to read.
	*/
	 function get_shortcodes() {
		global $shortcode_tags;
		$shortcodes = '';
		print '<script type="text/javascript">
		var shortcode_options_for_dropdown = new Array();
		';
		$cnt = 0;
		foreach($shortcode_tags as $tag => $function) {
			print "shortcode_options_for_dropdown[{$cnt}] = '{$tag}';
		";
			$cnt++;
		}
		print '
	</script>';
	}		
}

//Create the ShortCodeDropDown object.
global $ShortCodeDropDown;
$ShortCodeDropDown = new ShortCodeDropDown();


?>