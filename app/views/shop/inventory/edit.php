<?php $pageTitle = 'Edit Item'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil"></i> Edit Inventory Item</h2>
            <a href="<?php echo Router::url('/shop/inventory'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Inventory</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo Router::url('/shop/inventory/update/' . $item['id']); ?>" method="POST">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" name="product_name" 
                               value="<?php echo htmlspecialchars($item['product_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category" name="category" 
                               value="<?php echo htmlspecialchars($item['category']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label"><i class="bi bi-upc-scan"></i> Barcode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control" id="barcode" name="barcode" 
                                   value="<?php echo htmlspecialchars($item['barcode'] ?? ''); ?>" placeholder="Scan or enter barcode">
                            <button class="btn btn-outline-primary" type="button" onclick="startGlobalScanner('barcode')"><i class="bi bi-camera"></i> Scan</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" 
                                       value="<?php echo $item['quantity']; ?>" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unit_price" class="form-label">Unit Price (¥) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="unit_price" name="unit_price" 
                                       value="<?php echo $item['unit_price']; ?>" step="1" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="reorder_level" class="form-label">Reorder Level <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="reorder_level" name="reorder_level" 
                                       value="<?php echo $item['reorder_level']; ?>" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" 
                               value="<?php echo htmlspecialchars($item['supplier_name'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="supplier_contact" class="form-label">Supplier Contact</label>
                        <input type="text" class="form-control" id="supplier_contact" name="supplier_contact" 
                               value="<?php echo htmlspecialchars($item['supplier_contact'] ?? ''); ?>">
                    </div>
                    <div class="alert alert-info py-2">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            <strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($item['created_at'])); ?> |
                            <strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($item['updated_at'])); ?>
                        </small>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo Router::url('/shop/inventory'); ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
