<?php
/**
 * Product Detail Page
 */
$product = $data['product'];
$relatedProducts = $data['relatedProducts'];

// Calculate discount percentage if on sale
$discount = 0;
if ($product['is_on_sale'] && $product['price'] > 0) {
    $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
}
?>

<div class="bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="text-sm text-gray-500 mb-8">
            <a href="<?= $this->url("/") ?>" class="hover:text-green-600">Home</a>
            <span class="mx-2">/</span>
            <a href="<?= $this->url("/products") ?>" class="hover:text-green-600">Shop</a>
            <span class="mx-2">/</span>
            <a href="/category/<?= htmlspecialchars($product['category_slug']) ?>" class="hover:text-green-600">
                <?= htmlspecialchars($product['category_name']) ?>
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium"><?= htmlspecialchars($product['name']) ?></span>
        </nav>

        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Product Images -->
                <div class="space-y-4">
                    <div class="aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden relative group">
                        <?php if ($product['is_on_sale']): ?>
                            <span class="absolute top-4 left-4 bg-red-500 text-white text-sm font-bold px-3 py-1 rounded shadow-md z-10">
                                -<?= $discount ?>% OFF
                            </span>
                        <?php endif; ?>

                            <img 
                                src="<?= htmlspecialchars($this->productImage($product['primary_image'] ?? null, 'https://placehold.co/600x600?text=Product')) ?>" 
                                alt="<?= htmlspecialchars($product['name']) ?>" 
                                id="mainImage"
                                class="w-full h-96 object-cover object-center transform transition duration-500 hover:scale-105 cursor-zoom-in"
                                onerror="this.onerror=null;this.src='https://placehold.co/600x600?text=No+Image';"
                            >
                    </div>

                    <?php if (!empty($product['images'])): ?>
                        <div class="grid grid-cols-4 gap-4">
                            <?php foreach ($product['images'] as $image): ?>
                                <button 
                                    onclick="document.getElementById('mainImage').src = '<?= htmlspecialchars($this->productImage($image['image_path'], 'https://placehold.co/100x100?text=img')) ?>'"
                                    class="border-2 border-transparent hover:border-green-500 rounded-lg overflow-hidden focus:outline-none focus:border-green-500 transition"
                                >
                                    <img src="<?= htmlspecialchars($this->productImage($image['image_path'], 'https://placehold.co/100x100?text=img')) ?>" alt="" class="w-full h-20 object-cover" onerror="this.src='https://placehold.co/100x100?text=img';">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Product Info -->
                <div>
                    <div class="mb-6">
                        <span class="text-green-600 font-medium text-sm bg-green-50 px-3 py-1 rounded-full inline-block mb-2">
                            <?= htmlspecialchars($product['category_name']) ?>
                        </span>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($product['name']) ?></h1>
                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                            <span class="flex items-center">
                                <span class="material-symbols-outlined text-yellow-500 text-lg mr-1">star</span>
                                <span class="font-bold text-gray-900 mr-1"><?= $product['average_rating'] ?></span>
                                (<?= $product['review_count'] ?> reviews)
                            </span>
                            <span>•</span>
                            <span>SKU: <?= htmlspecialchars($product['sku']) ?></span>
                            <?php if ($product['is_halal']): ?>
                                <span>•</span>
                                <span class="text-green-600 flex items-center font-medium">
                                    <span class="material-symbols-outlined text-sm mr-1">verified</span> Halal
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-8 p-6 bg-gray-50 rounded-xl">
                        <div class="flex items-end space-x-4 mb-4">
                            <?php if ($product['is_on_sale']): ?>
                                <span class="text-4xl font-bold text-red-600">¥<?= number_format($product['sale_price']) ?></span>
                                <span class="text-xl text-gray-400 line-through mb-1">¥<?= number_format($product['price']) ?></span>
                            <?php else: ?>
                                <span class="text-4xl font-bold text-gray-900">¥<?= number_format($product['price']) ?></span>
                            <?php endif; ?>
                            <span class="text-gray-500 mb-1">/ <?= htmlspecialchars($product['unit']) ?></span>
                        </div>

                        <?php if ($product['stock_quantity'] > 0): ?>
                            <div class="flex items-center text-green-600 mb-6 font-medium">
                                <span class="material-symbols-outlined mr-2">check_circle</span>
                                In Stock (<?= $product['stock_quantity'] ?> available)
                            </div>

                            <form action="/cart/add" method="POST" class="flex items-center gap-4">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <?= Security::getCsrfField() ?>
                                
                                <div class="flex items-center border border-gray-300 rounded-lg bg-white">
                                    <button 
                                        type="button"
                                        onclick="decrementQty()"
                                        class="px-3 py-2 text-gray-600 hover:text-green-600 focus:outline-none"
                                    >
                                        −
                                    </button>
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        id="quantity" 
                                        value="1" 
                                        min="1" 
                                        max="<?= $product['stock_quantity'] ?>"
                                        class="w-16 text-center border-0 focus:ring-0 p-2 font-bold text-gray-900"
                                    >
                                    <button 
                                        type="button"
                                        onclick="incrementQty()"
                                        class="px-3 py-2 text-gray-600 hover:text-green-600 focus:outline-none"
                                    >
                                        +
                                    </button>
                                </div>

                                <button type="submit" class="flex-1 bg-green-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-green-700 transition transform hover:scale-105 shadow-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined mr-2">shopping_cart</span>
                                    Add to Cart
                                </button>
                                
                                <?php 
                                    $inWishlist = isset($data['inWishlist']) ? $data['inWishlist'] : false;
                                ?>
                                <button type="button" onclick="toggleWishlist(<?= $product['id'] ?>, this)" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600 transition" title="<?= $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' ?>">
                                    <span class="material-symbols-outlined <?= $inWishlist ? 'text-red-500 fill-current' : '' ?>" style="<?= $inWishlist ? 'font-variation-settings: \'FILL\' 1;' : '' ?>">favorite</span>
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="flex items-center text-red-600 mb-6 font-medium bg-red-50 p-4 rounded-lg">
                                <span class="material-symbols-outlined mr-2">cancel</span>
                                Out of Stock
                            </div>
                            <button disabled class="w-full bg-gray-300 text-gray-500 px-8 py-3 rounded-lg font-bold cursor-not-allowed">
                                Currently Unavailable
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <div class="prose prose-green max-w-none text-gray-600">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Description</h3>
                        <div class="mb-6 leading-relaxed">
                            <?= nl2br(htmlspecialchars($product['description'])) ?>
                        </div>
                        
                        <?php if ($product['is_organic']): ?>
                            <div class="flex items-center p-4 bg-green-50 rounded-lg text-green-800 mb-4">
                                <span class="material-symbols-outlined mr-3 text-2xl">eco</span>
                                <div>
                                    <div class="font-bold">100% Organic Certified</div>
                                    <div class="text-sm">Grown without synthetic pesticides or fertilizers.</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                            <div class="flex justify-between border-b border-gray-200 pb-2">
                                <span class="text-gray-500">Weight/Volume</span>
                                <span class="font-medium"><?= htmlspecialchars($product['weight'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 pb-2">
                                <span class="text-gray-500">Category</span>
                                <span class="font-medium"><?= htmlspecialchars($product['category_name']) ?></span>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span class="text-gray-500">Halal Certified</span>
                                <span class="font-medium"><?= $product['is_halal'] ? 'Yes' : 'No' ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You May Also Like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300 overflow-hidden group">
                            <a href="<?= $this->url('/product/' . $related['id']) ?>" class="block relative aspect-w-1 aspect-h-1 bg-gray-200">
                                <?php if ($related['primary_image']): ?>
                                    <img src="<?= htmlspecialchars($this->productImage($related['primary_image'])) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" onerror="this.src='https://placehold.co/400x400?text=Product';">
                                <?php else: ?>
                                    <div class="w-full h-48 flex items-center justify-center text-gray-400 bg-gray-100">
                                        <span class="material-symbols-outlined text-4xl">image</span>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 mb-1 truncate">
                                    <a href="<?= $this->url('/product/' . $related['id']) ?>" class="hover:text-green-600 transition">
                                        <?= htmlspecialchars($related['name']) ?>
                                    </a>
                                </h3>
                                <div class="font-bold text-green-600">¥<?= number_format($related['price']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function incrementQty() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decrementQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}

function toggleWishlist(productId, btn) {
    const icon = btn.querySelector('span');
    
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `product_id=${productId}&csrf_token=<?= Security::generateCsrfToken() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            if(data.action === 'added') {
                icon.classList.add('text-red-500', 'fill-current');
                icon.style.fontVariationSettings = "'FILL' 1";
                btn.title = "Remove from Wishlist";
            } else {
                icon.classList.remove('text-red-500', 'fill-current');
                icon.style.fontVariationSettings = "'FILL' 0";
                btn.title = "Add to Wishlist";
            }
            // Optional: you can show a small toast here 
            // alert(data.message);
        } else if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            console.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist:', error);
    });
}
</script>
