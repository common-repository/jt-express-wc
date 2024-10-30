<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    JT_Woocommerce
 * @subpackage JT_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    JT_Woocommerce
 * @subpackage JT_Woocommerce/includes
 * @author     Your Name <email@example.com>
 */
class JTWC_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	    $order_prefix = get_option('order-prefix', 0);
	    if($order_prefix == 0) {
            add_option('order-prefix', 'WC-'.mt_rand().'-');
        }
        self::create_service_mapping_table();
	}

    /**
     * Create service mapping.
     */
    public static function create_service_mapping_table()
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        global $wpdb;

        $wpdb->hide_errors();

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}jt_service_mapping (
				shipping_method_id INT NOT NULL,
				service_code VARCHAR (100) NOT NULL,
                is_enabled TINYINT(1) NOT NULL,
                created_at datetime NOT NULL,
				PRIMARY KEY  (shipping_method_id)
				) $charset_collate;";

        dbDelta( $sql );

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}jt_delivery_order (
				order_id INT NOT NULL,
				tracking_id VARCHAR (100) NOT NULL,
                is_deleted TINYINT(1) NOT NULL,
                tracking_status VARCHAR (100) NULL,
                updated_at datetime NOT NULL, 
                created_at datetime NOT NULL,
				PRIMARY KEY  (order_id)
				) $charset_collate;";

        dbDelta( $sql );

        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}jt_return_order (
				order_id        int auto_increment primary key,
                ref_id          varchar(100)  not null,
                tracking_id     varchar(100)  not null,
                json_data       varchar(2000) null,
                is_deleted      tinyint(1)    not null,
                tracking_status varchar(100)  null,
                updated_at      datetime      not null,
                created_at      datetime      not null
				) $charset_collate;";

        dbDelta( $sql );

    }
}
