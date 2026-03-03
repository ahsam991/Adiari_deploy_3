<?php $pageTitle = 'Customers'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-people"></i> Customers</h2>
            <a href="<?php echo Router::url('/shop/customers/add'); ?>" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add Customer</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($customers)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark"><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Total Purchases</th><th>Loyalty Points</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php foreach ($customers as $c): ?>
                                    <tr>
                                        <td><?php echo $c['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($c['customer_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($c['phone'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($c['email'] ?? '-'); ?></td>
                                        <td>¥<?php echo number_format($c['total_purchases'] ?? 0, 0); ?></td>
                                        <td><span class="badge bg-warning text-dark"><?php echo $c['loyalty_points'] ?? 0; ?> pts</span></td>
                                        <td><a href="<?php echo Router::url('/shop/customers/edit/' . $c['id']); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4"><i class="bi bi-people" style="font-size: 3rem;"></i><br>No customers yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
