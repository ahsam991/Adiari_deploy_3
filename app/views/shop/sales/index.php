<?php $pageTitle = 'Sales History'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cart-check"></i> Sales History</h2>
            <a href="<?php echo Router::url('/shop/sales/add'); ?>" class="btn btn-success"><i class="bi bi-cart-plus"></i> Record New Sale</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($sales)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr><th>Sale ID</th><th>Product Name</th><th>Category</th><th>Quantity Sold</th><th>Unit Price</th><th>Total Amount</th><th>Customer</th><th>Sale Date</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalRevenue = 0;
                                foreach ($sales as $sale): 
                                    $totalRevenue += $sale['total_amount'];
                                ?>
                                    <tr>
                                        <td><strong>#<?php echo $sale['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($sale['product_name'] ?? ''); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($sale['category'] ?? ''); ?></span></td>
                                        <td><span class="badge bg-info"><?php echo $sale['quantity_sold']; ?></span></td>
                                        <td>¥<?php echo number_format($sale['unit_price'] ?? 0, 0); ?></td>
                                        <td><strong class="text-success">¥<?php echo number_format($sale['total_amount'], 0); ?></strong></td>
                                        <td><?php echo !empty($sale['customer_name']) ? htmlspecialchars($sale['customer_name']) : '<span class="text-muted">Walk-in</span>'; ?></td>
                                        <td><small><?php echo date('M d, Y', strtotime($sale['sale_date'])); ?><br><span class="text-muted"><?php echo date('h:i A', strtotime($sale['sale_date'])); ?></span></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total Revenue:</strong></td>
                                    <td colspan="3"><strong class="text-success fs-5">¥<?php echo number_format($totalRevenue, 0); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="text-muted mt-3">No sales recorded yet</h4>
                        <a href="<?php echo Router::url('/shop/sales/add'); ?>" class="btn btn-success"><i class="bi bi-cart-plus"></i> Record First Sale</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
