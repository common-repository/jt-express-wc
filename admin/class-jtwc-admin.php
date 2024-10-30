<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    JT_woocommerce
 * @subpackage JT_woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    JT_woocommerce
 * @subpackage JT_woocommerce/admin
 * @author     Your Name <email@example.com>
 */
class JTWC_Admin {
    protected static $_instance = null;
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
	 * @param      string    $jt_woocommerce       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $jt_woocommerce, $version ) {

		$this->jt_woocommerce = $jt_woocommerce;
		$this->version = $version;
        $cred = get_option('jt-cred');
        if(isset($cred)) {
            JTWC_API::constructInstance($cred);
        }
	}

    public static function instance()
    {
        if (!empty(static::$_instance)) {
            return static::$_instance;
        }
        $env = jt_environment_variables();
        static::$_instance = new JTWC_Admin('jt_woocomerce', $env->version);
        return static::$_instance;
    }


    public static function connect()
    {
        return static::instance();
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
		 * defined in JT_woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The JT_woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		 if(isset($_GET['page']) && sanitize_text_field($_GET['page']) == "jt-woocommerce"){
			 wp_enqueue_style( $this->jt_woocommerce . 'vendors',  plugin_dir_url( __FILE__ ) . 'css/vendors.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'bootstrap',  plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'bootstrap-extended',  plugin_dir_url( __FILE__ ) . 'css/bootstrap-extended.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'colors',  plugin_dir_url( __FILE__ ) . 'css/colors.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'components',  plugin_dir_url( __FILE__ ) . 'css/components.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'bordered-layout',  plugin_dir_url( __FILE__ ) . 'css/bordered-layout.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'semi-dark-layout',  plugin_dir_url( __FILE__ ) . 'css/semi-dark-layout.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'vertical-menu',  plugin_dir_url( __FILE__ ) . 'css/vertical-menu.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce . 'font-monter',  'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600', array(), $this->version, 'all' );
			 wp_enqueue_style($this->jt_woocommerce . 'form-validation', plugin_dir_url( __FILE__ ) . 'css/auth/form-validation.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style($this->jt_woocommerce . 'page-auth', plugin_dir_url( __FILE__ ) . 'css/auth/page-auth.min.css', array(), $this->version, 'all' );
			 wp_enqueue_style( $this->jt_woocommerce, plugin_dir_url( __FILE__ ) . 'css/styles.css', array(), $this->version, 'all' );

			 if(isset($_GET['tab'])) {
				 if( sanitize_text_field($_GET['tab']) == "orders"){
					wp_enqueue_style($this->jt_woocommerce . 'form-wizard', plugin_dir_url( __FILE__ ) . 'css/form-wizard.min.css', array(), $this->version, 'all' );
					wp_enqueue_style($this->jt_woocommerce . 'bs-stepper', plugin_dir_url( __FILE__ ) . 'css/bs-stepper.min.css', array(), $this->version, 'all' );
					wp_enqueue_style($this->jt_woocommerce . 'flatpickr', plugin_dir_url( __FILE__ ) . 'css/flatpickr.min.css', array(), $this->version, 'all' );
					wp_enqueue_style($this->jt_woocommerce . 'form-flat-pickr', plugin_dir_url( __FILE__ ) . 'css/form-flat-pickr.min.css', array(), $this->version, 'all' );
				 }
			 }
		 }
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
		 * defined in JT_woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The JT_woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(isset($_GET['page']) && sanitize_text_field($_GET['page']) == "jt-woocommerce"){
			wp_enqueue_script( $this->jt_woocommerce, plugin_dir_url( __FILE__ ) . 'js/jt-woocommerce-admin.js', array('jquery'), $this->version, 'all' );
			wp_enqueue_script($this->jt_woocommerce . 'jquery-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array(), $this->version, 'all' );
			wp_enqueue_script($this->jt_woocommerce . 'feather-icons', plugin_dir_url( __FILE__ ) . 'js/feather-icons.min.js', array(), $this->version, 'all' );
			wp_enqueue_script($this->jt_woocommerce . 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array(), $this->version, 'all' );
			wp_enqueue_script($this->jt_woocommerce . 'flatpickr', plugin_dir_url( __FILE__ ) . 'js/flatpickr.min.js', array(), $this->version, 'all' );

			if(isset($_GET['tab'])){
				if(isset($_GET['tab']) == 'login') {
					wp_enqueue_script($this->jt_woocommerce . 'auth-page', plugin_dir_url( __FILE__ ) . 'js/login.js', array(), $this->version, 'all' );	
				}
				if(isset($_GET['tab']) == 'service-mapping') {
					wp_enqueue_script($this->jt_woocommerce . 'service-mapping', plugin_dir_url( __FILE__ ) . 'js/service-mapping.js', array(), $this->version, 'all' );	
				}
				if(isset($_GET['tab']) == 'orders') {
					wp_enqueue_script($this->jt_woocommerce . 'orders-page', plugin_dir_url( __FILE__ ) . 'js/orders.js', array(), $this->version, 'all' );
					if(isset($_GET['sub_tab']) && in_array(sanitize_text_field($_GET['sub_tab']), array("return-orders", "create-return-order"))) {
						wp_enqueue_script($this->jt_woocommerce . 'bs-stepper', plugin_dir_url( __FILE__ ) . 'js/bs-stepper.min.js', array(), $this->version, 'all' );
						wp_enqueue_script($this->jt_woocommerce . 'return-order', plugin_dir_url( __FILE__ ) . 'js/return-order.js', array(), $this->version, 'all' );
					}
				}
			}
		}
	}
    public function add_plugin_admin_menu() {
        // Add woocommerce menu subitem
        add_submenu_page(
            'woocommerce',
            __( 'J&T for WooCommerce', 'jt-for-woocommerce'),
            __( 'J&T Express', 'jt-for-woocommerce' ),
            jt_get_allowed_capability(),
            'jt-woocommerce',
            array($this, 'display_plugin_setup_page')
        );
    }

    public function display_plugin_setup_page() {
        include_once( 'partials/jtwc-admin-display.php' );
    }

}
