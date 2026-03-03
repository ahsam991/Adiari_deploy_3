<?php $pageTitle = 'Inventory Management'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-box-seam"></i> Inventory Management</h2>
            <div>
                <a href="<?php echo Router::url('/shop/inventory/add'); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Item
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($items)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th><th>Product Name</th><th>Category</th><th>Quantity</th>
                                    <th>Unit Price</th><th>Total Value</th><th>Reorder Level</th><th>Supplier</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr <?php echo ($item['quantity'] <= $item['reorder_level']) ? 'class="table-warning"' : ''; ?>>
                                        <td><?php echo $item['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                            <?php if ($item['quantity'] <= $item['reorder_level']): ?>
                                                <span class="badge bg-warning text-dark ms-2"><i class="bi bi-exclamation-triangle"></i> Low Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['category']); ?></span></td>
                                        <td>
                                            <span class="badge <?php echo ($item['quantity'] <= $item['reorder_level']) ? 'bg-danger' : 'bg-success'; ?>">
                                                <?php echo $item['quantity']; ?>
                                            </span>
                                        </td>
                                        <td>¥<?php echo number_format($item['unit_price'], 0); ?></td>
                                        <td>¥<?php echo number_format($item['quantity'] * $item['unit_price'], 0); ?></td>
                                        <td><?php echo $item['reorder_level']; ?></td>
                                        <td>
                                            <?php if (!empty($item['supplier_name'])): ?>
                                                <small><?php echo htmlspecialchars($item['supplier_name']); ?>
                                                <?php if (!empty($item['supplier_contact'])): ?>
                                                    <br><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($item['supplier_contact']); ?>
                                                <?php endif; ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">N/A</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo Router::url('/shop/inventory/edit/' . $item['id']); ?>" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-item-id="<?php echo $item['id']; ?>"
                                                    data-item-name="<?php echo htmlspecialchars($item['product_name']); ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total Inventory Value:</strong></td>
                                    <td colspan="4">
                                        <strong>¥<?php 
                                            $totalValue = array_sum(array_map(function($item) {
                                                return $item['quantity'] * $item['unit_price'];
                                            }, $items));
                                            echo number_format($totalValue, 0); 
                                        ?></strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                        <h4 class="text-muted mt-3">No inventory items found</h4>
                        <a href="<?php echo Router::url('/shop/inventory/add'); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add First Item</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <p class="fw-bold text-danger" id="deleteItemName"></p>
                <p class="small text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete Item</a>
            </div>
        </div>
    </div>
</div>

<script>
var deleteModal = document.getElementById('deleteModal');
if (deleteModal) {
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        while (button && !button.dataset.itemId) { button = button.parentElement; }
        var itemId = button ? button.dataset.itemId : '';
        var itemName = button ? button.dataset.itemName : '';
        document.getElementById('deleteItemName').textContent = itemName;
        document.getElementById('confirmDeleteBtn').href = '<?php echo Router::url('/shop/inventory/delete/'); ?>' + itemId;
    });
}
</script>
