<?php $pageTitle = 'Invoice History'; ?>

<div class="row mb-3">
    <div class="col-12"><h2><i class="bi bi-receipt"></i> Invoice History</h2></div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Invoices</span>
        <a href="<?php echo Router::url('/shop/pos'); ?>" class="btn btn-primary btn-sm"><i class="bi bi-arrow-left"></i> Back to POS</a>
    </div>
    <div class="card-body">
        <?php if (empty($invoices)): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">No invoices found</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light"><tr><th>Invoice #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($inv['invoice_number']); ?></strong></td>
                            <td>
                                <?php if ($inv['customer_name']): ?>
                                    <?php echo htmlspecialchars($inv['customer_name']); ?>
                                    <?php if ($inv['customer_phone']): ?><br><small class="text-muted"><?php echo htmlspecialchars($inv['customer_phone']); ?></small><?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Walk-in Customer</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong class="text-success">¥<?php echo number_format($inv['total_amount'], 0); ?></strong>
                                <?php if ($inv['discount'] > 0): ?><br><small class="text-muted">Discount: ¥<?php echo number_format($inv['discount'], 0); ?></small><?php endif; ?>
                            </td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($inv['payment_method']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($inv['created_at'])); ?><br><small class="text-muted"><?php echo date('h:i A', strtotime($inv['created_at'])); ?></small></td>
                            <td>
                                <a href="<?php echo Router::url('/shop/pos/invoice/' . $inv['id']); ?>" class="btn btn-sm btn-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?php echo Router::url('/shop/pos/invoice/' . $inv['id']); ?>" class="btn btn-sm btn-secondary" title="Print" target="_blank"><i class="bi bi-printer"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
