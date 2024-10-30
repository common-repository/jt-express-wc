<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class JTWC_Delivery_Order_List_Table extends WP_List_Table
{
    protected $data = [];
    protected $order_prefix;

    public function __construct()
    {
        // Set parent defaults.
        parent::__construct(array(
            'singular' => 'order',     // Singular name of the listed records.
            'plural' => 'orders',    // Plural name of the listed records.
            'ajax' => true,       // Does this table support ajax?
        ));
        $this->order_prefix = get_option('order-prefix');
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => _x('Ref.ID', 'Column label', 'wp-list-table-example'),
            'full_name' => _x('Contact', 'Column label', 'wp-list-table-example'),
            'tracking_id' => _x('Tracking ID', 'Column label', 'wp-list-table-example'),
            'tracking_status' => _x('Tracking status', 'Column label', 'wp-list-table-example'),
            'skus' => _x('Items', 'Column label', 'wp-list-table-example'),
            'total' => _x('Amount', 'Column label', 'wp-list-table-example'),
            'payment_method' => _x('Payment method', 'Column label', 'wp-list-table-example'),
            'consignee_details' => _x('Consignee details', 'Column label', 'wp-list-table-example')
        );

        return $columns;
    }


    protected function get_sortable_columns()
    {
        $sortable_columns = array(
            // 'id' => array( 'id', false ),
            // 'tracking_id' => array( 'tracking_id', false ),
        );

        return $sortable_columns;
    }


    protected function get_views()
    {
        $cate = 'no_pickup';
        if (isset($_GET['cate'])) {
            $cate = sanitize_text_field($_GET['cate']);
        }
        $tab = sanitize_text_field($_GET['tab']);
        $sub_tab = sanitize_text_field($_GET['sub_tab']);

        $status_links = array(
            "no_pickup" => __("<a class='clazz' href='admin.php?page=jt-woocommerce&tab=$tab&sub_tab=$sub_tab&cate=no_pickup'>No Pickup Schedule <span class='count'></span></a>", 'my-plugin-slug'),
            "pending_pickup" => __("<a class='clazz' href='admin.php?page=jt-woocommerce&tab=$tab&sub_tab=$sub_tab&cate=pending_pickup'>Pending Pickup <span class='count'></span></a>", 'my-plugin-slug'),
            "arrived_at_hub" => __("<a class='clazz' href='admin.php?page=jt-woocommerce&tab=$tab&sub_tab=$sub_tab&cate=arrived_at_hub'>Arrived at Hub <span class='count'></span></a>", 'my-plugin-slug'),
            "failed_delivery" => __("<a class='clazz' href='admin.php?page=jt-woocommerce&tab=$tab&sub_tab=$sub_tab&cate=failed_delivery'>Delivery Failed <span class='count'></span></a>", 'my-plugin-slug'),
            "others" => __("<a class='clazz' href='admin.php?page=jt-woocommerce&tab=$tab&sub_tab=$sub_tab&cate=others'>Others <span class='count'></span></a>", 'my-plugin-slug'),
        );
        foreach ($status_links as $key => $val) {
            if ($key == $cate) {
                $status_links[$key] = str_replace('clazz', 'current', $status_links[$key]);
                break;
            }
        }
        return $status_links;
    }

    protected function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'tracking_id':
                return $item[$column_name];
            // case 'full_name': return $item[ 'first_name' ].' '. $item['last_name'];
            case 'full_name':
                return "<div class='mb-75'><i data-feather='user' class='mr-50'></i>"
                    . $item['first_name'] . ' ' . $item['last_name'] . '</div>'
                    . "<div><i data-feather='phone' class='mr-50'></i>" . $item['phone'] . '</div>';
            case 'title':
                return $this->order_prefix . $item['id'];
            case 'consignee_details':
                return $item['first_name'] . ' ' . $item['last_name']
                    . '<br/>' . $item['address_1'] . '<br/>' . $item['address_2']
                    . '<br/>' . $item['city'] . ' ' . $item['state']
                    . ',' . $item['postcode'] . ' ' . $item['country'];
            case 'total':
                return wc_price($item['total']);
            case 'tracking_status':
                return str_replace('_', ' ', $item[$column_name]);
            case 'country':
            case 'first_name':
            case 'last_name':
            case 'address_1':
            case 'address_2':
            case 'city':
            case 'postcode':
            case 'state':
            case 'shipping_method':
            case 'weight':
            case 'payment_method':
            case 'skus':
            case 'service_code':
            case 'tracking_id':
            case 'delivery_instruction':
                return $item[$column_name];
            default:
                return print_r($item, true); // Show the whole array for troubleshooting purposes.
        }
    }

    protected function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],  // Let's simply repurpose the table's singular label ("order").
            $item['id']                // The value of the checkbox should be the record's ID.
        );
    }


    protected function column_title($item)
    {
        $page = sanitize_text_field($_REQUEST['page']);
        // Build print row action.
        $edit_query_args = array(
            'page' => $page,
            'action' => 'print',
            'order' => $item['id'],
        );

        if ($item['cancelable'] == 1) {
            $actions['cancel'] = sprintf(
                '<a href="#ex2" action="cancel" uid="%3$s">%2$s
			</a>',
                esc_url(wp_nonce_url(add_query_arg($edit_query_args, 'admin.php'), 'print_order_' . $item['id'])),
                _x('Cancel', 'List table row action', 'wp-list-table-example'),
                $item['tracking_id']
            );
        }

        $actions['print'] = sprintf(
            '<a href="#ex1">%2$s
				<div>
					<a action="label-print" uid="%3$s" format="a6">A6/Sticker</a>
					<a action="label-print" uid="%3$s" format="a4">A4 Paper</a>
				</div>
			</a>',
            esc_url(wp_nonce_url(add_query_arg($edit_query_args, 'admin.php'), 'print_order_' . $item['id'])),
            _x('Print', 'List table row action', 'wp-list-table-example'),
            $item['tracking_id']
        );

        // Return the title contents.
        return sprintf('%1$s %2$s',
            // $item['title'],
            $this->order_prefix . $item['id'],
            $this->row_actions($actions)
        );
    }


    protected function get_bulk_actions()
    {

        $actions = array(
            //'schedule_pickup' => _x( 'Schedule Pickup', 'List table bulk action', 'wp-list-table-example' ),
            'print' => _x('Print', 'List table bulk action', 'wp-list-table-example'),
        );

        $sub_tab = sanitize_text_field($_GET['sub_tab']);

        if ($sub_tab === "international" && (isset($_GET['cate']) && sanitize_text_field($_GET['cate']) === "pending_pickup")) {
            array_splice($actions, 0, 1);
        }

        return $actions;
    }

    protected function process_bulk_action()
    {
        // Detect when a bulk action is being triggered.
        if ('schedule_pickup' === $this->current_action()) {
            wp_die('Items schedule pickup!');
        }
    }


    public function search_box($text, $input_id)
    {
        if (empty($_REQUEST['s']) && !$this->has_items()) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if (!empty($_REQUEST['orderby'])) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
        }
        if (!empty($_REQUEST['order'])) {
            echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
        }
        if (!empty($_REQUEST['post_mime_type'])) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr($_REQUEST['post_mime_type']) . '" />';
        }
        if (!empty($_REQUEST['detached'])) {
            echo '<input type="hidden" name="detached" value="' . esc_attr($_REQUEST['detached']) . '" />';
        }
        ?>
        <div class="m-0 float-right d-flex">
            <div class="input-group input-group-merge" style="margin-right: 8px">
                <div class="input-group-prepend">
            <span class="input-group-text">
                <i data-feather='search'></i>
            </span>
                </div>
                <input type="search" id="<?php echo esc_attr($input_id); ?>" class="form-control" name="s"
                       value="<?php _admin_search_query(); ?>" placeholder="Search order"/>
            </div>
            <button type="submmit" class="btn btn-primary" id="search-submit">Search</button>
        </div>

        <?php
    }

    function prepare_items()
    {
        $data = $this->data;
        $per_page = 100;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        //usort($data, array($this, 'usort_reorder'));
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;

        $this->set_pagination_args(array(
            'total_items' => $total_items,                     // WE have to calculate the total number of items.
            'per_page' => $per_page,                        // WE have to determine how many items to show on a page.
            'total_pages' => ceil($total_items / $per_page), // WE have to calculate the total number of pages.
        ));
    }

    protected function usort_reorder($a, $b)
    {
        // If no sort, default to title.
        $orderby = !empty($_REQUEST['orderby']) ? sanitize_text_field($_REQUEST['orderby']) : 'id'; // WPCS: Input var ok.

        // If no order, default to asc.
        $order = !empty($_REQUEST['order']) ? sanitize_text_field($_REQUEST['order']) : 'desc'; // WPCS: Input var ok.

        // Determine sort order.
        $result = strcmp($a[$orderby], $b[$orderby]);
        return ('asc' === $order) ? $result : -$result;
    }
}