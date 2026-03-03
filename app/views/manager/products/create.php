<?php
/**
 * Create Product Form
 */
$categories = $data['categories'];
$old = Session::getFlash('old') ?? [];
$errors = Session::getFlash('errors') ?? [];
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
                <div class="flex items-center">
                    <a href="/manager/products" class="mr-4 text-gray-500 hover:text-gray-700">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
                </div>
            </header>

            <main class="flex-1 p-8">
                <?php if (Session::hasFlash('error')): ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center shadow-sm">
                        <span class="material-symbols-outlined text-red-500 mr-2">error</span>
                        <p class="text-red-700 font-medium"><?= Session::getFlash('error') ?></p>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <form action="/manager/product/create" method="POST" enctype="multipart/form-data" class="p-8">
                        <?= Security::getCsrfField() ?>
                        <!-- Basic Info -->
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Name -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition"
                                    required
                                >
                                <?php if (isset($errors['name'])): ?>
                                    <p class="mt-1 text-sm text-red-600"><?= $errors['name'][0] ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU (Stock Keeping Unit) <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="sku" 
                                    name="sku" 
                                    value="<?= htmlspecialchars($old['sku'] ?? '') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition"
                                    required
                                >
                                <?php if (isset($errors['sku'])): ?>
                                    <p class="mt-1 text-sm text-red-600"><?= $errors['sku'][0] ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                                <select 
                                    id="category_id" 
                                    name="category_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition"
                                    required
                                >
                                    <option value="" disabled <?= !isset($old['category_id']) ? 'selected' : '' ?>>Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= (isset($old['category_id']) && $old['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['category_id'])): ?>
                                    <p class="mt-1 text-sm text-red-600"><?= $errors['category_id'][0] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Pricing & Inventory -->
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2 pt-4">Pricing & Inventory</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (¥) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">¥</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        id="price" 
                                        name="price" 
                                        step="0.01"
                                        min="0"
                                        value="<?= htmlspecialchars($old['price'] ?? '') ?>"
                                        class="w-full pl-7 px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition"
                                        required
                                    >
                                </div>
                                <?php if (isset($errors['price'])): ?>
                                    <p class="mt-1 text-sm text-red-600"><?= $errors['price'][0] ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                                <input 
                                    type="number" 
                                    id="stock_quantity" 
                                    name="stock_quantity" 
                                    min="0"
                                    value="<?= htmlspecialchars($old['stock_quantity'] ?? '0') ?>"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition"
                                    required
                                >
                                <?php if (isset($errors['stock_quantity'])): ?>
                                    <p class="mt-1 text-sm text-red-600"><?= $errors['stock_quantity'][0] ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Details -->
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2 pt-4">Product Details</h3>
                        
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition"
                            ><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Images <span class="text-xs text-gray-400">(first image will be the primary image)</span></label>
                            <div id="dropZone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-500 transition cursor-pointer bg-gray-50" onclick="document.getElementById('product_images').click()">
                                <div class="space-y-1 text-center">
                                    <span class="material-symbols-outlined text-4xl text-gray-400">cloud_upload</span>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="product_images" class="relative cursor-pointer rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                            <span>Upload files</span>
                                            <input id="product_images" name="product_images[]" type="file" class="sr-only" accept="image/*" multiple onchange="handleFileSelect(this)">
                                        </label>
                                        <p class="pl-1">or drag and drop (multiple allowed)</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 5MB each. You can select files multiple times.</p>
                                </div>
                            </div>
                            <p id="fileCount" class="mt-2 text-sm text-green-600 font-medium hidden"></p>
                            <div id="imagePreviews" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4 hidden"></div>
                        </div>

                        <!-- Attributes -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h4 class="font-medium text-gray-900 mb-4">Attributes</h4>
                            <div class="space-y-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="is_featured" class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500" <?= isset($old['is_featured']) ? 'checked' : '' ?>>
                                    <span class="text-gray-700 font-medium">Featured Product</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="is_organic" class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500" <?= isset($old['is_organic']) ? 'checked' : '' ?>>
                                    <span class="text-gray-700 font-medium">Organic</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" name="is_halal" class="h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500" <?= isset($old['is_halal']) ? 'checked' : '' ?>>
                                    <span class="text-gray-700 font-medium">Halal Certified</span>
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="/manager/products" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700 transition shadow-lg transform hover:scale-105">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>

<script>
// Accumulated files list that persists across multiple file picker opens
let collectedFiles = [];

function handleFileSelect(input) {
    if (!input.files || input.files.length === 0) return;
    
    // Add newly selected files to our collection (avoid duplicates by name+size)
    Array.from(input.files).forEach(file => {
        const isDuplicate = collectedFiles.some(f => f.name === file.name && f.size === file.size);
        if (!isDuplicate) {
            collectedFiles.push(file);
        }
    });
    
    syncFilesToInput();
    renderPreviews();
}

function removeCollectedFile(index) {
    collectedFiles.splice(index, 1);
    syncFilesToInput();
    renderPreviews();
}

function syncFilesToInput() {
    // Rebuild the file input's FileList using DataTransfer
    const dt = new DataTransfer();
    collectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('product_images').files = dt.files;
    
    // Update counter
    const counter = document.getElementById('fileCount');
    if (collectedFiles.length > 0) {
        counter.textContent = collectedFiles.length + ' image' + (collectedFiles.length > 1 ? 's' : '') + ' selected';
        counter.classList.remove('hidden');
    } else {
        counter.classList.add('hidden');
    }
}

function renderPreviews() {
    const container = document.getElementById('imagePreviews');
    container.innerHTML = '';
    
    if (collectedFiles.length > 0) {
        container.classList.remove('hidden');
        
        collectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="h-32 w-full object-cover rounded-lg shadow-sm border border-gray-200">
                    <div class="absolute top-1 left-1">
                        ${index === 0 ? '<span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">Primary</span>' : '<span class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full">Image ' + (index + 1) + '</span>'}
                    </div>
                    <button type="button" onclick="removeCollectedFile(${index})" class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition hover:bg-red-700" title="Remove">
                        ✕
                    </button>
                `;
                container.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    } else {
        container.classList.add('hidden');
    }
}

// Drag & drop support
(function() {
    const dropZone = document.getElementById('dropZone');
    if (!dropZone) return;
    
    ['dragenter', 'dragover'].forEach(evt => {
        dropZone.addEventListener(evt, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-green-500', 'bg-green-50');
        });
    });
    
    ['dragleave', 'drop'].forEach(evt => {
        dropZone.addEventListener(evt, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-green-500', 'bg-green-50');
        });
    });
    
    dropZone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            Array.from(files).forEach(file => {
                if (allowed.includes(file.type) && file.size <= 5 * 1024 * 1024) {
                    const isDuplicate = collectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!isDuplicate) {
                        collectedFiles.push(file);
                    }
                }
            });
            syncFilesToInput();
            renderPreviews();
        }
    });
})();
</script>
