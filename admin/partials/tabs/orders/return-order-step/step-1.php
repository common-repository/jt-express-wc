<div id="step-1" class="content">
    <div class="content-header mb-2">
        <h5 class="mb-0">Create Order</h5>
        <small class="text-muted">Enter Your Order Details</small>

        <div class="alert alert-secondary mt-1" role="alert">
            <div class="alert-body">
                <i data-feather='info'></i>
                <span>If you confirm this order by 4pm, you are qualified for pickup today.</span>
            </div>
        </div>
    </div>
    <form>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i data-feather='tag'></i>
                    <span class="align-middle font-16">Order Details</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 form-group">
                        <label for="service_type">Service Type</label>
                        <select class="form-control" id="service_type" disabled>
                            <option selected="selected" value="RT">RT</option>
                        </select>
                    </div>
                    <div class="col-6 form-group">
                        <label for="order_reference">Order Reference</label>
                        <input type="text" id="order_reference" name="order_reference" class="form-control" placeholder="Order Refercence">
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i data-feather='anchor'></i>
                    <span class="align-middle font-16">Item Details</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-xl-2 form-group">
                        <label for="length">Length (cm)</label>
                        <input type="number" id="length" class="form-control" placeholder="Length (cm)" min="0">
                    </div>
                    <div class="col-12 col-xl-2 form-group">
                        <label for="width">Width (cm)</label>
                        <input type="number" id="width"  class="form-control" placeholder="Width (cm)" min="0">
                    </div>
                    <div class="col-12 col-xl-2 form-group">
                        <label for="height">Height (cm)</label>
                        <input type="number" id="height"  class="form-control" placeholder="Height (cm)" min="0">
                    </div>
                    <div class="col-12 col-xl-2 form-group">
                        <label for="weight">Weight</label>
                        <input type="number" id="weight" name="weight" class="form-control" placeholder="Weight" min="0">
                    </div>
                    <div class="col-12 col-xl-4 form-group">
                        <label for="weight_unit">Weight Unit</label>
                        <select class="form-control" id="weight_unit" name="weight_unit">
                            <option value="" selected="selected" class="">Select weight unit</option>
                            <option value="kg">Kg</option>
                            <option value="g">G</option>
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Description"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-between">
        <button class="btn btn-outline-secondary btn-prev invisible" disabled="">
            <i data-feather="arrow-left" class="align-middle mr-sm-25 mr-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
        </button>
        <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">Next</span>
            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
        </button>
    </div>
</div>