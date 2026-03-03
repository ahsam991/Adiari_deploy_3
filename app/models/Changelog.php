<?php
/**
 * Changelog Model
 * Tracks all development changes for the project
 */

require_once __DIR__ . '/../core/Model.php';

class Changelog extends Model {
    protected $table = 'changelog';
    protected $db = 'grocery';
    protected $timestamps = false;
    protected $fillable = ['version', 'title', 'description', 'change_type', 'changed_by'];

    /**
     * Get all changelog entries, newest first
     * @return array
     */
    public function getAll() {
        return Database::fetchAll(
            "SELECT * FROM {$this->table} ORDER BY created_at DESC",
            [],
            $this->db
        );
    }

    /**
     * Add a new changelog entry
     */
    public function addEntry($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->create($data);
    }
}
