<?php $pageTitle = 'Coupons'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-tag"></i> Coupons</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($coupons)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark"><tr><th>Code</th><th>Description</th><th>Type</th><th>Value</th><th>Min Purchase</th><th>Max Discount</th><th>Valid From</th><th>Valid Until</th><th>Usage</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($coupons as $c): ?>
                                    <tr>
                                        <td><code class="fw-bold"><?php echo htmlspecialchars($c['code']); ?></code></td>
                                        <td><?php echo htmlspecialchars($c['description'] ?? '-'); ?></td>
                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($c['discount_type']); ?></span></td>
                                        <td><?php echo $c['discount_type'] === 'percentage' ? $c['discount_value'] . '%' : '¥' . number_format($c['discount_value'], 0); ?></td>
                                        <td>¥<?php echo number_format($c['min_purchase'] ?? $c['min_purchase_amount'] ?? 0, 0); ?></td>
                                        <td><?php echo ($c['max_discount_amount'] ?? 0) > 0 ? '¥' . number_format($c['max_discount_amount'], 0) : '-'; ?></td>
                                        <td><small><?php echo $c['valid_from'] ?? '-'; ?></small></td>
                                        <td><small><?php echo $c['valid_until'] ?? '-'; ?></small></td>
                                        <td><?php echo ($c['times_used'] ?? 0) . ($c['usage_limit'] > 0 ? ' / ' . $c['usage_limit'] : ''); ?></td>
                                        <td><span class="badge <?php echo ($c['is_active'] ?? 1) ? 'bg-success' : 'bg-secondary'; ?>"><?php echo ($c['is_active'] ?? 1) ? 'Active' : 'Inactive'; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted small mt-2"><i class="bi bi-info-circle"></i> Use coupon codes at POS checkout.</p>
                <?php else: ?>
                    <p class="text-muted text-center py-4"><i class="bi bi-tag" style="font-size: 3rem;"></i><br>No coupons configured.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
