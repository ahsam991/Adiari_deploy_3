<?php
/**
 * Base Model Class
 * All models extend this class for database operations
 */

class Model {
    protected $table;
    protected $primaryKey = 'id';
    protected $db = 'grocery'; // Default database
    protected $timestamps = true;
    protected $fillable = [];

    /**
     * Find record by ID
     * @param int $id Record ID
     * @return array|false
     */
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return Database::fetchOne($query, [$id], $this->db);
    }

    /**
     * Find all records
     * @param array $conditions WHERE conditions
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array
     */
    public function findAll($conditions = [], $limit = null, $offset = 0) {
        $query = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }

        if ($limit !== null) {
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return Database::fetchAll($query, $params, $this->db);
    }

    /**
     * Find record by conditions
     * @param array $conditions WHERE conditions
     * @return array|false
     */
    public function findBy($conditions) {
        $where = [];
        $params = [];

        foreach ($conditions as $key => $value) {
            $where[] = "{$key} = ?";
            $params[] = $value;
        }

        $query = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $where) . " LIMIT 1";
        return Database::fetchOne($query, $params, $this->db);
    }

    /**
     * Create new record
     * @param array $data Data to insert
     * @return int Last insert ID
     */
    public function create($data) {
        // Filter only fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        // Add timestamps
        if ($this->timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');

        $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                  VALUES (" . implode(', ', $placeholders) . ")";

        try {
            // Get connection first to ensure we use the same connection for query and lastInsertId
            $pdo = Database::getConnection($this->db);
            
            // Log the insert attempt for debugging
            error_log("Model::create [{$this->table}] - Fields: " . implode(', ', $fields));
            
            // Prepare statement using the PDO object directly
            $stmt = $pdo->prepare($query);
            $stmt->execute($values);
            
            // Get last insert ID from the same PDO object
            $id = $pdo->lastInsertId();
            
            if (!$id) {
                error_log("Model Create Warning: Insert successful but no ID returned. Table: {$this->table}");
            }
            
            return $id;
        } catch (\PDOException $e) {
            error_log("Model Create PDOException [{$this->table}]: " . $e->getMessage());
            error_log("Query: " . $query);
            error_log("Fields: " . implode(', ', $fields));
            error_log("Value count: " . count($values));
            throw new Exception("Database insert failed for {$this->table}: " . $e->getMessage());
        } catch (Exception $e) {
            error_log("Model Create Exception [{$this->table}]: " . $e->getMessage());
            error_log("Query: " . $query);
            error_log("Values: " . json_encode($values));
            throw $e; // Re-throw to be caught by controller/caller
        }
    }

    /**
     * Update record
     * @param int $id Record ID
     * @param array $data Data to update
     * @return bool
     */
    public function update($id, $data) {
        // Filter only fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        // Add updated timestamp
        if ($this->timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $set = [];
        $params = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = ?";
            $params[] = $value;
        }

        $params[] = $id;
        $query = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE {$this->primaryKey} = ?";

        Database::query($query, $params, $this->db);
        return true;
    }

    /**
     * Delete record
     * @param int $id Record ID
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        Database::query($query, [$id], $this->db);
        return true;
    }

    /**
     * Count records
     * @param array $conditions WHERE conditions
     * return int
     */
    public function count($conditions = []) {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "{$key} = ?";
                $params[] = $value;
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }

        $result = Database::fetchOne($query, $params, $this->db);
        return (int)$result['total'];
    }

    /**
     * Execute custom query
     * @param string $query SQL query
     * @param array $params Parameters
     * @return array
     */
    public function query($query, $params = []) {
        return Database::fetchAll($query, $params, $this->db);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return Database::beginTransaction($this->db);
    }

    /**
     * Commit transaction
     */
    public function commit() {
        return Database::commit($this->db);
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        return Database::rollback($this->db);
    }
}
