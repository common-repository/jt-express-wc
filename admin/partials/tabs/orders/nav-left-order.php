<a class="step <?php echo esc_attr($sub_tab) == DOMESTIC_ORDERS ? 'active' : ''; ?>" href=<?php echo esc_attr($originUrl) . ORDERS . SUB_TAB_URL . DOMESTIC_ORDERS ?>>
    <button type="button" class="step-trigger" aria-selected="true">
        <span class="bs-stepper-box">
            <i data-feather='shopping-bag'></i>
        </span>
        <span class="bs-stepper-label">
            <span class="bs-stepper-title">Domestic</span>
            <span class="bs-stepper-subtitle">List all domestic orders</span>
        </span>
    </button>
</a>
<a class="step <?php echo esc_attr($sub_tab) == INTER_ORDERS ? 'active' : ''; ?>" href=<?php echo esc_attr($originUrl) . ORDERS . SUB_TAB_URL . INTER_ORDERS ?>>
    <button type="button" class="step-trigger" aria-selected="false">
        <span class="bs-stepper-box">
            <i data-feather='globe'></i>
        </span>
        <span class="bs-stepper-label">
            <span class="bs-stepper-title">International</span>
            <span class="bs-stepper-subtitle">List all international orders</span>
        </span>
    </button>
</a>
<a class="step <?php echo (esc_attr($sub_tab) == RETURN_ORDERS ||  esc_attr($sub_tab) == CREATE_RETURN_ORDER) ? 'active' : ''; ?>" href=<?php echo esc_attr($originUrl) . ORDERS . SUB_TAB_URL . RETURN_ORDERS ?>>
    <button type="button" class="step-trigger" aria-selected="false">
        <span class="bs-stepper-box">
            <i data-feather='file-plus'></i>
        </span>
        <span class="bs-stepper-label">
            <span class="bs-stepper-title">Return Orders</span>
            <span class="bs-stepper-subtitle">List all orders need return</span>
        </span>
    </button>
</a>