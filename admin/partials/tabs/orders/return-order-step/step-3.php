<div id="step-3" class="content">
    <div class="content-header mb-2">
        <h5 class="mb-0">Pickup Address</h5>
        <small class="text-muted">Enter your pickup address.</small>
    </div>
    <form>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i data-feather='user'></i>
                    <span class="align-middle font-16">Contact Info</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4 form-group">
                        <label for="contact_name">Name</label>
                        <input type="text" id="contact_name" name="contact_name" class="form-control"
                            placeholder="Contact Name">
                    </div>
                    <div class="col-4 form-group">
                        <label for="contact_email">Email</label>
                        <input type="email" id="contact_email" class="form-control"
                            placeholder="Contact Email">
                    </div>
                    <div class="col-4 form-group">
                        <label for="contact_phone">Phone</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+65</span>
                            </div>
                            <input type="number" class="form-control" id="contact_phone" name="contact_phone" maxlength="8">
                        </div>
                    </div>
                    <!-- <div class="col-3 form-group">
                        <label for="contact_country">Country</label>
                        <input type="text" id="contact_country" value="Singapore" readonly class="form-control read-only">
                    </div>
                    <div class="col-3 form-group">
                        <label for="contact_city">City</label>
                        <input type="text" id="contact_city" value="Singapore" readonly class="form-control read-only">
                    </div> -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i data-feather='compass'></i>
                    <span class="align-middle font-16">Address Info</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="street_name">Blk/Hse No. & Street Name</label>
                        <input type="text" id="street_name" name="street_name" class="form-control" placeholder="Street Name">
                    </div>
                    <div class="col-6 form-group">
                        <label for="unit">Floor/Unit No.</label>
                        <input type="text" id="unit" name="unit" class="form-control" placeholder="Floor/Unit No.">
                    </div>
                    <div class="col-6 form-group">
                        <label for="building_name">Building Name</label>
                        <input type="text" id="building_name" name="building_name" class="form-control" placeholder="Building Name">
                    </div>
                    <div class="col-6 form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="number" id="postal_code" name="postal_code" class="form-control" placeholder="Postal Code" maxlength="6">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-between">
        <button class="btn btn-outline-secondary btn-prev">
            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
        </button>
        <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">Next</span>
            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
        </button>
    </div>
</div>