<?php $pageTitle = 'Add New Item'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-plus-circle"></i> Add New Inventory Item</h2>
            <a href="<?php echo Router::url('/shop/inventory'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Inventory</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo Router::url('/shop/inventory/store'); ?>" method="POST">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category" name="category" placeholder="e.g., Dairy, Vegetables, Beverages" required>
                    </div>
                    <div class="mb-3">
                        <label for="barcode" class="form-label"><i class="bi bi-upc-scan"></i> Barcode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Scan or enter barcode">
                            <button class="btn btn-outline-primary" type="button" onclick="startGlobalScanner('barcode')"><i class="bi bi-camera"></i> Scan</button>
                            <button class="btn btn-outline-secondary" type="button" onclick="generateBarcode()"><i class="bi bi-magic"></i> Auto-Generate</button>
                        </div>
                        <small class="form-text text-muted">Leave empty to auto-generate</small>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unit_price" class="form-label">Unit Price (¥) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="unit_price" name="unit_price" value="0" step="1" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="reorder_level" class="form-label">Reorder Level <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="reorder_level" name="reorder_level" value="10" min="0" required>
                                <small class="form-text text-muted">Alert when stock falls below this level</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name">
                    </div>
                    <div class="mb-3">
                        <label for="supplier_contact" class="form-label">Supplier Contact</label>
                        <input type="text" class="form-control" id="supplier_contact" name="supplier_contact" placeholder="Phone or email">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo Router::url('/shop/inventory'); ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function generateBarcode() {
    const timestamp = Date.now().toString().slice(-10);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    document.getElementById('barcode').value = '2' + timestamp.slice(0, 9) + random;
}
</script>
