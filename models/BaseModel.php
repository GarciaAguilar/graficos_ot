<?php
require_once dirname(__DIR__) . '/config/database.php';

abstract class BaseModel {
    
    protected $db;
    protected $table;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
}
?>
