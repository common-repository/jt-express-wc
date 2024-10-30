<div class="content-header">
    <h4 class="mb-0">International Orders</h4>
</div>
<div class="row">
    <div class="col-12">
        <form method="get">
            <?php
                if($cate != null && $cate != 'no_pickup') {
                    $test_list_table = new JTWC_Delivery_Order_List_Table();
                } else {
                    $test_list_table = new JTWC_Order_List_Table();
                }
                $test_list_table->setData($jtOrders);
                $test_list_table->prepare_items();
                $test_list_table->views();
                $test_list_table->display();
            ?>
            <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
            <input type="hidden" name="tab" value="<?php echo esc_attr(  $_REQUEST['tab'] ); ?>" />
            <input type="hidden" name="sub_tab" value="<?php echo esc_attr( $_REQUEST['sub_tab'] ); ?>" />
        </form>
    </div>
</div>

<?php include_once 'schedule-pick-up-modal.php'; ?>