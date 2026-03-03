<?php
/**
 * Manager Dashboard
 */


$stats = $data['stats'];
?>

<div class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
            <div class="p-6">
                <h2 class="text-2xl font-bold tracking-tight">Manager Panel</h2>
                <p class="text-xs text-gray-400 mt-1">ADI ARI Fresh</p>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="/manager" class="flex items-center px-4 py-3 bg-gray-900 rounded-lg text-white">
                    <span class="material-symbols-outlined mr-3">dashboard</span>
                    Dashboard
                </a>
                <a href="/manager/products" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
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
                <a href="/manager/inventory" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition">
                    <span class="material-symbols-outlined mr-3">inventory</span>
                    Inventory
                </a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 hover:text-red-300 rounded-lg mt-8 transition">
                    <span class="material-symbols-outlined mr-3">logout</span>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-6 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Welcome, <?= htmlspecialchars(Session::get('user_name')) ?></span>
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold">
                        <?= strtoupper(substr(Session::get('user_name'), 0, 1)) ?>
                    </div>
                </div>
            </header>

            <main class="p-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Products -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <span class="material-symbols-outlined text-blue-600">inventory_2</span>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900"><?= number_format($stats['total_products']) ?></div>
                        <p class="text-xs text-green-600 mt-2 flex items-center">
                            <span class="material-symbols-outlined text-sm mr-1">trending_up</span>
                            Active Catalog
                        </p>
                    </div>

                    <!-- Low Stock Alerts -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Low Stock Items</h3>
                            <div class="p-2 bg-red-50 rounded-lg">
                                <span class="material-symbols-outlined text-red-600">warning</span>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900"><?= number_format($stats['low_stock']) ?></div>
                        <p class="text-xs text-red-600 mt-2 flex items-center">
                            <span class="material-symbols-outlined text-sm mr-1">priority_high</span>
                            Needs Attention
                        </p>
                    </div>

                    <!-- Pending Orders -->
                    <a href="/manager/orders?status=pending" class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-green-200 transition block">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Pending Orders</h3>
                            <div class="p-2 bg-yellow-50 rounded-lg">
                                <span class="material-symbols-outlined text-yellow-600">shopping_cart</span>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900"><?= number_format($stats['pending_orders']) ?></div>
                        <p class="text-xs text-yellow-600 mt-2 flex items-center">View orders</p>
                    </a>

                    <!-- Total Revenue (Placeholder) -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Today's Revenue</h3>
                            <div class="p-2 bg-green-50 rounded-lg">
                                <span class="material-symbols-outlined text-green-600">payments</span>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900">¥<?= number_format($stats['today_revenue']) ?></div>
                        <p class="text-xs text-green-600 mt-2 flex items-center">
                            Sales from delivered/paid orders
                        </p>
                    </div>
                </div>

                <!-- Sales Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <h3 class="font-bold text-gray-900">Revenue Overview <span id="chartRangeLabel" class="text-sm font-normal text-gray-500 ml-2">(<?= $stats['sales_chart']['rangeLabel'] ?>)</span></h3>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <select id="chartDateRange" class="w-full sm:w-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block p-2">
                                <option value="7" <?= $stats['sales_chart']['range'] == '7' ? 'selected' : '' ?>>Last 7 Days</option>
                                <option value="30" <?= $stats['sales_chart']['range'] == '30' ? 'selected' : '' ?>>Last 30 Days</option>
                                <option value="90" <?= $stats['sales_chart']['range'] == '90' ? 'selected' : '' ?>>Last 3 Months</option>
                                <option value="custom" <?= $stats['sales_chart']['range'] == 'custom' ? 'selected' : '' ?>>Custom Range</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Custom Date Picker (Hidden by default) -->
                    <div id="customDateRange" class="<?= $stats['sales_chart']['range'] == 'custom' ? 'flex' : 'hidden' ?> items-center gap-3 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600">Start:</label>
                            <input type="date" id="chartStartDate" value="<?= $stats['sales_chart']['start'] ?>" class="border border-gray-300 rounded p-1 text-sm bg-white">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600">End:</label>
                            <input type="date" id="chartEndDate" value="<?= $stats['sales_chart']['end'] ?>" class="border border-gray-300 rounded p-1 text-sm bg-white">
                        </div>
                        <button id="applyCustomDateBtn" class="bg-green-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-green-700 transition font-medium">Apply</button>
                    </div>

                    <div class="w-full h-72 relative">
                        <!-- Loading spinner -->
                        <div id="chartLoader" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex items-center justify-center rounded-lg">
                            <span class="material-symbols-outlined animate-spin text-3xl text-green-600">progress_activity</span>
                        </div>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="/manager/product/create" class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-green-50 rounded-lg border border-gray-200 hover:border-green-200 transition group">
                                <span class="material-symbols-outlined text-3xl text-gray-600 group-hover:text-green-600 mb-2">add_circle</span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Add Product</span>
                            </a>
                            <a href="/manager/products" class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-200 hover:border-blue-200 transition group">
                                <span class="material-symbols-outlined text-3xl text-gray-600 group-hover:text-blue-600 mb-2">edit_note</span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Manage Stock</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4">System Status</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Database Connection</span>
                                <span class="text-green-600 font-medium flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Active</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Server Time</span>
                                <span class="text-gray-900"><?= date('Y-m-d H:i:s') ?></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">PHP Version</span>
                                <span class="text-gray-900"><?= phpversion() ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const rangeSelect = document.getElementById('chartDateRange');
    const customDateRangeLabel = document.getElementById('customDateRange');
    const applyCustomBtn = document.getElementById('applyCustomDateBtn');
    const loader = document.getElementById('chartLoader');
    const rangeLabelObj = document.getElementById('chartRangeLabel');
    
    let chartLabels = <?= json_encode($stats['sales_chart']['labels']) ?>;
    let chartData = <?= json_encode($stats['sales_chart']['data']) ?>;
    
    // Create gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(34, 197, 94, 0.2)'); // bg-green-500
    gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');
    
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Revenue (¥)',
                data: chartData,
                backgroundColor: gradient,
                borderColor: '#22c55e', // border-green-500
                borderWidth: 2,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#22c55e',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)', // gray-900
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            return ' ¥' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '¥' + value.toLocaleString();
                        },
                        font: {
                            family: "'Work Sans', sans-serif",
                            size: 12
                        },
                        color: '#6b7280'
                    },
                    grid: {
                        color: '#f3f4f6', // gray-100
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: "'Work Sans', sans-serif",
                            size: 12
                        },
                        color: '#6b7280'
                    },
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            }
        }
    });

    // Chart Updating Logic
    function fetchAndUpdateChart(url, labelText) {
        loader.classList.remove('hidden');
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                salesChart.data.labels = res.chart.labels;
                salesChart.data.datasets[0].data = res.chart.data;
                salesChart.update();
                rangeLabelObj.textContent = '(' + res.chart.rangeLabel + ')';
            }
        })
        .catch(err => console.error("Failed to load chart data:", err))
        .finally(() => {
            loader.classList.add('hidden');
        });
    }

    rangeSelect.addEventListener('change', function() {
        const val = this.value;
        if(val === 'custom') {
            customDateRangeLabel.classList.remove('hidden');
            customDateRangeLabel.classList.add('flex');
        } else {
            customDateRangeLabel.classList.add('hidden');
            customDateRangeLabel.classList.remove('flex');
            fetchAndUpdateChart(`/manager?range=${val}`);
        }
    });

    applyCustomBtn.addEventListener('click', function() {
        const start = document.getElementById('chartStartDate').value;
        const end = document.getElementById('chartEndDate').value;
        if(!start || !end) {
            alert('Please select both start and end dates.');
            return;
        }
        if(new Date(start) > new Date(end)) {
            alert('Start date must be before end date.');
            return;
        }
        fetchAndUpdateChart(`/manager?range=custom&start=${start}&end=${end}`);
    });
});
</script>
