<?php
$users = $users ?? [];
$success = Session::getFlash('success');
$error = Session::getFlash('error');
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
                <!-- Mobile Close Button -->
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
                    <span class="material-symbols-outlined mr-3">people</span>
                    Users
                </a>
                
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Analytics</p>
                <a href="/admin/analytics" class="<?= $nav('/admin/analytics') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">analytics</span>
                    Analytics
                </a>
                <a href="/admin/reports" class="<?= $nav('/admin/reports') ?>">
                    <span class="material-symbols-outlined mr-3 group-hover:text-[#20df29] transition-colors">summarize</span>
                    Reports
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

        <!-- Main Content -->
        <div class="flex-1 overflow-auto w-full">
            <header class="bg-white shadow-sm p-6 sticky top-0 z-30 flex justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                     <!-- Mobile Toggle Button -->
                    <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-gray-900">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                        <p class="text-sm text-gray-500 mt-1 hidden sm:block">Manage user access and roles</p>
                    </div>
                </div>
                <button onclick="toggleModal('userModal')" class="bg-[#111712] text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-black transition-colors shadow-lg shadow-black/20">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span class="hidden sm:inline">Add New User</span>
                </button>
            </header>

            <main class="p-8">
                <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                        <p class="text-green-700 font-medium"><?= htmlspecialchars($success) ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center gap-3">
                        <span class="material-symbols-outlined text-red-600">error</span>
                        <p class="text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Filters & Search -->
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                        <button class="px-4 py-2 bg-[#111712] text-white rounded-lg text-sm font-bold whitespace-nowrap">All Users</button>
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">Customers</button>
                        <button class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium whitespace-nowrap transition-colors">Administrators</button>
                    </div>
                    
                    <div class="flex gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:w-64">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                            <input type="text" placeholder="Search users..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#20df29]/20 focus:border-[#20df29]">
                        </div>
                        <button class="p-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 bg-white">
                            <span class="material-symbols-outlined">filter_list</span>
                        </button>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold">
                                <tr>
                                    <th class="px-6 py-4">User</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Joined Date</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                <?php foreach ($users as $u): ?>
                                    <tr class="hover:bg-gray-50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg border border-gray-200">
                                                    <?= strtoupper(substr($u['first_name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-[#111712]"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></div>
                                                    <div class="text-gray-500 text-xs"><?= htmlspecialchars($u['email']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <form method="post" action="/admin/user/<?= (int)$u['id'] ?>/role" class="inline">
                                                <?= $this->csrfField() ?>
                                                <select name="role" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold px-2 py-1 rounded-full cursor-pointer focus:ring-1 focus:ring-[#20df29]
                                                    <?= ($u['role'] === 'admin') ? 'bg-purple-100 text-purple-700' : (($u['role'] === 'manager') ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') ?>">
                                                    <option value="customer" <?= ($u['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                                                    <option value="manager" <?= ($u['role'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
                                                    <option value="admin" <?= ($u['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 font-medium">
                                            <?= date('M d, Y', strtotime($u['created_at'] ?? 'now')) ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button class="p-1.5 text-gray-400 hover:text-[#111712] hover:bg-gray-100 rounded-lg transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                                </button>
                                                <?php if ((int)$u['id'] !== (int)Session::get('user_id')): ?>
                                                    <form method="post" action="/admin/user/<?= (int)$u['id'] ?>/delete" class="inline" onsubmit="return confirm('Delete this user?');">
                                                        <?= $this->csrfField() ?>
                                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="userModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm" onclick="toggleModal('userModal')" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Add New User</h3>
                    <button type="button" onclick="toggleModal('userModal')" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                
                <form method="post" action="/admin/user/create">
                    <?= $this->csrfField() ?>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#20df29] focus:border-transparent transition-shadow">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#20df29] focus:border-transparent transition-shadow">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#20df29] focus:border-transparent transition-shadow">
                        </div>
                         <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#20df29] focus:border-transparent transition-shadow">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                            <div class="relative">
                                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-[#20df29] focus:border-transparent transition-shadow bg-white">
                                    <option value="customer">Customer</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">expand_more</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('userModal')" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-[#20df29] text-[#111712] font-bold rounded-lg hover:bg-[#1bc423] transition-colors shadow-lg shadow-[#20df29]/20">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('.transform').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                modal.querySelector('.transform').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        } else {
            modal.querySelector('.transform').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            modal.querySelector('.transform').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

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
</script>
