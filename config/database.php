<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $con;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'graficos_ot';
        $this->username = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';
    }

    public function conexion()
    {
        try {
            $this->con = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->exec("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
        }

        return $this->con;
    }
}
