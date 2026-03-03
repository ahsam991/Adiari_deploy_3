<?php $pageTitle = 'Dashboard'; ?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h2>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Items</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_items']); ?></h2>
                    </div>
                    <div><i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
            <div class="card-footer bg-primary bg-opacity-50">
                <a href="<?php echo Router::url('/shop/inventory'); ?>" class="text-white text-decoration-none">View All <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Inventory Value</h6>
                        <h2 class="mb-0">¥<?php echo number_format($stats['total_value'], 0); ?></h2>
                    </div>
                    <div><i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
            <div class="card-footer bg-success bg-opacity-50"><small>Total stock value</small></div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Today's Sales</h6>
                        <h2 class="mb-0">¥<?php echo number_format($stats['today_sales'], 0); ?></h2>
                    </div>
                    <div><i class="bi bi-graph-up-arrow" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
            <div class="card-footer bg-info bg-opacity-50">
                <a href="<?php echo Router::url('/shop/sales'); ?>" class="text-white text-decoration-none">View Sales <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card <?php echo $stats['low_stock_count'] > 0 ? 'bg-warning' : 'bg-secondary'; ?> text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Low Stock Items</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['low_stock_count']); ?></h2>
                    </div>
                    <div><i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.5;"></i></div>
                </div>
            </div>
            <div class="card-footer <?php echo $stats['low_stock_count'] > 0 ? 'bg-warning' : 'bg-secondary'; ?> bg-opacity-50"><small>Items need reorder</small></div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
                <div class="btn-group flex-wrap" role="group">
                    <a href="<?php echo Router::url('/shop/pos'); ?>" class="btn btn-success"><i class="bi bi-cash-register"></i> POS</a>
                    <a href="<?php echo Router::url('/shop/inventory/add'); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Item</a>
                    <a href="<?php echo Router::url('/shop/sales/add'); ?>" class="btn btn-info"><i class="bi bi-cart-plus"></i> Record Sale</a>
                    <a href="<?php echo Router::url('/shop/inventory'); ?>" class="btn btn-outline-secondary"><i class="bi bi-list-ul"></i> Inventory</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Low Stock Items -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Low Stock Alert</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($lowStockItems)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th>Category</th><th>Current</th><th>Reorder</th></tr></thead>
                            <tbody>
                                <?php foreach ($lowStockItems as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['category']); ?></span></td>
                                        <td><span class="badge bg-danger"><?php echo $item['quantity']; ?></span></td>
                                        <td><?php echo $item['reorder_level']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-success mb-0"><i class="bi bi-check-circle"></i> All items are well stocked!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Sales -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Sales</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentSales)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>Product</th><th>Qty</th><th>Amount</th><th>Date</th></tr></thead>
                            <tbody>
                                <?php foreach ($recentSales as $sale): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                                        <td><?php echo $sale['quantity_sold']; ?></td>
                                        <td>¥<?php echo number_format($sale['total_amount'], 0); ?></td>
                                        <td><small><?php echo date('M d, H:i', strtotime($sale['sale_date'])); ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo Router::url('/shop/sales'); ?>" class="btn btn-sm btn-outline-info">View All Sales</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0"><i class="bi bi-info-circle"></i> No sales recorded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
