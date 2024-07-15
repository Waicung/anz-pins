<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://willai.com.au
 * @since      1.0.0
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/public
 * @author     Will <will@willai.com.au>
 */
class Anz_Pins_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/anz-pins-public.css', array(), $this->version, 'all');

		// register https://unpkg.com/leaflet/dist/leaflet.css
		wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet/dist/leaflet.css', array(), null, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/anz-pins-public.js', array('jquery', 'leaflet-js'), $this->version, true);

		// register https://unpkg.com/leaflet/dist/leaflet.js
		wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet/dist/leaflet.js', array(), null, false);
	}

	/**
	 * Register the shortcode for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode()
	{
		add_shortcode('anz_pins', array($this, 'anz_pins_shortcode'));
	}

	/**
	 * Callback function for the anz_pins shortcode.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string            Shortcode output.
	 */
	public function anz_pins_shortcode($atts)
	{
		// Add your shortcode logic here
		return 'Anz Pins Shortcode Output';
	}

	public function anz_pins_shortcode_handler($atts = [], $content = null)
	{

		// get content from partils/anz-pins-public-display.php
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/anz-pins-public-display.php';
		$output = ob_get_clean();
		return $output;
	}
}
