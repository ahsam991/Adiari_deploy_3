<?php
/**
 * Manager Product List
 */
$products = $data['products'];
$currentPage = $data['currentPage'];
$totalPages = $data['totalPages'];
$search = $data['search'];
?>

<div class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar (Reused) -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6">
                <h2 class="text-2xl font-bold tracking-tight">Manager Panel</h2>
                <p class="text-xs text-gray-400 mt-1">ADI ARI Fresh</p>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="/manager" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                    <span class="material-symbols-outlined mr-3">dashboard</span>
                    Dashboard
                </a>
                <a href="/manager/products" class="flex items-center px-4 py-3 bg-gray-900 text-white rounded-lg">
                    <span class="material-symbols-outlined mr-3">inventory_2</span>
                    Products
                </a>
                <a href="/manager/categories" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                    <span class="material-symbols-outlined mr-3">category</span>
                    Categories
                </a>
                <a href="/manager/orders" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                    <span class="material-symbols-outlined mr-3">shopping_bag</span>
                    Orders
                </a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 hover:text-red-300 rounded-lg mt-8 transition">
                    <span class="material-symbols-outlined mr-3">logout</span>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto flex flex-col">
            <header class="bg-white shadow-sm p-6 flex justify-between items-center z-10">
                <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden'); document.getElementById('importModal').classList.add('flex');" class="flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-lg transform hover:scale-105">
                        <span class="material-symbols-outlined mr-2">upload_file</span>
                        Import Excel
                    </button>
                    <a href="/manager/product/create" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition shadow-lg transform hover:scale-105">
                        <span class="material-symbols-outlined mr-2">add_circle</span>
                        New Product
                    </a>
                </div>
            </header>

            <main class="flex-1 p-8">
                <?php if (Session::hasFlash('success')): ?>
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg flex items-center shadow-sm">
                        <span class="material-symbols-outlined text-green-500 mr-2">check_circle</span>
                        <p class="text-green-700 font-medium"><?= Session::getFlash('success') ?></p>
                    </div>
                <?php endif; ?>

                <?php if (Session::hasFlash('error')): ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center shadow-sm">
                        <span class="material-symbols-outlined text-red-500 mr-2">error</span>
                        <p class="text-red-700 font-medium"><?= Session::getFlash('error') ?></p>
                    </div>
                <?php endif; ?>

                <!-- Toolbar -->
                <div class="bg-white rounded-t-xl shadow-sm p-4 border-b border-gray-100 flex justify-between items-center">
                    <form action="/manager/products" method="GET" class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <span class="material-symbols-outlined">search</span>
                        </span>
                        <input 
                            type="text" 
                            name="q" 
                            value="<?= htmlspecialchars($search) ?>" 
                            placeholder="Search products..." 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 w-64 text-sm"
                        >
                    </form>
                    <div class="text-sm text-gray-500">
                        Showing page <?= $currentPage ?> of <?= $totalPages ?>
                    </div>
                </div>

                <!-- Product Table -->
                <div class="bg-white rounded-b-xl shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No products found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                                                        <img src="<?= htmlspecialchars($this->productImage($product['primary_image'] ?? null)) ?>" alt="" class="h-10 w-10 object-cover"
                                                             onerror="this.onerror=null;this.src='https://placehold.co/40x40?text=IMG';">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></div>
                                                    <div class="text-xs text-gray-500">SKU: <?= htmlspecialchars($product['sku']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?= htmlspecialchars($product['category_name']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ¥<?= number_format($product['price']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($product['stock_quantity'] <= 5): ?>
                                                <span class="text-red-600 font-bold"><?= $product['stock_quantity'] ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-900"><?= $product['stock_quantity'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($product['status'] === 'active'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800"><?= ucfirst($product['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/manager/product/<?= $product['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit">
                                                <span class="material-symbols-outlined text-lg">edit</span>
                                            </a>
                                            <form action="/manager/product/<?= $product['id'] ?>/delete" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <?= Security::getCsrfField() ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-6 flex justify-center w-full">
                        <nav class="flex items-center space-x-1 sm:space-x-2">
                            <!-- Previous Button -->
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?>&q=<?= urlencode($search) ?>" class="px-3 py-2 border rounded-md text-sm font-medium bg-white border-gray-300 text-gray-700 hover:bg-gray-50 flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">chevron_left</span> Prev
                                </a>
                            <?php else: ?>
                                <span class="px-3 py-2 border rounded-md text-sm font-medium bg-gray-50 border-gray-200 text-gray-400 cursor-not-allowed flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">chevron_left</span> Prev
                                </span>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php 
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            
                            // Adjust if we are near the ends
                            if ($endPage - $startPage < 4) {
                                if ($startPage == 1) {
                                    $endPage = min($totalPages, 5);
                                } elseif ($endPage == $totalPages) {
                                    $startPage = max(1, $totalPages - 4);
                                }
                            }
                            ?>
                            
                            <?php if ($startPage > 1): ?>
                                <a href="?page=1&q=<?= urlencode($search) ?>" class="hidden sm:inline-flex px-3 py-2 border rounded-md text-sm font-medium bg-white border-gray-300 text-gray-700 hover:bg-gray-50">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="hidden sm:inline-flex px-2 py-2 text-gray-500">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>" class="px-3 py-2 border rounded-md text-sm font-medium <?= $i == $currentPage ? 'bg-green-600 text-white border-green-600 shadow-sm' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?>
                                    <span class="hidden sm:inline-flex px-2 py-2 text-gray-500">...</span>
                                <?php endif; ?>
                                <a href="?page=<?= $totalPages ?>&q=<?= urlencode($search) ?>" class="hidden sm:inline-flex px-3 py-2 border rounded-md text-sm font-medium bg-white border-gray-300 text-gray-700 hover:bg-gray-50"><?= $totalPages ?></a>
                            <?php endif; ?>

                            <!-- Next Button -->
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?= $currentPage + 1 ?>&q=<?= urlencode($search) ?>" class="px-3 py-2 border rounded-md text-sm font-medium bg-white border-gray-300 text-gray-700 hover:bg-gray-50 flex items-center">
                                    Next <span class="material-symbols-outlined text-sm ml-1">chevron_right</span>
                                </a>
                            <?php else: ?>
                                <span class="px-3 py-2 border rounded-md text-sm font-medium bg-gray-50 border-gray-200 text-gray-400 cursor-not-allowed flex items-center">
                                    Next <span class="material-symbols-outlined text-sm ml-1">chevron_right</span>
                                </span>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<!-- Import Excel Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-2xl">
            <div>
                <h3 class="text-xl font-bold">Import Products from Excel</h3>
                <p class="text-blue-100 text-sm mt-1">Upload .xlsx, .xls, or .csv files</p>
            </div>
            <button onclick="closeImportModal()" class="text-white/80 hover:text-white transition">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1">
            <!-- Step 1: Upload -->
            <div id="importStep1">
                <div class="mb-6">
                    <h4 class="font-bold text-gray-900 mb-2">📋 Required Columns</h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Name *</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">Price *</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">SKU</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Category</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Stock</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Description</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Sale Price</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Unit</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Halal (yes/no)</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Organic (yes/no)</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">Featured (yes/no)</span>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="mb-6">
                    <button onclick="downloadTemplate()" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                        <span class="material-symbols-outlined mr-1 text-lg">download</span>
                        Download Excel Template
                    </button>
                </div>

                <!-- Drop Zone -->
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:border-blue-500 transition-all cursor-pointer bg-gray-50 hover:bg-blue-50/30"
                     onclick="document.getElementById('excelFile').click()"
                     ondragover="event.preventDefault(); this.classList.add('border-blue-500','bg-blue-50')"
                     ondragleave="this.classList.remove('border-blue-500','bg-blue-50')"
                     ondrop="event.preventDefault(); this.classList.remove('border-blue-500','bg-blue-50'); handleFileDrop(event)">
                    <span class="material-symbols-outlined text-5xl text-gray-400 mb-3">cloud_upload</span>
                    <p class="text-lg font-bold text-gray-700 mb-1">Drag & drop your Excel file here</p>
                    <p class="text-sm text-gray-500 mb-4">or click to browse</p>
                    <span class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">
                        <span class="material-symbols-outlined mr-2 text-lg">folder_open</span>
                        Choose File
                    </span>
                    <input type="file" id="excelFile" accept=".xlsx,.xls,.csv" class="hidden" onchange="handleFileSelect(this)">
                </div>

                <div id="fileName" class="mt-3 text-sm text-gray-600 hidden">
                    <span class="material-symbols-outlined text-green-500 align-middle mr-1">check_circle</span>
                    <span id="fileNameText"></span>
                </div>
            </div>

            <!-- Step 2: Preview -->
            <div id="importStep2" class="hidden">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="font-bold text-gray-900">📊 Data Preview</h4>
                        <p class="text-sm text-gray-500" id="rowCount"></p>
                    </div>
                    <button onclick="resetImport()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-lg">refresh</span>
                        Choose Different File
                    </button>
                </div>

                <div class="border rounded-xl overflow-hidden">
                    <div class="overflow-x-auto max-h-[350px] overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="previewTable">
                            <thead class="bg-gray-50 sticky top-0 z-10" id="previewHead"></thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="previewBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-between items-center rounded-b-2xl">
            <button onclick="closeImportModal()" class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                Cancel
            </button>
            <form id="importForm" method="POST" action="/manager/products/import">
                <?= Security::getCsrfField() ?>
                <input type="hidden" name="import_data" id="importDataField" value="">
                <button type="submit" id="importSubmitBtn" disabled class="px-8 py-2.5 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">publish</span>
                    <span id="importBtnText">Import Products</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- SheetJS Library for Excel parsing -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

<script>
let parsedData = [];

function handleFileDrop(event) {
    const files = event.dataTransfer.files;
    if (files.length > 0) processFile(files[0]);
}

function handleFileSelect(input) {
    if (input.files.length > 0) processFile(input.files[0]);
}

function processFile(file) {
    const validTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-excel', // .xls
        'text/csv',
        'application/csv'
    ];
    const ext = file.name.split('.').pop().toLowerCase();
    if (!['xlsx', 'xls', 'csv'].includes(ext)) {
        alert('Please upload an Excel (.xlsx, .xls) or CSV file.');
        return;
    }

    // Show file name
    document.getElementById('fileName').classList.remove('hidden');
    document.getElementById('fileNameText').textContent = file.name + ' (' + formatFileSize(file.size) + ')';

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheet = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheet];
            const jsonData = XLSX.utils.sheet_to_json(worksheet, { defval: '' });

            if (jsonData.length === 0) {
                alert('No data found in the file. Make sure the first row contains column headers.');
                return;
            }

            parsedData = jsonData;
            showPreview(jsonData);
        } catch (err) {
            alert('Error reading file: ' + err.message);
        }
    };
    reader.readAsArrayBuffer(file);
}

function showPreview(data) {
    document.getElementById('importStep1').classList.add('hidden');
    document.getElementById('importStep2').classList.remove('hidden');
    document.getElementById('rowCount').textContent = data.length + ' product(s) found';

    // Build preview table
    const headers = Object.keys(data[0]);
    const thead = document.getElementById('previewHead');
    const tbody = document.getElementById('previewBody');

    thead.innerHTML = '<tr>' + headers.map(h =>
        `<th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider whitespace-nowrap">${escapeHtml(h)}</th>`
    ).join('') + '</tr>';

    // Show max 50 rows for preview
    const previewRows = data.slice(0, 50);
    tbody.innerHTML = previewRows.map((row, idx) =>
        '<tr class="' + (idx % 2 === 0 ? 'bg-white' : 'bg-gray-50') + '">' +
        headers.map(h =>
            `<td class="px-4 py-2 text-sm text-gray-700 whitespace-nowrap max-w-[200px] truncate">${escapeHtml(String(row[h] ?? ''))}</td>`
        ).join('') +
        '</tr>'
    ).join('');

    if (data.length > 50) {
        tbody.innerHTML += `<tr><td colspan="${headers.length}" class="px-4 py-3 text-center text-sm text-gray-500 italic bg-yellow-50">... and ${data.length - 50} more rows (not shown in preview)</td></tr>`;
    }

    // Enable submit button
    document.getElementById('importDataField').value = JSON.stringify(parsedData);
    document.getElementById('importSubmitBtn').disabled = false;
    document.getElementById('importBtnText').textContent = `Import ${data.length} Product(s)`;
}

function resetImport() {
    parsedData = [];
    document.getElementById('importStep1').classList.remove('hidden');
    document.getElementById('importStep2').classList.add('hidden');
    document.getElementById('fileName').classList.add('hidden');
    document.getElementById('excelFile').value = '';
    document.getElementById('importDataField').value = '';
    document.getElementById('importSubmitBtn').disabled = true;
    document.getElementById('importBtnText').textContent = 'Import Products';
}

function closeImportModal() {
    document.getElementById('importModal').classList.remove('flex');
    document.getElementById('importModal').classList.add('hidden');
    resetImport();
}

function downloadTemplate() {
    const templateData = [
        { 'Name': 'Sample Product', 'SKU': 'SKU-001', 'Category': 'Vegetables', 'Price': 100, 'Stock': 50, 'Description': 'Fresh organic vegetable', 'Sale Price': '', 'Unit': 'kg', 'Halal': 'yes', 'Organic': 'yes', 'Featured': 'no' }
    ];
    const ws = XLSX.utils.json_to_sheet(templateData);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Products');

    // Set column widths
    ws['!cols'] = [
        { wch: 25 }, { wch: 12 }, { wch: 15 }, { wch: 10 },
        { wch: 10 }, { wch: 35 }, { wch: 12 }, { wch: 8 },
        { wch: 8 }, { wch: 10 }, { wch: 10 }
    ];

    XLSX.writeFile(wb, 'product_import_template.xlsx');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / 1048576).toFixed(1) + ' MB';
}

// Close modal on escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImportModal();
});

// Close modal on backdrop click
document.getElementById('importModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeImportModal();
});

// Show loading on submit
document.getElementById('importForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('importSubmitBtn');
    btn.disabled = true;
    document.getElementById('importBtnText').textContent = 'Importing...';
});
</script>
