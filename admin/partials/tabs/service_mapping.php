<div class="alert alert-danger mt-1 mb-1 d-none" role="alert">
    <div class="alert-body">
        <i data-feather='info'></i>
        <span>If you confirm this order by 4pm, you are qualified for pickup today.</span>
    </div>
</div>
<section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom p-1">
                    <div class="head-label"><h5 class="mb-0">Service Mapping</h5></div>
                    <div class="dt-action-buttons text-right">
                        <div class="dt-buttons d-inline-flex">
                            <button id="service_mapping_resync" type="button" class="btn btn-outline-primary mr-1">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i data-feather='loader'></i>
                                <span class="ml-25 align-middle">Resync</span>
                            </button>
                            <button id="service_mapping_submit" type="button" class="btn btn-primary">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <i data-feather='save'></i>
                                <span class="ml-25 align-middle">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="table table-hover" id="service-mapping-table">
                        <thead>
                            <tr>
                                <th>Woocomerce shipping zone</th>
                                <th>Woocomerce shipping name</th>
                                <th>J&amp;T service name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $shipping_methods as $shipping_method ) : ?>
                            <tr>
                                <td hidden><input type="text" name='instance_id'
                                        value="<?php echo esc_attr( $shipping_method['instance_id'] ); ?>" /></td>
                                <td hidden><input disabled type="text" name='zone_id'
                                        value="<?php echo esc_attr( $shipping_method['zone_id'] ); ?>" /></td>
                                <td>
                                    <input disabled type="hidden"
                                        value="<?php echo esc_attr( $shipping_method['formatted_zone_location'] ); ?>" />
                                    <span class="font-weight-bold"><?php echo esc_attr( $shipping_method['formatted_zone_location'] ); ?></span>
                                </td>

                                <td>
                                    <input disabled type="hidden"
                                        value="<?php echo esc_attr( $shipping_method['method_title'] ); ?>" />
                                    <span class="font-weight-bold"><?php echo esc_attr( $shipping_method['method_title'] ); ?></span>
                                </td>

                                <td>
                                    <select class="form-control" name="jt-service" id="jt-service">
                                        <option value="none">
                                            None
                                        </option>
                                        <?php foreach ( $jt_services as $jt_service ) : ?>
                                        <option value="<?php echo esc_attr( $jt_service['serviceCode'] ); ?>"
                                            <?php echo (esc_attr($jt_service['serviceCode']) == esc_attr($shipping_method['service_code']) ? "selected":"bb") ; ?>>
                                            <?php echo esc_attr( $jt_service['name'] ); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="service_enable_<?php echo esc_attr($shipping_method['instance_id']) ?>" name="service_enable_<?php echo $shipping_method['instance_id'] ?>"
                                            class="custom-control-input"
                                            <?php echo esc_attr($shipping_method['enable']) == 1 ? "checked":""; ?> />
                                        <label class="custom-control-label" for="service_enable_<?php echo esc_attr($shipping_method['instance_id']); ?>">Enable</label>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>