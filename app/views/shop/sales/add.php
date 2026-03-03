<?php $pageTitle = 'Record Sale'; ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cart-plus"></i> Record New Sale</h2>
            <a href="<?php echo Router::url('/shop/sales'); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Sales</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo Router::url('/shop/sales/store'); ?>" method="POST" id="salesForm">
                    <div class="mb-3">
                        <label for="inventory_id" class="form-label">Select Product <span class="text-danger">*</span></label>
                        <select class="form-select" id="inventory_id" name="inventory_id" required onchange="updateProductInfo()">
                            <option value="">-- Select a product --</option>
                            <?php foreach ($items as $item): ?>
                                <option value="<?php echo $item['id']; ?>" 
                                        data-price="<?php echo $item['unit_price']; ?>"
                                        data-available="<?php echo $item['quantity']; ?>"
                                        data-name="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    <?php echo htmlspecialchars($item['product_name']); ?> - Available: <?php echo $item['quantity']; ?> - Price: ¥<?php echo number_format($item['unit_price'], 0); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div id="productInfo" class="alert alert-info d-none">
                        <h6>Product Information:</h6>
                        <div class="row">
                            <div class="col-md-4"><strong>Unit Price:</strong><br><span id="displayPrice">¥0</span></div>
                            <div class="col-md-4"><strong>Available Stock:</strong><br><span id="displayStock">0</span></div>
                            <div class="col-md-4"><strong>Total Amount:</strong><br><span id="displayTotal" class="text-success fw-bold">¥0</span></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity_sold" class="form-label">Quantity to Sell <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantity_sold" name="quantity_sold" value="1" min="1" required oninput="calculateTotal()">
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name (Optional)</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Leave empty for walk-in customers">
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?php echo Router::url('/shop/sales'); ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Record Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateProductInfo() {
    const select = document.getElementById('inventory_id');
    const opt = select.options[select.selectedIndex];
    const info = document.getElementById('productInfo');
    if (opt.value) {
        document.getElementById('displayPrice').textContent = '¥' + Math.round(parseFloat(opt.dataset.price)).toLocaleString();
        document.getElementById('displayStock').textContent = opt.dataset.available;
        document.getElementById('quantity_sold').max = opt.dataset.available;
        info.classList.remove('d-none');
        calculateTotal();
    } else { info.classList.add('d-none'); }
}
function calculateTotal() {
    const select = document.getElementById('inventory_id');
    const opt = select.options[select.selectedIndex];
    const qtyInput = document.getElementById('quantity_sold');
    if (opt.value && qtyInput.value) {
        const price = parseFloat(opt.dataset.price);
        let qty = parseInt(qtyInput.value);
        const avail = parseInt(opt.dataset.available);
        if (qty > avail) { qtyInput.value = avail; qty = avail; alert('Quantity cannot exceed available stock (' + avail + ')'); }
        document.getElementById('displayTotal').textContent = '¥' + Math.round(price * qty).toLocaleString();
    }
}
</script>
