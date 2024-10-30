<div class="row">
    <div class="col-12">
        <section class="horizontal-wizard return-order-tab">
            <div class="bs-stepper horizontal-wizard-example linear">
                <div class="bs-stepper-header flex-row align-items-center">
                    <div class="step" data-target="#step-1">
                        <button type="button" class="step-trigger" aria-selected="true">
                            <span class="bs-stepper-box">1</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Order Details</span>
                                <!-- <span class="bs-stepper-subtitle">Fill up the order information</span> -->
                            </span>
                        </button>
                    </div>
                    <div class="line d-block">
                        <i data-feather='chevron-right' class="font-medium-2"></i>
                    </div>
                    <div class="step" data-target="#step-2">
                        <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
                            <span class="bs-stepper-box">2</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Pickup / Delivery Dates</span>
                                <!-- <span class="bs-stepper-subtitle">Choose pickup / delivery date</span> -->
                            </span>
                        </button>
                    </div>
                    <div class="line d-block">
                        <i data-feather='chevron-right' class="font-medium-2"></i>
                    </div>
                    <div class="step" data-target="#step-3">
                        <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
                            <span class="bs-stepper-box">3</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Pickup Address</span>
                                <!-- <span class="bs-stepper-subtitle">Fill up the pickup address</span> -->
                            </span>
                        </button>
                    </div>
                    <div class="line d-block">
                        <i data-feather='chevron-right' class="font-medium-2"></i>
                    </div>
                    <div class="step" data-target="#step-4">
                        <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
                            <span class="bs-stepper-box">4</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Delivery Address</span>
                                <!-- <span class="bs-stepper-subtitle">Fill up the delivery address</span> -->
                            </span>
                        </button>
                    </div>
                    <div class="line d-block">
                        <i data-feather='chevron-right' class="font-medium-2"></i>
                    </div>
                    <div class="step" data-target="#step-5">
                        <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
                            <span class="bs-stepper-box">5</span>
                            <span class="bs-stepper-label">
                                <span class="bs-stepper-title">Confirmation</span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <?php include_once 'return-order-step/step-1.php'; ?>
                    <?php include_once 'return-order-step/step-2.php'; ?>
                    <?php include_once 'return-order-step/step-3.php'; ?>
                    <?php include_once 'return-order-step/step-4.php'; ?>
                    <?php include_once 'return-order-step/step-5.php'; ?>
                </div>
            </div>
        </section>
    </div>
</div>

