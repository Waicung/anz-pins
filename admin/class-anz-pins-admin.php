<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://willai.com.au
 * @since      1.0.0
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/admin
 * @author     Will <will@willai.com.au>
 */
class Anz_Pins_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Anz_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Anz_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/anz-pins-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Anz_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Anz_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/anz-pins-admin.js', array('jquery'), $this->version, false);
	}

	public function add_admin_menu()
	{
		// Add a new top-level menu (settings page)
		add_submenu_page(
			'tools.php', // Parent slug
			'ANZ Pins', // Page title
			'ANZ Pins', // Menu title
			'manage_options', // Capability required to see this menu
			'anz-pins-settings', // Menu slug
			array($this, 'display_settings_page') // Function to display the settings page
		);
	}

	public function display_settings_page()
	{
		// Check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}

		// Display the settings page content
		echo '<div class="wrap">';
		echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
		echo '<form action="options.php" method="post">';
		settings_fields('anz_pins_options');
		do_settings_sections('anz-pins-settings');
		submit_button('Save Settings');
		echo '</form>';
		echo '</div>';
	}
}
