<?php
/**
 * Database Management Controller
 * Admin interface for database operations
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../helpers/Session.php';

class DatabaseController extends Controller {
    // No layout override needed — View::resolveLayout() auto-detects 'layouts/admin'
    // for any view path starting with 'admin/'

    private function requireAdmin() {
        if (!Session::isLoggedIn() || Session::get('user_role') !== 'admin') {
            Session::setFlash('error', 'Access denied.');
            $this->redirect('/admin/login');
        }
    }
    
    /**
     * Database overview page
     */
    public function index() {
        $this->requireAdmin();
        
        $stats = [];
        $errors = [];
        
        try {
            // Test grocery database connection
            $groceryDb = Database::getConnection('grocery');
            $stats['grocery'] = [
                'status' => 'Connected',
                'tables' => $this->getTables('grocery'),
                'size' => $this->getDatabaseSize('grocery')
            ];
        } catch (Exception $e) {
            $stats['grocery'] = [
                'status' => 'Error',
                'error' => $e->getMessage()
            ];
            $errors[] = 'Grocery Database: ' . $e->getMessage();
        }
        
        // Get table statistics
        $tableStats = [];
        if (isset($stats['grocery']['tables'])) {
            foreach ($stats['grocery']['tables'] as $table) {
                try {
                    $count = $this->getTableRowCount($table, 'grocery');
                    $tableStats[$table] = $count;
                } catch (Exception $e) {
                    $tableStats[$table] = 'Error';
                }
            }
        }
        
        $this->view('admin/database/index', [
            'title' => 'Database Management',
            'stats' => $stats,
            'tableStats' => $tableStats,
            'errors' => $errors
        ]);
    }
    
    /**
     * Get all tables in a database
     */
    private function getTables($dbConfig) {
        $query = "SHOW TABLES";
        $result = Database::fetchAll($query, [], $dbConfig);
        
        $tables = [];
        foreach ($result as $row) {
            $tables[] = array_values($row)[0];
        }
        
        return $tables;
    }
    
    /**
     * Get database size
     */
    private function getDatabaseSize($dbConfig) {
        $config = require __DIR__ . '/../../config/database.php';
        $dbName = $config[$dbConfig]['database'];
        
        $query = "SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                  FROM information_schema.TABLES 
                  WHERE table_schema = ?";
        
        $result = Database::fetchOne($query, [$dbName], $dbConfig);
        return $result['size_mb'] ?? 0;
    }
    
    /**
     * Get table row count
     */
    private function getTableRowCount($table, $dbConfig) {
        $query = "SELECT COUNT(*) as count FROM `{$table}`";
        $result = Database::fetchOne($query, [], $dbConfig);
        return $result['count'] ?? 0;
    }
    
    /**
     * View table data
     */
    public function viewTable($tableName) {
        $this->requireAdmin();
        
        try {
            $query = "SELECT * FROM `{$tableName}` LIMIT 100";
            $data = Database::fetchAll($query, [], 'grocery');
            
            // Get table structure
            $structure = Database::fetchAll("DESCRIBE `{$tableName}`", [], 'grocery');
            
            $this->view('admin/database/table', [
                'title' => 'Table: ' . $tableName,
                'tableName' => $tableName,
                'data' => $data,
                'structure' => $structure,
                'count' => $this->getTableRowCount($tableName, 'grocery')
            ]);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error loading table: ' . $e->getMessage());
            $this->redirect('/admin/database');
        }
    }
    
    /**
     * Test database connection
     */
    public function testConnection() {
        $this->requireAdmin();
        
        header('Content-Type: application/json');
        
        $results = [];
        
        // Test grocery database
        try {
            $db = Database::getConnection('grocery');
            $query = "SELECT DATABASE() as db_name, VERSION() as version";
            $result = Database::fetchOne($query, [], 'grocery');
            
            $results['grocery'] = [
                'status' => 'success',
                'database' => $result['db_name'],
                'version' => $result['version']
            ];
        } catch (Exception $e) {
            $results['grocery'] = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        
        echo json_encode($results, JSON_PRETTY_PRINT);
        exit;
    }
}
