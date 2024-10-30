<div class="content-header">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="mb-0">Return Orders</h4>
        <a href=<?php echo esc_attr($originUrl) . ORDERS . SUB_TAB_URL . CREATE_RETURN_ORDER ?> class="btn btn-primary">
            <i data-feather="file-plus" class="align-middle mr-sm-25 mr-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Create Order</span>
        </a>
        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <form method="get">
            <?php
                $return_order_table = new JTWC_Return_Order_List_Table();
                $return_order_table->setData($jtOrders);
                $return_order_table->prepare_items();
                $return_order_table->views();
                $return_order_table->display();
            ?>
            <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
            <input type="hidden" name="tab" value="<?php echo esc_attr(  $_REQUEST['tab'] ); ?>" />
            <input type="hidden" name="sub_tab" value="<?php echo esc_attr( $_REQUEST['sub_tab'] ); ?>" />
        </form>
    </div>
</div>
