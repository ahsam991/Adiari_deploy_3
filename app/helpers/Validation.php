<?php
/**
 * Validation Helper Class
 * Input validation functions
 */

class Validation {
    private $errors = [];
    private $data = [];

    /**
     * Validate data against rules
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @return bool
     */
    public function validate($data, $rules) {
        $this->data = $data;
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $ruleList = explode('|', $ruleSet);
            
            foreach ($ruleList as $rule) {
                $this->applyRule($field, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Apply validation rule
     * @param string $field Field name
     * @param string $rule Rule to apply
     */
    private function applyRule($field, $rule) {
        $value = $this->data[$field] ?? null;
        
        // Extract rule parameters
        $params = [];
        if (strpos($rule, ':') !== false) {
            list($rule, $paramStr) = explode(':', $rule, 2);
            $params = explode(',', $paramStr);
        }

        // Apply validation based on rule
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "{$field} is required");
                }
                break;

            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "{$field} must be a valid email");
                }
                break;

            case 'min':
                $min = $params[0] ?? 0;
                if (strlen($value) < $min) {
                    $this->addError($field, "{$field} must be at least {$min} characters");
                }
                break;

            case 'max':
                $max = $params[0] ?? 0;
                if (strlen($value) > $max) {
                    $this->addError($field, "{$field} must not exceed {$max} characters");
                }
                break;

            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->addError($field, "{$field} must be numeric");
                }
                break;

            case 'alpha':
                if ($value && !ctype_alpha($value)) {
                    $this->addError($field, "{$field} must contain only letters");
                }
                break;

            case 'alphanumeric':
                if ($value && !ctype_alnum($value)) {
                    $this->addError($field, "{$field} must be alphanumeric");
                }
                break;

            case 'url':
                if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "{$field} must be a valid URL");
                }
                break;

            case 'match':
                $matchField = $params[0] ?? '';
                $matchValue = $this->data[$matchField] ?? null;
                if ($value !== $matchValue) {
                    $this->addError($field, "{$field} must match {$matchField}");
                }
                break;

            case 'unique':
                // Format: unique:table,column
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                if ($value && $this->checkUnique($table, $column, $value)) {
                    $this->addError($field, "{$field} already exists");
                }
                break;

            case 'exists':
                // Format: exists:table,column
                $table = $params[0] ?? '';
                $column = $params[1] ?? $field;
                if ($value && !$this->checkExists($table, $column, $value)) {
                    $this->addError($field, "{$field} does not exist");
                }
                break;

            case 'in':
                // Format: in:value1,value2,value3
                if ($value && !in_array($value, $params)) {
                    $this->addError($field, "{$field} must be one of: " . implode(', ', $params));
                }
                break;

            case 'date':
                if ($value && !strtotime($value)) {
                    $this->addError($field, "{$field} must be a valid date");
                }
                break;
        }
    }

    /**
     * Check if value is unique in database
     */
    private function checkUnique($table, $column, $value) {
        $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $result = Database::fetchOne($query, [$value]);
        return $result['count'] > 0;
    }

    /**
     * Check if value exists in database
     */
    private function checkExists($table, $column, $value) {
        $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $result = Database::fetchOne($query, [$value]);
        return $result['count'] > 0;
    }

    /**
     * Add error message
     */
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Check if validation passed (no errors)
     * @return bool
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Get all errors (alias for getErrors)
     * @return array
     */
    public function errors() {
        return $this->getErrors();
    }

    /**
     * Get all errors
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Get errors for specific field
     * @param string $field Field name
     * @return array
     */
    public function getError($field) {
        return $this->errors[$field] ?? [];
    }

    /**
     * Check if field has error
     * @param string $field Field name
     * @return bool
     */
    public function hasError($field) {
        return isset($this->errors[$field]);
    }
}
