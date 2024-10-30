<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    JT_Woocommerce
 * @subpackage JT_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    JT_Woocommerce
 * @subpackage JT_Woocommerce/public
 * @author     Your Name <email@example.com>
 */
class JTWC_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $jt_woocommerce    The ID of this plugin.
	 */
	private $jt_woocommerce;

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
	 * @param      string    $jt_woocommerce       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $jt_woocommerce, $version ) {

		$this->jt_woocommerce = $jt_woocommerce;
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
		 * defined in JT_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The JT_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->jt_woocommerce, plugin_dir_url( __FILE__ ) . 'css/jt-woocommerce-public.css', array(), $this->version, 'all' );

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
		 * defined in JT_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The JT_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->jt_woocommerce, plugin_dir_url( __FILE__ ) . 'js/jt-woocommerce-public.js', array( 'jquery' ), $this->version, false );

	}

}
