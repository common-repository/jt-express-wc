<section class="vertical-wizard">
    <div class="bs-stepper vertical vertical-wizard-example">
        <div class="bs-stepper-header">
            <?php include_once 'orders/nav-left-order.php'; ?>
        </div>
        <div class="bs-stepper-content">
            <div class="content dstepper-block active">
                <?php if ($sub_tab == INTER_ORDERS) : ?>
                <?php include_once 'orders/international-orders.php'; ?>
                <?php endif; ?>
                <?php if ($sub_tab == DOMESTIC_ORDERS) : ?>
                <?php include_once 'orders/dometic-orders.php'; ?>
                <?php endif; ?>
                <?php if ($sub_tab == RETURN_ORDERS) : ?>
                <?php include_once 'orders/return-orders.php'; ?>
                <?php endif; ?>
                <?php if ($sub_tab == CREATE_RETURN_ORDER) : ?>
                <?php include_once 'orders/create-return-order.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

