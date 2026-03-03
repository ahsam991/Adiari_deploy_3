<?php $pageTitle = 'Edit Customer'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil"></i> Edit Customer</h2>
            <a href="<?php echo Router::url('/shop/customers'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Customers</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo Router::url('/shop/customers/update/' . $customer['id']); ?>" method="POST">
                    <div class="mb-3"><label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($customer['customer_name']); ?>" required></div>
                    <div class="mb-3"><label for="phone" class="form-label">Phone <span class="text-danger">*</span></label><input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" required></div>
                    <div class="mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>"></div>
                    <div class="mb-3"><label for="address" class="form-label">Address</label><textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea></div>
                    <div class="mb-2 text-muted small"><i class="bi bi-info-circle"></i> Total purchases: ¥<?php echo number_format($customer['total_purchases'] ?? 0, 0); ?> | Loyalty points: <?php echo $customer['loyalty_points'] ?? 0; ?></div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>
