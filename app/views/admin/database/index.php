<?php $pageTitle = $title ?? 'Database Management'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-database"></i> Database Management</h2>
                <button class="btn btn-primary" onclick="testConnection()">
                    <i class="bi bi-plug"></i> Test Connection
                </button>
            </div>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h5>Database Errors:</h5>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Database Status -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-database-check"></i> Main Database (Grocery)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($stats['grocery'])): ?>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-<?php echo $stats['grocery']['status'] === 'Connected' ? 'success' : 'danger'; ?>">
                                        <?php echo htmlspecialchars($stats['grocery']['status']); ?>
                                    </span>
                                </p>
                                <?php if (isset($stats['grocery']['size'])): ?>
                                <p><strong>Size:</strong> <?php echo $stats['grocery']['size']; ?> MB</p>
                                <p><strong>Tables:</strong> <?php echo count($stats['grocery']['tables']); ?></p>
                                <?php endif; ?>
                                <?php if (isset($stats['grocery']['error'])): ?>
                                <div class="alert alert-danger mt-2">
                                    <?php echo htmlspecialchars($stats['grocery']['error']); ?>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Table Statistics -->
            <?php if (!empty($tableStats)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Table Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Table Name</th>
                                    <th class="text-end">Row Count</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableStats as $table => $count): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars($table); ?></code></td>
                                    <td class="text-end">
                                        <?php if ($count !== 'Error'): ?>
                                            <span class="badge bg-info"><?php echo number_format($count); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Error</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?php echo Router::url('/admin/database/table/' . $table); ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="connectionTestModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Connection Test Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="connectionTestResults">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Testing...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testConnection() {
    const modal = new bootstrap.Modal(document.getElementById('connectionTestModal'));
    modal.show();
    
    fetch('<?php echo Router::url('/admin/database/test-connection'); ?>')
        .then(response => response.json())
        .then(data => {
            let html = '<div class="list-group">';
            
            for (const [db, result] of Object.entries(data)) {
                if (result.status === 'success') {
                    html += `
                        <div class="list-group-item list-group-item-success">
                            <h6><i class="bi bi-check-circle"></i> ${db} Database</h6>
                            <p class="mb-0"><strong>Database:</strong> ${result.database}</p>
                            <p class="mb-0"><strong>Version:</strong> ${result.version}</p>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="list-group-item list-group-item-danger">
                            <h6><i class="bi bi-x-circle"></i> ${db} Database</h6>
                            <p class="mb-0">${result.message}</p>
                        </div>
                    `;
                }
            }
            
            html += '</div>';
            document.getElementById('connectionTestResults').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('connectionTestResults').innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error:</strong> ${error.message}
                </div>
            `;
        });
}
</script>
