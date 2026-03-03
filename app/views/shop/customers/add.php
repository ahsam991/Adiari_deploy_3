<?php $pageTitle = 'Add Customer'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-plus"></i> Add Customer</h2>
            <a href="<?php echo Router::url('/shop/customers'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Customers</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo Router::url('/shop/customers/store'); ?>" method="POST">
                    <div class="mb-3"><label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="customer_name" name="customer_name" required></div>
                    <div class="mb-3"><label for="phone" class="form-label">Phone <span class="text-danger">*</span></label><input type="text" class="form-control" id="phone" name="phone" required></div>
                    <div class="mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control" id="email" name="email"></div>
                    <div class="mb-3"><label for="address" class="form-label">Address</label><textarea class="form-control" id="address" name="address" rows="2"></textarea></div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Add Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>
