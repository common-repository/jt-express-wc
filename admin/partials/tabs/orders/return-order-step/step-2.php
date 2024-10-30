<div id="step-2" class="content">
    <div class="content-header mb-2">
        <h5 class="mb-0">Pickup / Delivery Dates</h5>
        <small class="text-muted">Select pickup date and delivery date</small>

        <div class="alert alert-secondary mt-1" role="alert">
            <div class="alert-body">
                <i data-feather='info'></i>
                <span>Order confirmed before 4pm on a working day (Mon-Sat, except PH) will be picked up the same day before 6pm and delivered the next working day.</span>
            </div>
        </div>
        <div class="alert alert-secondary" role="alert">
            <div class="alert-body">
                <i data-feather='info'></i>
                <span>Order after 4pm on a working day will be picked up the next working day and delivered the next working day.</span>
            </div>
        </div>
    </div>
    <form>
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i data-feather='clock'></i>
                            <span class="align-middle font-16">Select your pickup date</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="pickup-date">Pickup Date & Time</label>
                                <input type="text" id="pickup-date" name="pickup-date" class="form-control flatpickr-basic flatpickr-input"
                                    placeholder="YYYY-MM-DD" readonly="readonly">
                            </div>
                            <div class="col-6 form-group">
                                <label for="pickup-time-slot">Pickup Time Slot</label>
                                <select class="form-control" id="pickup-time-slot" name="pickup-time-slot">
                                    <option value="9-18">09:00 - 18:00</option>
                                    <option value="9-12">09:00 - 12:00</option>
                                    <option value="12-15">12:00 - 15:00</option>
                                    <option value="15-18">15:00 - 18:00</option>
                                </select>
                            </div>
                            <!-- <div class="col-12 form-group">
                                <label for="pickup-note">Item description</label>
                                <textarea class="form-control" id="pickup-note" rows="3"
                                    placeholder="Item description"></textarea>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-12 col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i data-feather='clock'></i>
                            <span class="align-middle font-16">Select your delivery date</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="length">Delivery date</label>
                                <input type="text" id="delivery_date" value="By 29/04/2021" readonly class="form-control read-only">
                            </div>
                            <div class="col-12 form-group">
                                <label for="delivery-note">Delivery note</label>
                                <textarea class="form-control" id="delivery-note" rows="3" placeholder="Input instruction here"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
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