<?php $pageTitle = 'Point of Sale (POS)'; ?>

<div class="row mb-3">
    <div class="col-12"><h2><i class="bi bi-cart-check"></i> Point of Sale</h2></div>
</div>

<div class="row">
    <!-- Left Side: Product Scanner & Cart -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white"><i class="bi bi-upc-scan"></i> Barcode Scanner</div>
            <div class="card-body">
                <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" id="barcodeInput" class="form-control form-control-lg" placeholder="Scan barcode or enter product name..." autofocus>
                    <button class="btn btn-primary" type="button" onclick="startGlobalScanner('barcodeInput')"><i class="bi bi-camera"></i></button>
                    <button class="btn btn-secondary" type="button" id="manualSearchBtn"><i class="bi bi-search"></i> Search</button>
                </div>
                <small class="text-muted"><i class="bi bi-info-circle"></i> Focus on this field and scan barcode, or search by product name</small>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cart3"></i> Shopping Cart</span>
                <button class="btn btn-sm btn-light" id="clearCartBtn"><i class="bi bi-trash"></i> Clear Cart</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="cartTable">
                        <thead class="table-light"><tr><th>Product</th><th>Barcode</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr></thead>
                        <tbody id="cartItems">
                            <tr id="emptyCartRow"><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-cart-x" style="font-size: 3rem;"></i><p class="mt-2">Cart is empty. Scan a product to begin.</p></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Checkout Panel -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-info text-white"><i class="bi bi-calculator"></i> Checkout</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-people"></i> Customer</label>
                    <div class="input-group">
                        <select class="form-select" id="customerSelect">
                            <option value="">Select Customer (Optional)</option>
                            <?php if (isset($customers)): ?>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo $customer['id']; ?>"><?php echo htmlspecialchars($customer['customer_name']); ?> (<?php echo $customer['phone']; ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button class="btn btn-outline-secondary" type="button" id="addCustomerBtn"><i class="bi bi-person-plus"></i></button>
                    </div>
                    <div id="customerInfoDisplay" class="card mt-2 border-info" style="display: none;">
                        <div class="card-body p-2 small">
                            <div class="d-flex justify-content-between">
                                <span><strong><i class="bi bi-person"></i></strong> <span id="custName"></span></span>
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> <span id="custPoints">0</span> pts</span>
                            </div>
                            <div><strong><i class="bi bi-telephone"></i></strong> <span id="custPhone"></span></div>
                            <div class="text-truncate"><strong><i class="bi bi-geo-alt"></i></strong> <span id="custAddress"></span></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2"><span>Subtotal:</span><strong id="subtotalDisplay">¥0</strong></div>
                <div class="d-flex justify-content-between mb-2"><span>Tax (10%):</span><strong id="taxDisplay">¥0</strong></div>
                <div class="mb-2">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="couponCode" placeholder="Coupon Code">
                        <button class="btn btn-outline-secondary" type="button" id="applyCouponBtn">Apply</button>
                    </div>
                    <div id="couponMessage" class="form-text text-success" style="display:none;"></div>
                </div>
                <div class="d-flex justify-content-between mb-2 align-items-center">
                    <span>Discount:</span>
                    <div class="input-group input-group-sm" style="width: 140px;">
                        <input type="number" class="form-control" id="discountInput" value="0" min="0">
                        <select class="form-select" id="discountType" style="max-width: 60px;"><option value="fixed">¥</option><option value="percentage">%</option></select>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3"><h5>Total:</h5><h5 class="text-success" id="totalDisplay">¥0</h5></div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod"><option value="Cash">Cash</option><option value="Card">Card</option><option value="Mobile">Mobile Payment</option></select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount Paid</label>
                    <div class="input-group"><span class="input-group-text">¥</span><input type="number" class="form-control" id="amountPaid" value="0" min="0"></div>
                </div>
                <div class="alert alert-info mb-3" id="changeDisplay" style="display: none;"><strong>Change:</strong> <span id="changeAmount">¥0</span></div>
                <button class="btn btn-success btn-lg w-100" id="checkoutBtn" disabled><i class="bi bi-check-circle"></i> Complete Sale</button>
                <button class="btn btn-outline-secondary btn-sm w-100 mt-2" onclick="window.location.href='<?php echo Router::url('/shop/pos/invoices'); ?>'"><i class="bi bi-receipt"></i> View Invoices</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Add New Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCustomerForm">
                    <div class="mb-3"><label for="newCustomerName" class="form-label">Customer Name *</label><input type="text" class="form-control" id="newCustomerName" required></div>
                    <div class="mb-3"><label for="newCustomerPhone" class="form-label">Phone Number *</label><input type="text" class="form-control" id="newCustomerPhone" required></div>
                    <div class="mb-3"><label for="newCustomerEmail" class="form-label">Email (Optional)</label><input type="email" class="form-control" id="newCustomerEmail"></div>
                    <div class="mb-3"><label for="newCustomerAddress" class="form-label">Address (Optional)</label><textarea class="form-control" id="newCustomerAddress" rows="2"></textarea></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCustomerBtn">Save Customer</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white"><h5 class="modal-title"><i class="bi bi-check-circle"></i> Sale Completed</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Payment Successful!</h4>
                <p>Invoice Number: <strong id="invoiceNumberDisplay"></strong></p>
                <p class="text-muted">Change: <strong id="modalChangeAmount"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="printInvoiceBtn"><i class="bi bi-printer"></i> Print Invoice</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productSelectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"><h5 class="modal-title"><i class="bi bi-search"></i> Select Product</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><p>Multiple matches found. Please select a product:</p><div class="list-group" id="productSearchResults"></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>

<script src="<?php echo Router::url('/js/shop-pos.js'); ?>"></script>

<style>
#barcodeInput { font-size: 1.2rem; font-weight: bold; }
.sticky-top { position: sticky; }
@media print { body * { visibility: hidden; } .invoice-print, .invoice-print * { visibility: visible; } .invoice-print { position: absolute; left: 0; top: 0; } }
</style>
