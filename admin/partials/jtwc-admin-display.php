<?php
$originUrl = '?page=jt-woocommerce&tab=';
const INTER_ORDERS = "international";
const ORDERS = "orders";
const DOMESTIC_ORDERS = "domestic";
const RETURN_ORDERS = "return-orders";
const CREATE_RETURN_ORDER = "create-return-order";
const SERVICE_MAPPING = "service-mapping";
const SUB_TAB_URL = "&sub_tab=";
const NO_RELOAD_STATUSES = array('DELIVERED', 'RETURNED_TO_SENDER', 'CANCELLED');
const CANCELABLE_STATUSES = array('PENDING_PICKUP', 'ASSIGNED_PICKUP', 'ACCEPTED_PICKUP', 'FAILED_PICKUP');
$order_prefix = get_option('order-prefix');;
$orderTabs = array(INTER_ORDERS, DOMESTIC_ORDERS, RETURN_ORDERS);
global $wpdb;
function loadOrder($shipping_methods, $ids = null, $sub_tab = null)
{
    if ($ids != null) {
        $orders = array();
        foreach ($ids as $id) {
            $orders[] = wc_get_order($id);
        }
    } else {
        $orders = wc_get_orders(array('numberposts' => -1, 'status' => 'wc-processing'));
    }

    $jtOrders = array();
    foreach ($orders as $order) {
        if (count($order->get_shipping_methods()) == 0) {
            continue;
        }
        $instance_id = array_values($order->get_shipping_methods())[0]->get_instance_id();
        if (!isset($shipping_methods[$instance_id])) {
            continue;
        }
        $shipping_method = $shipping_methods[$instance_id];
        if ($shipping_method['enable'] != 1 || $shipping_method['service_code'] == 'none') {
            continue;
        }
        $is_international = strtoupper($order->get_shipping_country()) != 'SG';
        $is_international_tab = $sub_tab == INTER_ORDERS;
        if ($is_international != $is_international_tab && $ids == null) {
            continue;
        }
        $jtOrders[$order->get_id()]['id'] = $order->get_id();
        $jtOrders[$order->get_id()]['country'] = $order->get_shipping_country();
        $jtOrders[$order->get_id()]['is_international'] = strtoupper($order->get_shipping_country()) != 'SG';
        $jtOrders[$order->get_id()]['first_name'] = $order->get_shipping_first_name();
        $jtOrders[$order->get_id()]['last_name'] = $order->get_shipping_last_name();
        $jtOrders[$order->get_id()]['phone'] = $order->get_billing_phone();
        $jtOrders[$order->get_id()]['address_1'] = $order->get_shipping_address_1();
        $jtOrders[$order->get_id()]['address_2'] = $order->get_shipping_address_2();
        $jtOrders[$order->get_id()]['city'] = $order->get_shipping_city();
        $jtOrders[$order->get_id()]['postcode'] = $order->get_shipping_postcode();
        $jtOrders[$order->get_id()]['state'] = $order->get_shipping_state();
        $jtOrders[$order->get_id()]['shipping_method'] = $order->get_shipping_method();
        $jtOrders[$order->get_id()]['total'] = $order->get_total();
        $jtOrders[$order->get_id()]['currency'] = $order->get_currency();
        $jtOrders[$order->get_id()]['payment_method'] = $order->get_payment_method();
        $jtOrders[$order->get_id()]['service_code'] = $shipping_method['service_code'];
        //print_r(array_values($order->get_shipping_methods())[0]->get_instance_id());
        $skus = array();
        $weight = 0;
        $items = $order->get_items();
        foreach ($items as $item) {
            $product = wc_get_product($item->get_product_id());
			if(isset($product) && $product != null){
                $skus[] = $product->get_sku();
                $weight += $item->get_quantity() * $product->get_weight();
            }
        }
        $jtOrders[$order->get_id()]['weight'] = $weight;
        $jtOrders[$order->get_id()]['skus'] = implode(',', $skus);
    }
    return $jtOrders;
}

function loadReturnOrder($wpdb)
{
    $orders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jt_return_order WHERE is_deleted = 0");
    $jtOrders = array();
    foreach ($orders as $order) {
        $jtOrder = array();
        $jtOrder['id'] = $order->order_id;
        $jtOrder['ref'] = $order->ref_id;
        $jtOrder['tracking_id'] = $order->tracking_id;
        $jtOrder['created_at'] = $order->created_at;
        $jtOrder['updated_at'] = $order->updated_at;
        $jtOrder['tracking_status'] = $order->tracking_status;
        $data = json_decode($order->json_data, true);
        $jtOrder['service_code'] = $data['service_code'];
        $jtOrder['service_code'] = $data['service_code'];
        $jtOrder['pickup_details'] = $data['pickup_details'];
        $jtOrder['consignee_details'] = $data['consignee_details'];
        $jtOrder['item_details'] = $data['item_details'];

        $jtOrders[$order->order_id] = $jtOrder;
    }
    return $jtOrders;
}

const CATE = array('PENDING_PICKUP', 'ARRIVED_AT_HUB', 'FAILED_DELIVERY');
function classifyStatus($status)
{
    if (in_array($status, CATE)) {
        return strtolower($status);
    }
    return 'others';
}


$handler = JTWC_Admin::connect();

$has_valid_api_key = false;
$show_wizard = true;
$action = null;
$cate = 'no_pickup';
if (isset($_GET['action'])) {
    $action = sanitize_text_field($_GET['action']);
}
if (isset($_GET['cate'])) {
    $cate = sanitize_text_field($_GET['cate']);
}
// Grab all options for this particular tab we're viewing.
$options = get_option($this->jt_woocommerce, array());

$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : (isset($options['active_tab']) ? $options['active_tab'] : 'api_key');
$sub_tab = isset($_GET['sub_tab']) ? sanitize_text_field($_GET['sub_tab']) : DOMESTIC_ORDERS;
$jt_configured = false;


if (isset($_GET['save_account'])) {
    $cred = $_POST['cred'];
    add_option('jt-cred', $cred);
    return;
}


$cred = get_option('jt-cred');
if (isset($cred) && $cred != null) {
    $show_wizard = false;
    $has_valid_api_key = true;
    JTWC_API::constructInstance($cred);
    $api = JTWC_API::getInstance();
    $token = $api->login();
    if($token == null) {
        delete_option('jt-cred');
    }
    $jt_services = $api->getServices();

    if ($active_tab == 'service-mapping') {
        $delivery_zones = WC_Shipping_Zones::get_zones();
        $shipping_methods = array();
        foreach ((array)$delivery_zones as $key => $the_zone) {
            //$shipping_methods[]
            //print_r($the_zone['zone_id']);
            //print_r($the_zone['formatted_zone_location']);
            $shippings = $the_zone['shipping_methods'];
            foreach ($shippings as $shipping) {
                $shipping_methods[$shipping->instance_id] = array();
                $shipping_methods[$shipping->instance_id]['instance_id'] = $shipping->instance_id;
                $shipping_methods[$shipping->instance_id]['method_title'] = $shipping->method_title;
                $shipping_methods[$shipping->instance_id]['zone_id'] = $the_zone['zone_id'];
                $shipping_methods[$shipping->instance_id]['formatted_zone_location'] = $the_zone['formatted_zone_location'];;
                $shipping_methods[$shipping->instance_id]['enable'] = 0;
                $shipping_methods[$shipping->instance_id]['service_code'] = 'none';
            }
        }
        $service_maps = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jt_service_mapping");
        foreach ($service_maps as $service_map) {
            $instance_id = $service_map->shipping_method_id;
            if (isset($instance_id)) {
                $shipping_methods[$instance_id]['enable'] = $service_map->is_enabled;
                $shipping_methods[$instance_id]['created_at'] = $service_map->created_at;
                $shipping_methods[$instance_id]['service_code'] = $service_map->service_code;
            }
        }


        if ($action == 'save') {
            $json = file_get_contents('php://input');
            $service_maps = json_decode($json);
            $data = array();
            foreach ($service_maps as $service_map) {
                $data['shipping_method_id'] = $service_map->shipping_method_id;
                $data['service_code'] = $service_map->service_code;
                $data['is_enabled'] = $service_map->is_enabled;
                $wpdb->delete("{$wpdb->prefix}jt_service_mapping", array('shipping_method_id' => $service_map->shipping_method_id));
                $wpdb->insert("{$wpdb->prefix}jt_service_mapping", $data, array('%d', '%s', '%d'));
            }
            return;
        }

        if ($action == 'resync') {
            $api->resyncServices();
        }
    }
}

$weight_unit = strtolower(get_option('woocommerce_weight_unit'));

if ($active_tab == ORDERS) {
    $service_maps = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jt_service_mapping");
    $shipping_methods = array();
    foreach ($service_maps as $service_map) {
        $instance_id = $service_map->shipping_method_id;
        if (isset($instance_id)) {
            $shipping_methods[$instance_id]['enable'] = $service_map->is_enabled;
            $shipping_methods[$instance_id]['created_at'] = $service_map->created_at;
            $shipping_methods[$instance_id]['service_code'] = $service_map->service_code;
        }
    }
    //print_r(loadOrder($shipping_methods,16));
    if ($action == 'create') {
        $json_request = file_get_contents('php://input');
        $request = json_decode($json_request);
        $length = $request->length;
        $width = $request->width;
        $height = $request->height;
        $weight = 0;
        $itemValue = $request->itemValue;
        if (isset($request->weight)) {
            $weight = $request->weight;
        }
        $time_window = $request->timeWindow;
        $date = $request->date;
        $pick_up_note = $request->pickUpNote;
        $pick_up_address = $request->address;
        $pick_up_contact_name = $request->contactName;
        $pick_up_email = $request->email;
        $pick_up_phone_number = $request->phoneNumber;
        $pick_up_post_code = $request->postCode;
        $order_ids = $request->orderId;
        $ids = explode(',', $order_ids);
        //print_r('ids');
        //print_r($ids);
        $jtOrders = loadOrder($shipping_methods, $ids);
        //print_r($jtOrders);

        if (count($jtOrders) > 0) {
            $datas = array();
            foreach ($ids as $order_id) {
                $jtOrder = $jtOrders[$order_id];
                print_r('jtOrder');
                print_r($jtOrder);
                $data = array();
                $data['pickup_details'] = array();
                $data['pickup_details']['contact_name'] = $pick_up_contact_name;
                $data['pickup_details']['phone_number'] = $pick_up_phone_number;
                $data['pickup_details']['email'] = $pick_up_email;
                $data['pickup_details']['address'] = $pick_up_address;
                $data['pickup_details']['postcode'] = $pick_up_post_code;
                $data['pickup_details']['date'] = $date;
                $data['pickup_details']['time_window'] = $time_window;
                $data['service_code'] = $jtOrder['service_code'];
                $data['reference_number'] = $order_prefix . $jtOrder['id'];
                $data['consignee_details'] = array();
                $data['consignee_details']['contact_name'] = $jtOrder['first_name'] . ' ' . $jtOrder['last_name'];
                $data['consignee_details']['phone_number'] = $jtOrder['phone'];
                $data['consignee_details']['address'] = $jtOrder['address_1'] . ' ' . $jtOrder['address_2'];
                //$data['consignee_details']['unit'] = '';
                $data['consignee_details']['postcode'] = $jtOrder['postcode'];
                $data['consignee_details']['country_code'] = $jtOrder['country'];
                $data['customs_declaration'] = array();
                $data['customs_declaration']['currency'] = "SGD";
                $data['customs_declaration']['amount'] = $itemValue;
                $item_details = array();
                $item_details['length'] = $length;
                $item_details['width'] = $width;
                $item_details['height'] = $height;
                $item_details['weight'] = $jtOrder['weight'];
                $item_details['weight_unit'] = $weight_unit;
                if ($weight > 0) {
                    $item_details['weight'] = $weight;
                }
                $item_details['quantity'] = 1;
                $item_details['description'] = $pick_up_note;
                $data['item_details'] = array();
                $data['item_details'][] = $item_details;
                if ($jtOrder['payment_method'] == 'cod') {
                    $cod = array();
                    $cod['amount'] = $jtOrder['total'];
                    $cod['currency'] = 'SGD';
                    $data['cod'] = $cod;
                }
                $datas[] = $data;
            }
                // print_r("@@@@@@@@@@");
                // print_r($datas);
            $createdOrders = $api->createOrder($datas);
            print_r($createdOrders);
            $fails = array();
            $lastError = '';
            foreach ($createdOrders as $ref => $createdOrder) {
                //print_r('ref');
                //print_r($ref);
                if ($createdOrder['success']) {
                    $row['order_id'] = str_replace($order_prefix, '', $ref);
                    $row['tracking_id'] = $createdOrder['tracking_id'];
                    $row['is_deleted'] = false;
                    $row['created_at'] = date("Y-m-d H:i:s");
                    $dbResult = $wpdb->insert("{$wpdb->prefix}jt_delivery_order", $row, array('%d', '%s', '%d', '%s'));
                } else {
                    $fails[$ref] = $createdOrder['error'];
                }
            }
            if (count($fails) == count($datas)) {
                //all failed ??
                print_r('ERROR:');
                wp_send_json_error(array('message' => array_values($fails)[0]), 400);
                die();
            }
            wp_send_json_success($createdOrders);
            die();
            //print_r($dbResult);
        }
        return;
    }
    if ($action == 'cancel' || $action == 'return-order-cancel') {
        $tbl = $action == 'cancel' ? 'jt_delivery_order' : 'jt_return_order';
        if (isset($_GET['id'])) {
            $tracking_id = sanitize_text_field($_GET['id']);
            $result = $api->cancelOrder(array($tracking_id));
            if ($result[$tracking_id]['success']) {
                $data = array('tracking_status' => 'CANCELLED', 'updated_at' => date("Y-m-d H:i:s"));
                $wpdb->update("{$wpdb->prefix}$tbl", $data, array('tracking_id' => $tracking_id));
            } else {
                wp_send_json_error(array('message' => 'Order can not be cancelled'), 400);
            }
            return;
        }
    }
    if ($action == 'create_return_order') {
        $json_request = file_get_contents('php://input');
        $request = json_decode($json_request);
        $ref = $request->ref;
        $itemDetails = $request->item_details;
        $pickupDetails = $request->pickup_details;
        $consigneeDetails = $request->consignee_details;
        if (isset($request->weight)) {
            $weight = $request->weight;
        }
        $time_window = $request->timeWindow;

        //prepare data
        $data = array();
        $data['reference_number'] = $ref;
        $data['service_code'] = 'RT';

        //pickup details
        $data['pickup_details'] = (array)$pickupDetails;
        $data['pickup_details']['time_window'] = $time_window;
        //consignee
        $data['consignee_details'] = (array)$consigneeDetails;

        // item details
        $itemDetails->quantity = 1;
        $itemDetailsArr = (array)$itemDetails;
        $itemDetailsArr['quantity'] = 1;
        $data['item_details'] = array($itemDetailsArr);

        $createdOrders = $api->createOrder(array($data));

        $fails = array();
        $lastError = '';
        foreach ($createdOrders as $ref_id => $createdOrder) {
            if ($createdOrder['success']) {
                $row['ref_id'] = $ref_id;
                $row['tracking_id'] = $createdOrder['tracking_id'];
                $row['json_data'] = json_encode($data);
                $row['is_deleted'] = false;
                $row['created_at'] = date("Y-m-d H:i:s");
                $dbResult = $wpdb->insert("{$wpdb->prefix}jt_return_order", $row, array('%s', '%s', '%s', '%d', '%s'));
            } else {
                $fails[$ref_id] = $createdOrder['error'];
            }
        }
        wp_send_json_success($createdOrders);
        return;
    }

    //TODO: filter by domestic/xborder
    if ($sub_tab == 'return-orders') {
        $jtOrders = loadReturnOrder($wpdb);
        //print_r($jtOrders);
        $tracking_ids = array();
        $tracking_id_statuses = array();
        foreach ($jtOrders as $returnOrder) {
            $tracking_id = $returnOrder['tracking_id'];
            $tracking_status = $returnOrder['tracking_status'];
            $updated_at = $returnOrder['updated_at'];
            $expired_time = strtotime("-5 minutes");

            $tracking_id_statuses[$tracking_id] = array();
            $tracking_id_statuses[$tracking_id]['order_id'] = $returnOrder['id'];
            $tracking_id_statuses[$tracking_id]['tracking_id'] = $tracking_id;
            $tracking_id_statuses[$tracking_id]['tracking_status'] = $tracking_status;
            $tracking_id_statuses[$tracking_id]['updated_at'] = $updated_at;
            if ($tracking_status == null || empty($tracking_status) || $updated_at == null || strtotime($updated_at) < $expired_time) {
                if (!in_array($tracking_status, NO_RELOAD_STATUSES)) {
                    $tracking_ids[] = $tracking_id;
                } else {
                    //print_r('no reload');
                    //print_r($tracking_status);
                }
            }
        }
        if (count($tracking_ids) > 0) {
            $tracking_statuses = $api->getTrackingStatus(array("ids" => $tracking_ids));
            //print_r($tracking_statuses);
            if (isset($tracking_statuses['deliveries']) && count($tracking_statuses['deliveries']) > 0) {
                $statuses = $tracking_statuses['deliveries'];
                foreach ($statuses as $status) {
                    $tracking_id_statuses[$status['tracking_id']]['details'] = $status;
                    $data = array('tracking_status' => $status['status'], 'updated_at' => date("Y-m-d H:i:s"));
                    $wpdb->update("{$wpdb->prefix}jt_return_order", $data, array('tracking_id' => $status['tracking_id']));
                }
            }

        }
        foreach ($tracking_id_statuses as $tracking_id_status) {
            //print_r($tracking_id_status);
            if (isset($tracking_id_status['details'])) {
                $jtOrders[$tracking_id_status['order_id']]['tracking_status'] = $tracking_id_status['details']['status'];
            } else {
                $jtOrders[$tracking_id_status['order_id']]['tracking_status'] = $tracking_id_status['tracking_status'];
            }
            $jtOrders[$tracking_id_status['order_id']]['cancelable'] = in_array($jtOrders[$tracking_id_status['order_id']]['tracking_status'], CANCELABLE_STATUSES) ? 1 : 0;
        }

    }

    if ($sub_tab != 'return-orders') {
        if ($cate == 'no_pickup') {
            $jtOrders = loadOrder($shipping_methods, null, $sub_tab);
            $delivery_orders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jt_delivery_order WHERE is_deleted = 0 ORDER BY created_at desc");
            foreach ($delivery_orders as $delivery_order) {
                $order_id = $delivery_order->order_id;
                unset($jtOrders[$order_id]);
            }
        } else {
            $jtOrders = array();
            $delivery_orders = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}jt_delivery_order WHERE is_deleted = 0 ORDER BY created_at desc");
            $tracking_id_statuses = array();
            $tracking_ids = array();
            foreach ($delivery_orders as $delivery_order) {
                $order_id = $delivery_order->order_id;
                $tracking_id = $delivery_order->tracking_id;
                $tracking_status = $delivery_order->tracking_status;
                $updated_at = $delivery_order->updated_at;
                $jtOrderDetails = loadOrder($shipping_methods, array($order_id), $sub_tab);
                $is_international_tab = $sub_tab == INTER_ORDERS;

                $expired_time = strtotime("-5 minutes");
                if (count($jtOrderDetails) > 0) {
                    if ($jtOrderDetails[$order_id]['is_international'] != $is_international_tab) {
                        continue;
                    }
                    $jtOrders[$order_id] = $jtOrderDetails[$order_id];
                    $jtOrders[$order_id]['tracking_id'] = $tracking_id;
                    $tracking_id_statuses[$tracking_id] = array();
                    $tracking_id_statuses[$tracking_id]['tracking_id'] = $tracking_id;
                    $tracking_id_statuses[$tracking_id]['order_id'] = $order_id;
                    $tracking_id_statuses[$tracking_id]['tracking_status'] = $tracking_status;
                    $tracking_id_statuses[$tracking_id]['updated_at'] = $updated_at;
                    if ($tracking_status == null || empty($tracking_status) || $updated_at == null || strtotime($updated_at) < $expired_time) {
                        if (!in_array($tracking_status, NO_RELOAD_STATUSES)) {
                            $tracking_ids[] = $tracking_id;
                        }
                    }
                }
            }
            if (count($tracking_ids) > 0) {
                $tracking_statuses = $api->getTrackingStatus(array("ids" => $tracking_ids));
                //print_r($tracking_statuses);
                if (isset($tracking_statuses['deliveries']) && count($tracking_statuses['deliveries']) > 0) {
                    $statuses = $tracking_statuses['deliveries'];
                    foreach ($statuses as $status) {
                        $tracking_id_statuses[$status['tracking_id']]['details'] = $status;
                        $data = array('tracking_status' => $status['status'], 'updated_at' => date("Y-m-d H:i:s"));
                        $wpdb->update("{$wpdb->prefix}jt_delivery_order", $data, array('tracking_id' => $status['tracking_id']));
                    }
                }

            }
            foreach ($tracking_id_statuses as $tracking_id_status) {
                //print_r($tracking_id_status);
                if (isset($tracking_id_status['details'])) {
                    $jtOrders[$tracking_id_status['order_id']]['tracking_status'] = $tracking_id_status['details']['status'];
                } else {
                    $jtOrders[$tracking_id_status['order_id']]['tracking_status'] = $tracking_id_status['tracking_status'];
                }
                $jtOrders[$tracking_id_status['order_id']]['cancelable'] = in_array($jtOrders[$tracking_id_status['order_id']]['tracking_status'], CANCELABLE_STATUSES) ? 1 : 0;
            }
            $jtOrders = array_filter($jtOrders, function ($o) use ($cate) {
                return classifyStatus($o['tracking_status']) == $cate;
            });
        }
    }
}

?>

<script type="text/javascript">
    var loginURL = "<?php echo jt_environment_variables()->login_url; ?>";
    var connoteURL = "<?php echo jt_environment_variables()->connote_url; ?>";
    var multipleConnoteUrl = "<?php echo jt_environment_variables()->multiple_connote_url; ?>";
    var activeTab = "<?php echo esc_attr($active_tab); ?>";
    var subTab = "<?php echo esc_attr($sub_tab); ?>";
    var token = "<?php echo isset($token) ? esc_attr($token) : ""; ?>";
</script>
<div class="app-content content jt-page-content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <?php if (!$show_wizard) : ?>
            <div class="content-header row">
                <div class="content-header-left col-12 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <h2 class="content-header-title float-left mb-0">
                                <a href="javascript:void(0);" class="brand-logo">
                                    <?php include 'common/logo.php'; ?>
                                </a>
                            </h2>
                        </div>
                        <div class="breadcrumb-wrapper">
                            <ul class="nav nav-pills m-0">
                                <li class="nav-item">
                                    <a class="nav-link <?php echo esc_attr($active_tab) == ORDERS ? 'active' : ''; ?>"
                                       id="order-tab"
                                       href=<?php echo esc_attr($originUrl) . ORDERS . SUB_TAB_URL . DOMESTIC_ORDERS ?>
                                       aria-expanded="true">Orders</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo esc_attr($active_tab) == SERVICE_MAPPING ? 'active' : ''; ?>"
                                       id="service-mapping-tab"
                                       href=<?php echo esc_attr($originUrl) . SERVICE_MAPPING ?> aria-expanded="true">Service
                                        Mapping</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="content-body">
            <?php if ($show_wizard) : ?>
                <?php if ($active_tab == 'api_key') : ?>
                    <?php include_once 'tabs/api_key.php'; ?>
                <?php endif; ?>

                <?php if ($active_tab == 'login') : ?>
                    <?php include_once 'tabs/login.php'; ?>
                <?php endif; ?>

                <div class="box">
                    <?php if ($show_wizard) : ?>
                        <input type="hidden" name="jt_woocommerce_wizard_on" value=1>
                    <?php endif; ?>

                    <input type="hidden" name="jt_woocommerce_settings_hidden" value="Y">
                </div>

            <?php else : ?>
                <?php if ($active_tab === ORDERS): ?>
                    <?php include_once 'tabs/orders.php'; ?>
                <?php elseif ($active_tab == SERVICE_MAPPING): ?>
                    <?php include_once 'tabs/service_mapping.php'; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>