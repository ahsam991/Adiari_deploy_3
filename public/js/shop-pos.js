/**
 * POS System JavaScript (Adapted for merged project)
 * Uses clean URL routing instead of query-string routing
 */

// Get base URL from document
function getBaseUrl() {
    const scripts = document.querySelectorAll('script[src*="shop-pos"]');
    if (scripts.length > 0) {
        const src = scripts[0].getAttribute('src');
        return src.replace('/js/shop-pos.js', '');
    }
    return '';
}

const BASE_URL = getBaseUrl();

// Cart state
let cart = [];
let subtotal = 0;
let tax = 0;
let discount = 0;
let total = 0;
let activeCoupon = null;
let searchResults = [];
let selectedCustomer = null;
const VAT_RATE = 10;

// Initialize POS
document.addEventListener('DOMContentLoaded', function () {
    const barcodeInput = document.getElementById('barcodeInput');
    const manualSearchBtn = document.getElementById('manualSearchBtn');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const clearCartBtn = document.getElementById('clearCartBtn');
    const discountInput = document.getElementById('discountInput');
    const discountType = document.getElementById('discountType');
    const amountPaidInput = document.getElementById('amountPaid');
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    const couponCodeInput = document.getElementById('couponCode');
    const addCustomerBtn = document.getElementById('addCustomerBtn');
    const saveCustomerBtn = document.getElementById('saveCustomerBtn');

    if (barcodeInput) {
        barcodeInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); searchProduct(); }
        });
        barcodeInput.focus();
    }
    if (manualSearchBtn) manualSearchBtn.addEventListener('click', searchProduct);
    if (checkoutBtn) checkoutBtn.addEventListener('click', processCheckout);
    if (clearCartBtn) clearCartBtn.addEventListener('click', confirmClearCart);
    if (discountInput) discountInput.addEventListener('input', updateTotals);
    if (discountType) discountType.addEventListener('change', updateTotals);
    if (amountPaidInput) amountPaidInput.addEventListener('input', calculateChange);
    if (applyCouponBtn) applyCouponBtn.addEventListener('click', validateCoupon);
    if (couponCodeInput) {
        couponCodeInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); validateCoupon(); }
        });
    }
    if (addCustomerBtn) {
        addCustomerBtn.addEventListener('click', function () {
            const modal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
            modal.show();
        });
    }
    if (saveCustomerBtn) saveCustomerBtn.addEventListener('click', saveNewCustomer);

    const customerSelect = document.getElementById('customerSelect');
    if (customerSelect) {
        customerSelect.addEventListener('change', function () {
            if (this.value) { fetchCustomerDetails(this.value); }
            else { selectedCustomer = null; document.getElementById('customerInfoDisplay').style.display = 'none'; }
        });
    }
});

function fetchCustomerDetails(customerId) {
    fetch(BASE_URL + '/shop/pos/get-customer?id=' + customerId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectedCustomer = data.customer;
                document.getElementById('custName').textContent = selectedCustomer.customer_name;
                document.getElementById('custPhone').textContent = selectedCustomer.phone;
                document.getElementById('custAddress').textContent = selectedCustomer.address || 'N/A';
                document.getElementById('custPoints').textContent = selectedCustomer.loyalty_points || 0;
                document.getElementById('customerInfoDisplay').style.display = 'block';
            } else { selectedCustomer = null; }
        })
        .catch(() => { selectedCustomer = null; });
}

function formatCurrency(amount) {
    return '¥' + Math.round(amount).toLocaleString();
}

function searchProduct() {
    const barcodeInput = document.getElementById('barcodeInput');
    const term = barcodeInput.value.trim();
    if (!term) { showNotification('Please enter a barcode or product name', 'warning'); return; }
    barcodeInput.disabled = true;

    fetch(BASE_URL + '/shop/pos/search', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'term=' + encodeURIComponent(term)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.multiple) {
                searchResults = data.products;
                const resultContainer = document.getElementById('productSearchResults');
                let html = '';
                searchResults.forEach((product, index) => {
                    html += `<button type="button" class="list-group-item list-group-item-action" onclick="selectProduct(${index})">
                        <div class="d-flex w-100 justify-content-between"><h5 class="mb-1">${product.product_name}</h5><small>${formatCurrency(product.unit_price)}</small></div>
                        <p class="mb-1">Category: ${product.category}</p>
                        <small>Barcode: ${product.barcode} | Stock: ${product.quantity}</small>
                    </button>`;
                });
                resultContainer.innerHTML = html;
                new bootstrap.Modal(document.getElementById('productSelectionModal')).show();
            } else {
                addToCart(data.product);
                barcodeInput.value = '';
                showNotification('Product added to cart', 'success');
            }
        } else { showNotification(data.message, 'error'); }
        barcodeInput.disabled = false;
        barcodeInput.focus();
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error searching product', 'error');
        barcodeInput.disabled = false;
        barcodeInput.focus();
    });
}

function selectProduct(index) {
    if (searchResults[index]) {
        addToCart(searchResults[index]);
        document.getElementById('barcodeInput').value = '';
        bootstrap.Modal.getInstance(document.getElementById('productSelectionModal')).hide();
        showNotification('Product added to cart', 'success');
        document.getElementById('barcodeInput').focus();
    }
}

function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    if (existingItem) {
        if (existingItem.quantity < product.quantity) {
            existingItem.quantity++;
            existingItem.line_total = existingItem.quantity * existingItem.unit_price;
        } else { showNotification('Not enough stock available', 'warning'); return; }
    } else {
        cart.push({
            id: product.id,
            product_name: product.product_name,
            barcode: product.barcode,
            unit_price: parseFloat(product.unit_price),
            quantity: 1,
            current_stock: parseInt(product.quantity),
            line_total: parseFloat(product.unit_price)
        });
    }
    renderCart();
    updateTotals();
}

function removeFromCart(index) { cart.splice(index, 1); renderCart(); updateTotals(); }

function updateQuantity(index, newQuantity) {
    const item = cart[index];
    newQuantity = parseInt(newQuantity);
    if (newQuantity < 1) { removeFromCart(index); return; }
    if (newQuantity > item.current_stock) { showNotification('Not enough stock available', 'warning'); renderCart(); return; }
    item.quantity = newQuantity;
    item.line_total = item.quantity * item.unit_price;
    renderCart();
    updateTotals();
}

function renderCart() {
    const cartItems = document.getElementById('cartItems');
    if (cart.length === 0) {
        if (activeCoupon) { activeCoupon = null; document.getElementById('couponCode').value = ''; document.getElementById('couponMessage').style.display = 'none'; }
        cartItems.innerHTML = `<tr id="emptyCartRow"><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-cart-x" style="font-size: 3rem;"></i><p class="mt-2">Cart is empty. Scan a product to begin.</p></td></tr>`;
        return;
    }
    let html = '';
    cart.forEach((item, index) => {
        html += `<tr>
            <td><strong>${item.product_name}</strong></td>
            <td><code>${item.barcode}</code></td>
            <td>${formatCurrency(item.unit_price)}</td>
            <td><input type="number" class="form-control form-control-sm" style="width: 70px;" value="${item.quantity}" min="1" max="${item.current_stock}" onchange="updateQuantity(${index}, this.value)"></td>
            <td><strong>${formatCurrency(item.line_total)}</strong></td>
            <td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})"><i class="bi bi-trash"></i></button></td>
        </tr>`;
    });
    cartItems.innerHTML = html;
}

function validateCoupon() {
    const code = document.getElementById('couponCode').value.trim();
    const messageEl = document.getElementById('couponMessage');
    if (!code) { showNotification('Please enter a coupon code', 'warning'); return; }
    if (cart.length === 0) { showNotification('Add items to cart before applying coupon', 'warning'); return; }
    const currentSubtotal = cart.reduce((sum, item) => sum + item.line_total, 0);

    fetch(BASE_URL + '/shop/pos/validate-coupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `code=${encodeURIComponent(code)}&total=${currentSubtotal}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            activeCoupon = data;
            messageEl.textContent = `${data.message} (${data.coupon.description})`;
            messageEl.className = 'form-text text-success';
            messageEl.style.display = 'block';
            showNotification('Coupon applied successfully', 'success');
            document.getElementById('discountInput').disabled = true;
            document.getElementById('discountInput').value = 0;
            updateTotals();
        } else {
            activeCoupon = null;
            messageEl.textContent = data.message;
            messageEl.className = 'form-text text-danger';
            messageEl.style.display = 'block';
            updateTotals();
        }
    })
    .catch(error => { console.error('Error:', error); showNotification('Error validating coupon', 'error'); });
}

function updateTotals() {
    subtotal = cart.reduce((sum, item) => sum + item.line_total, 0);
    tax = Math.round(subtotal * (VAT_RATE / 100));
    discount = 0;

    if (activeCoupon) {
        if (activeCoupon.coupon.discount_type === 'percentage') {
            discount = Math.round((subtotal * activeCoupon.coupon.discount_value) / 100);
            if (activeCoupon.coupon.max_discount_amount && discount > activeCoupon.coupon.max_discount_amount) discount = parseFloat(activeCoupon.coupon.max_discount_amount);
        } else { discount = parseFloat(activeCoupon.coupon.discount_value); }
    } else {
        const discountValue = parseFloat(document.getElementById('discountInput').value) || 0;
        const discountTypeEl = document.getElementById('discountType');
        discount = discountTypeEl.value === 'percentage' ? Math.round((subtotal * discountValue) / 100) : discountValue;
        if (discount > subtotal) discount = subtotal;
    }

    total = subtotal + tax - discount;
    if (total < 0) total = 0;

    document.getElementById('subtotalDisplay').textContent = formatCurrency(subtotal);
    document.getElementById('taxDisplay').textContent = formatCurrency(tax);
    document.getElementById('totalDisplay').textContent = formatCurrency(total);

    const amountPaidInput = document.getElementById('amountPaid');
    if (parseFloat(amountPaidInput.value) === 0 || !amountPaidInput.value) amountPaidInput.value = Math.ceil(total);

    calculateChange();
    document.getElementById('checkoutBtn').disabled = cart.length === 0;
}

function calculateChange() {
    const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
    const change = amountPaid - total;
    const changeDisplay = document.getElementById('changeDisplay');

    if (amountPaid > 0) {
        changeDisplay.style.display = 'block';
        if (change >= 0) {
            changeDisplay.classList.remove('alert-danger'); changeDisplay.classList.add('alert-info');
            changeDisplay.innerHTML = `<strong>Change:</strong> <span id="changeAmount">${formatCurrency(change)}</span>`;
        } else {
            changeDisplay.classList.remove('alert-info'); changeDisplay.classList.add('alert-danger');
            changeDisplay.innerHTML = `<strong>Due:</strong> <span id="changeAmount">${formatCurrency(Math.abs(change))}</span>`;
        }
    } else { changeDisplay.style.display = 'none'; }
}

function processCheckout() {
    const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;
    if (amountPaid < total) { showNotification('Amount paid is less than total', 'error'); document.getElementById('amountPaid').focus(); return; }
    if (cart.length === 0) { showNotification('Cart is empty', 'warning'); return; }

    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn.disabled = true;
    checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processing...';

    const checkoutData = {
        items: cart,
        subtotal: subtotal, tax: tax, discount: discount, total: total,
        amountPaid: amountPaid, change: amountPaid - total,
        customerId: document.getElementById('customerSelect').value || null,
        customerName: selectedCustomer ? selectedCustomer.customer_name : null,
        customerPhone: selectedCustomer ? selectedCustomer.phone : null,
        couponId: activeCoupon ? activeCoupon.coupon.coupon_id : null,
        paymentMethod: document.getElementById('paymentMethod').value
    };

    fetch(BASE_URL + '/shop/pos/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(checkoutData)
    })
    .then(response => {
        if (!response.ok) throw new Error('Server error: ' + response.status);
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) throw new Error('Session may have expired. Please refresh.');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('invoiceNumberDisplay').textContent = data.invoice_number;
            document.getElementById('modalChangeAmount').textContent = formatCurrency(amountPaid - total);
            document.getElementById('printInvoiceBtn').onclick = function () {
                window.open(BASE_URL + '/shop/pos/invoice/' + data.invoice_id, '_blank');
            };
            new bootstrap.Modal(document.getElementById('successModal')).show();

            cart = []; activeCoupon = null; selectedCustomer = null;
            document.getElementById('customerSelect').value = '';
            document.getElementById('customerInfoDisplay').style.display = 'none';
            renderCart();
            document.getElementById('discountInput').value = 0;
            document.getElementById('discountInput').disabled = false;
            document.getElementById('couponCode').value = '';
            document.getElementById('couponMessage').style.display = 'none';
            document.getElementById('amountPaid').value = 0;
            updateTotals();
            showNotification('Sale completed successfully!', 'success');
        } else { showNotification(data.message, 'error'); }
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = '<i class="bi bi-check-circle"></i> Complete Sale';
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Error processing checkout', 'error');
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = '<i class="bi bi-check-circle"></i> Complete Sale';
    });
}

function saveNewCustomer() {
    const name = document.getElementById('newCustomerName').value.trim();
    const phone = document.getElementById('newCustomerPhone').value.trim();
    if (!name || !phone) { showNotification('Name and Phone are required', 'warning'); return; }

    const saveBtn = document.getElementById('saveCustomerBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

    fetch(BASE_URL + '/shop/pos/add-customer', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name: name, phone: phone,
            email: document.getElementById('newCustomerEmail').value.trim(),
            address: document.getElementById('newCustomerAddress').value.trim()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const select = document.getElementById('customerSelect');
            const option = document.createElement('option');
            option.value = data.customer.id;
            option.text = `${data.customer.customer_name} (${data.customer.phone})`;
            option.selected = true;
            select.add(option);
            fetchCustomerDetails(data.customer.id);
            bootstrap.Modal.getInstance(document.getElementById('addCustomerModal')).hide();
            document.getElementById('addCustomerForm').reset();
            showNotification('Customer added successfully', 'success');
        } else { showNotification(data.message, 'error'); }
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error saving customer', 'error');
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    });
}

function confirmClearCart() {
    if (cart.length === 0) return;
    if (confirm('Are you sure you want to clear the cart?')) {
        cart = []; subtotal = 0; tax = 0; discount = 0; total = 0; activeCoupon = null;
        document.getElementById('discountInput').value = 0;
        document.getElementById('discountInput').disabled = false;
        document.getElementById('amountPaid').value = 0;
        document.getElementById('couponCode').value = '';
        document.getElementById('couponMessage').style.display = 'none';
        document.getElementById('customerSelect').value = '';
        selectedCustomer = null;
        renderCart(); updateTotals();
        document.getElementById('barcodeInput').focus();
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    let icon = 'info-circle';
    if (type === 'success') icon = 'check-circle';
    if (type === 'error') icon = 'exclamation-circle';
    if (type === 'warning') icon = 'exclamation-triangle';
    notification.innerHTML = `<i class="bi bi-${icon} me-2"></i> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(notification);
    setTimeout(() => { notification.remove(); }, 3000);
}
