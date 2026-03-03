<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$nav = function (string $path) use ($currentPath) {
    return $currentPath === $path
        ? 'flex items-center px-4 py-3 bg-[#20df29] text-[#111712] rounded-lg shadow-lg shadow-[#20df29]/20 font-medium'
        : 'flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-all group';
};
?>
<div class="bg-gray-100 min-h-screen font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col transition-transform duration-300 fixed md:relative z-50 h-full -translate-x-full md:translate-x-0">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Admin</h2>
                    <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider">ADI ARI Fresh</p>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white">
                     <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <nav class="mt-2 px-4 space-y-1 flex-1 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-4">Main</p>
                <a href="/admin" class="<?= $nav('/admin') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">dashboard</span>
                    Dashboard
                </a>
                <a href="/manager/products" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-all group">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">inventory_2</span>
                    Products
                </a>
                <a href="/manager/categories" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-all group">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">category</span>
                    Categories
                </a>
                <a href="/admin/offers" class="<?= $nav('/admin/offers') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">sell</span>
                    Weekly Deals
                </a>
                <a href="/admin/users" class="<?= $nav('/admin/users') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">people</span>
                    Users
                </a>
                
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Analytics</p>
                <a href="/admin/analytics" class="<?= $nav('/admin/analytics') ?>">
                    <span class="material-symbols-outlined mr-3">analytics</span>
                    Analytics
                </a>
                <a href="/admin/reports" class="<?= $nav('/admin/reports') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">summarize</span>
                    Reports
                </a>
                <a href="/admin/coupons" class="<?= $nav('/admin/coupons') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">local_offer</span>
                    Coupons
                </a>
                
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">System</p>
                <a href="/admin/settings" class="<?= $nav('/admin/settings') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">settings</span>
                    Settings
                </a>
                <a href="/admin/logs" class="<?= $nav('/admin/logs') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">list_alt</span>
                    Logs
                </a>
            </nav>
            
            <div class="p-4 border-t border-gray-800">
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/20 hover:text-red-300 rounded-lg transition-colors w-full">
                    <span class="material-symbols-outlined mr-3">logout</span>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Overlay -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden glass transition-opacity"></div>

        <div class="flex-1 overflow-auto w-full">
            <header class="bg-white shadow-sm p-6 sticky top-0 z-30 flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-900">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-2xl font-bold text-gray-900 flex-1">Analytics Overview</h1>
                <div class="flex items-center gap-4 hidden sm:flex">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">calendar_today</span>
                        <input type="text" value="Last 30 Days" class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 focus:outline-none focus:ring-2 focus:ring-[#20df29]/20 focus:border-[#20df29]" readonly>
                    </div>
                    <button class="bg-[#111712] text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-black transition-colors">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                        Export Report
                    </button>
                </div>
            </header>

            <main class="p-8 space-y-8">
                <!-- Key Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Revenue</p>
                                <h3 class="text-2xl font-bold text-[#111712] mt-1">¥4,285,000</h3>
                            </div>
                            <div class="p-2 bg-[#20df29]/10 rounded-lg text-[#20df29]">
                                <span class="material-symbols-outlined">payments</span>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 font-bold flex items-center mr-2">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                12.5%
                            </span>
                            <span class="text-gray-400">vs last month</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Orders</p>
                                <h3 class="text-2xl font-bold text-[#111712] mt-1">1,482</h3>
                            </div>
                            <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500">
                                <span class="material-symbols-outlined">shopping_bag</span>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 font-bold flex items-center mr-2">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                8.2%
                            </span>
                            <span class="text-gray-400">vs last month</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Avg. Order Value</p>
                                <h3 class="text-2xl font-bold text-[#111712] mt-1">¥2,890</h3>
                            </div>
                            <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500">
                                <span class="material-symbols-outlined">receipt_long</span>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-red-500 font-bold flex items-center mr-2">
                                <span class="material-symbols-outlined text-[16px]">trending_down</span>
                                2.1%
                            </span>
                            <span class="text-gray-400">vs last month</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Active Users</p>
                                <h3 class="text-2xl font-bold text-[#111712] mt-1">892</h3>
                            </div>
                            <div class="p-2 bg-orange-500/10 rounded-lg text-orange-500">
                                <span class="material-symbols-outlined">group</span>
                            </div>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="text-green-600 font-bold flex items-center mr-2">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span>
                                15.3%
                            </span>
                            <span class="text-gray-400">vs last month</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-lg text-[#111712] mb-6">Revenue Overview</h3>
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-lg text-[#111712] mb-6">Visitor Traffic</h3>
                        <canvas id="visitorChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Recent Orders Table Preview -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-lg text-[#111712]">Recent Orders</h3>
                        <a href="#" class="text-sm font-bold text-[#20df29] hover:text-[#1bc423]">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="text-xs font-bold text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3">Order ID</th>
                                    <th class="px-4 py-3">Customer</th>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-[#111712]">#ORD-7782</td>
                                    <td class="px-4 py-3 text-gray-600">Sarah Johnson</td>
                                    <td class="px-4 py-3 text-gray-600">Fresh Tomatoes (5kg)</td>
                                    <td class="px-4 py-3 font-medium text-[#111712]">¥2,250</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Completed</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-[#111712]">#ORD-7781</td>
                                    <td class="px-4 py-3 text-gray-600">Michael Chen</td>
                                    <td class="px-4 py-3 text-gray-600">Organic Spinich</td>
                                    <td class="px-4 py-3 font-medium text-[#111712]">¥850</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Processing</span></td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-[#111712]">#ORD-7780</td>
                                    <td class="px-4 py-3 text-gray-600">Emma Davis</td>
                                    <td class="px-4 py-3 text-gray-600">Halal Chicken Breast</td>
                                    <td class="px-4 py-3 font-medium text-[#111712]">¥1,500</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Completed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue (¥)',
                data: [150000, 230000, 180000, 320000, 290000, 450000, 410000],
                borderColor: '#20df29',
                backgroundColor: 'rgba(32, 223, 41, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Visitor Chart
    const ctxVisitor = document.getElementById('visitorChart').getContext('2d');
    new Chart(ctxVisitor, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Visitors',
                data: [120, 190, 150, 250, 220, 380, 340],
                backgroundColor: '#111712',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
