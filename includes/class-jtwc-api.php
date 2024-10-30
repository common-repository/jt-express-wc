<?php

/**
 * Class JT_API
 */
class JTWC_API
{
    protected $cred = null;
    protected $end_point = 'https://jts.jtexpress.sg/jts-service-doorstep/api/gateway/v1';

    protected static $instance = null;
    protected static $token = null;

    public static function getInstance()
    {
        return static::$instance;
    }

    public static function constructInstance($cred)
    {
        static::$instance = new JTWC_API($cred);
    }

    public function __construct($cred = null)
    {
        if (!empty($cred)) {
            $this->cred = $cred;
        }
    }

    public function login()
    {
        $url = "$this->end_point/auth/login";
        $headers = $this->extendHeaders(array(
            "Authorization" => "Basic $this->cred",
        ));

        $result = $this->post($url, array(), $headers);
        if (isset($result['token'])) {
            self::$token = $result['token'];
            return $result['token'];
        }
        return null;
    }

    public function getServices()
    {
        $services = get_transient('jt-services');
        if ($services == false) {
            $result = $this->get("$this->end_point/merchants/getServiceTypes");
            $services = $result['serviceTypes'];
            if (count($services) > 0) {
                set_transient('jt-services', $services, 40000);
            }
        }
        return $services;
    }

    public function createOrder($orders)
    {
        $createdOrders = array();
        if (count($orders) == 0) {
            return null;
        }

        if (count($orders) == 1) {
            //create single order
            $order = $orders[0];
            //print_r('order : <br>');
            //print_r(json_encode($order));
            $result = $this->post("$this->end_point/deliveries", $order);
            $this->failFast($result);
            $createdOrders[$result['reference_number']] = array('success' => true, 'tracking_id' => $result['tracking_id']);
        } else {
            //print_r(json_encode($orders));
            $result = $this->post("$this->end_point/deliveries/batch", array('shipments' => $orders));
            $this->failFast($result);
            $successes = $result['success'];
            $errors = $result['errors'];
            foreach ($successes as $success) {
                $createdOrders[$success['reference_number']] = array('success' => true, 'tracking_id' => $success['tracking_id']);
            }
            foreach ($errors as $err) {
                $createdOrders[$err['reference_number']] = array('success' => false, 'error' => $err['error']);
            }
        }

        return $createdOrders;
    }

    public function cancelOrder($ids)
    {
        $canceledOrders = array();
        //print_r(json_encode($orders));
        $result = $this->post("$this->end_point/deliveries/operation", array('type' => 'CANCEL', 'data' => array('ids' => $ids, 'reason' => 'Customer cancels the order')));
        $this->failFast($result);
        $successes = $result['success'];
        $errors = $result['errors'];
        foreach ($successes as $success) {
            $canceledOrders[$success] = array('success' => true);
        }
        foreach ($errors as $err) {
            $canceledOrders[$err] = array('success' => false);
        }

        return $canceledOrders;
    }

    function failFast($result)
    {
        print_r('result: <br/>');
        print_r($result);
        if ($result['status'] == 400) {
            print_r('ERROR:');
            wp_send_json_error($result, 400);
            die();
        }
    }

    public function getTrackingStatus($ids)
    {
        $result = $this->post("$this->end_point/track", $ids);
        //print_r($result);
        return $result;
    }

    public function resyncServices()
    {
        delete_transient('jt-services');
    }


    protected function get($url, $params = null)
    {
        $headers = $this->extendHeaders(array());
        $url = $this->url($url, $params);
        $rawResponse = wp_remote_get($url, array('headers' => $headers));
        return $this->extractResponse($rawResponse);
    }


    protected function patch($url, $body)
    {
        $headers = $this->extendHeaders(array());
        $url = $this->url($url);
        $rawResponse = wp_remote_request($url, array(
            'headers' => $headers,
            'method' => 'PATCH',
            'body' => $body
        ));
        return $this->extractResponse($rawResponse);
    }

    protected function post($url, $body, $headers = array())
    {
        $headers = $this->extendHeaders($headers);
        $url = $this->url($url);
        $rawResponse = wp_remote_post($url, array(
            'headers' => $headers,
            'body' => json_encode($body)
        ));
        return $this->extractResponse($rawResponse);
    }

    protected function put($url, $body)
    {
        $headers = $this->extendHeaders(array());
        $url = $this->url($url);
        $rawResponse = wp_remote_request($url, array(
            'headers' => $headers,
            'method' => 'PUT',
            'body' => json_encode($body)
        ));
        return $this->extractResponse($rawResponse);
    }

    protected function url($extra = '', $params = null)
    {
        $url = $this->end_point;
        if (!empty($extra)) {
            $url = $extra;
        }

        if (!empty($params)) {
            $url .= '?' . (is_array($params) ? http_build_query($params) : $params);
        }

        return $url;
    }

    protected function extendHeaders($headers = array())
    {
        $extendedHeaders = array(
            'content-type' => 'application/json',
            'accept' => 'application/json'
        );

        if (isset(self::$token)) {
            $extendedHeaders['Authorization'] = "JWT ". self::$token;
        }
        $extendedHeaders = array_merge($extendedHeaders, $headers);
        return $extendedHeaders;
    }

    protected function extractResponse($rawResponse)
    {
        $body = wp_remote_retrieve_body($rawResponse);
        return json_decode($body, true);
    }

}
