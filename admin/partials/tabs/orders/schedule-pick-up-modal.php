<div class="modal fade" id="schedule-pick-up" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <form id="pickup-timing-form" novalidate="novalidate">
                <div class="modal-header">
                    <h5 class="modal-title">Pickup Timing & Instruction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i data-feather='tag'></i>
                                        <span class="align-middle font-16">Pickup Details</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="contact-name">Contact Name</label>
                                                <input type="text" id="contact-name" name="contact-name" class="form-control"
                                                    placeholder="Contact Name">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="phone-number">Phone Number</label>
                                                <input type="number" id="phone-number" name="phone-number" class="form-control"
                                                    placeholder="Phone Number">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="post-code">Post Code</label>
                                                <input type="number" id="post-code" name="post-code" class="form-control"
                                                    placeholder="Post Code">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea class="form-control" id="address" name="address" rows="4"
                                                    placeholder="Address"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card" id="parcel-details">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i data-feather='box'></i>
                                        <span class="align-middle">Parcel Details</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                    <?php if (esc_attr($sub_tab) == INTER_ORDERS) : ?>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="parcel-length">Parcel's length</label>
                                                <input type="number" id="parcel-length" name="parcel-length" class="form-control" min="0"
                                                    placeholder="Parcel's length (cm)">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="parcel-width">Parcel's width</label>
                                                <input type="number" id="parcel-width" name="parcel-width" class="form-control" min="0"
                                                    placeholder="Parcel's width (cm)">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="parcel-height">Parcel's height</label>
                                                <input type="number" id="parcel-height" name="parcel-height" class="form-control" min="0"
                                                    placeholder="Parcel's height (cm)">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="parcel-height">Parcel's value</label>
                                                <input type="number" id="parcel-value" name="parcel-value" class="form-control" min="1"
                                                    placeholder="Parcel's value">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="parcel-weight">Parcel's weight (<?php echo (esc_attr($weight_unit)) ?>)</label>
                                                <input type="number" id="parcel-weight" name="parcel-weight" class="form-control"
                                                    placeholder="Parcel's weight (<?php echo (esc_attr($weight_unit)) ?>)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i data-feather='clock'></i>
                                        <span class="align-middle font-16">Pickup Time</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="pickup-date">Pickup Date & Time</label>
                                            <input type="text" id="pickup-date" name="pickup-date"
                                                class="form-control flatpickr-basic flatpickr-input"
                                                placeholder="YYYY-MM-DD" readonly="readonly">
                                        </div>
                                        <div class="col-6 form-group">
                                            <label for="pickup-time-slot">Pickup Time Slot</label>
                                            <select class="form-control" id="pickup-time-slot" name="pickup-time-slot">
                                                <option selected="selected" value="9-18">09:00 - 18:00</option>
                                                <option value="9-12">09:00 - 12:00</option>
                                                <option value="12-15">12:00 - 15:00</option>
                                                <option value="15-18">15:00 - 18:00</option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="pickup-note">Item description</label>
                                            <textarea class="form-control" id="pickup-note" name="pickup-note" rows="3"
                                                placeholder="Item description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="order-error" class="alert alert-danger mt-1 mb-1" style="display: none" role="alert">
                                <div class="alert-body">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" type="button" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="align-middle">Next</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>