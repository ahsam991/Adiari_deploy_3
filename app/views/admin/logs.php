<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$nav = function (string $path) use ($currentPath) {
    return $currentPath === $path
        ? 'flex items-center px-4 py-3 bg-gray-800 text-white rounded-lg'
        : 'flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 rounded-lg transition';
};
$userMap = $userMap ?? [];
?>
<div class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6"><h2 class="text-2xl font-bold">Admin</h2><p class="text-xs text-gray-400 mt-1">ADI ARI Fresh</p></div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="/admin" class="<?= $nav('/admin') ?>"><span class="material-symbols-outlined mr-3">dashboard</span>Dashboard</a>
                <a href="/manager/products" class="<?= $nav('/manager/products') ?>"><span class="material-symbols-outlined mr-3">inventory_2</span>Products</a>
                <a href="/manager/categories" class="<?= $nav('/manager/categories') ?>"><span class="material-symbols-outlined mr-3">category</span>Categories</a>
                <a href="/admin/offers" class="<?= $nav('/admin/offers') ?>"><span class="material-symbols-outlined mr-3">sell</span>Weekly Deals</a>
                <a href="/admin/users" class="<?= $nav('/admin/users') ?>"><span class="material-symbols-outlined mr-3">people</span>Users</a>
                <a href="/admin/settings" class="<?= $nav('/admin/settings') ?>"><span class="material-symbols-outlined mr-3">settings</span>Settings</a>
                <a href="/admin/analytics" class="<?= $nav('/admin/analytics') ?>"><span class="material-symbols-outlined mr-3">analytics</span>Analytics</a>
                <a href="/admin/reports" class="<?= $nav('/admin/reports') ?>"><span class="material-symbols-outlined mr-3">summarize</span>Reports</a>
                <a href="/admin/coupons" class="<?= $nav('/admin/coupons') ?>"><span class="material-symbols-outlined mr-3">local_offer</span>Coupons</a>
                <a href="/admin/logs" class="<?= $nav('/admin/logs') ?>"><span class="material-symbols-outlined mr-3">list_alt</span>Logs</a>
                <a href="/logout" class="flex items-center px-4 py-3 text-red-400 hover:bg-red-900/30 rounded-lg mt-8 transition"><span class="material-symbols-outlined mr-3">logout</span>Logout</a>
            </nav>
        </aside>
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900">📊 Activity Logs</h1>
            </header>
            <main class="p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Activities</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($totalActivities ?? 0) ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Today's Activities</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2"><?= number_format($todayActivities ?? 0) ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Unique Users</p>
                        <p class="text-3xl font-bold text-green-600 mt-2"><?= number_format($uniqueUsers ?? 0) ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <p class="text-sm font-medium text-gray-500 uppercase">Active Sessions</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2"><?= number_format($activeSessions ?? 0) ?></p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                    <form method="GET" action="/admin/logs" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label for="activity_type" class="block text-sm font-medium text-gray-700 mb-2">Activity Type</label>
                            <select name="activity_type" id="activity_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Types</option>
                                <option value="user_login" <?= ($activityType ?? '') === 'user_login' ? 'selected' : '' ?>>Login</option>
                                <option value="user_logout" <?= ($activityType ?? '') === 'user_logout' ? 'selected' : '' ?>>Logout</option>
                                <option value="user_registered" <?= ($activityType ?? '') === 'user_registered' ? 'selected' : '' ?>>Registration</option>
                                <option value="product_view" <?= ($activityType ?? '') === 'product_view' ? 'selected' : '' ?>>Product View</option>
                                <option value="cart_add" <?= ($activityType ?? '') === 'cart_add' ? 'selected' : '' ?>>Cart Add</option>
                                <option value="order_placed" <?= ($activityType ?? '') === 'order_placed' ? 'selected' : '' ?>>Order Placed</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">User ID</label>
                            <input type="number" name="user_id" id="user_id" value="<?= $userId ?? '' ?>" placeholder="Filter by user" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex-1 min-w-[150px]">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" id="date" value="<?= $date ?? '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Apply Filters</button>
                        </div>
                        <?php if (!empty($_GET)): ?>
                            <div>
                                <a href="/admin/logs" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition inline-block">Clear</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Logs Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <?php if (!empty($error)): ?>
                        <div class="p-8 text-center text-red-600">
                            <p><?= htmlspecialchars($error) ?></p>
                        </div>
                    <?php elseif (!empty($logs)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Activity</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Page</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">IP Address</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Details</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($logs as $log): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?= htmlspecialchars($log['id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <?php if ($log['user_id']): ?>
                                                    <?php $user = $userMap[$log['user_id']] ?? null; ?>
                                                    <?php if ($user): ?>
                                                        <div>
                                                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span>
                                                            <span class="text-xs text-gray-400 ml-1">#<?= $log['user_id'] ?></span>
                                                        </div>
                                                        <a href="mailto:<?= htmlspecialchars($user['email']) ?>" class="text-blue-600 hover:text-blue-800 text-xs flex items-center gap-1 mt-0.5" title="Send email">
                                                            <span class="material-symbols-outlined" style="font-size:14px">mail</span>
                                                            <?= htmlspecialchars($user['email']) ?>
                                                        </a>
                                                        <?php if (!empty($user['phone'])): ?>
                                                            <span class="text-xs text-gray-400"><?= htmlspecialchars($user['phone']) ?></span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="font-medium text-gray-900">User #<?= htmlspecialchars($log['user_id']) ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-gray-400">Guest</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                $type = $log['activity_type'];
                                                $badges = [
                                                    'user_login' => 'bg-green-100 text-green-800',
                                                    'user_logout' => 'bg-red-100 text-red-800',
                                                    'user_registered' => 'bg-blue-100 text-blue-800',
                                                    'product_view' => 'bg-purple-100 text-purple-800',
                                                    'cart_add' => 'bg-yellow-100 text-yellow-800',
                                                    'order_placed' => 'bg-indigo-100 text-indigo-800',
                                                ];
                                                $badgeClass = $badges[$type] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $badgeClass ?>">
                                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $type))) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <?php if ($log['page_url']): ?>
                                                    <code class="text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded"><?= htmlspecialchars($log['page_url']) ?></code>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                            <td class="px-6 py-4 text-sm">
                                                <?php if ($log['metadata']): ?>
                                                    <details class="cursor-pointer">
                                                        <summary class="text-blue-600 hover:text-blue-800">View Details</summary>
                                                        <pre class="mt-2 text-xs bg-gray-50 p-2 rounded overflow-x-auto"><?= htmlspecialchars(json_encode(json_decode($log['metadata']), JSON_PRETTY_PRINT)) ?></pre>
                                                    </details>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                <?php 
                                                $time = strtotime($log['created_at']);
                                                $diff = time() - $time;
                                                if ($diff < 60) {
                                                    echo "<span class='text-green-600 font-medium'>Just now</span>";
                                                } elseif ($diff < 3600) {
                                                    echo "<span class='text-blue-600'>" . floor($diff / 60) . "m ago</span>";
                                                } elseif ($diff < 86400) {
                                                    echo "<span class='text-purple-600'>" . floor($diff / 3600) . "h ago</span>";
                                                } else {
                                                    echo date('M j, Y g:i A', $time);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if (($totalPages ?? 1) > 1): ?>
                            <div class="flex justify-center gap-2 p-6 bg-gray-50 border-t border-gray-200">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?= $i ?><?= !empty($activityType) ? '&activity_type=' . urlencode($activityType) : '' ?><?= !empty($userId) ? '&user_id=' . $userId : '' ?><?= !empty($date) ? '&date=' . $date : '' ?>" 
                                       class="px-4 py-2 <?= ($currentPage ?? 1) === $i ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?> rounded-lg border border-gray-300 transition">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="p-12 text-center">
                            <p class="text-gray-500 text-lg">No activity logs found.</p>
                            <p class="text-gray-400 text-sm mt-2">Activities will appear here once users interact with the site.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>
