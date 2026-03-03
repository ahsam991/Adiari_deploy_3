<?php
/**
 * Root entry point for Hostinger deployment
 * Delegates to public/index.php while keeping SCRIPT_NAME at root level
 * This ensures Router::url() generates correct paths without /public prefix
 */

// Simply require the real entry point
require __DIR__ . '/public/index.php';
