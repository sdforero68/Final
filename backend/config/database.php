<?php
/**
 * Configuración de conexión a la base de datos
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Cargar configuración desde el archivo .env
        $envFile = __DIR__ . '/database.env';
        
        if (!file_exists($envFile)) {
            throw new Exception("Archivo de configuración database.env no encontrado");
        }
        
        $env = parse_ini_file($envFile);
        
        $host = $env['DB_HOST'] ?? 'localhost';
        $port = $env['DB_PORT'] ?? '3306';
        $dbname = $env['DB_NAME'] ?? 'anita_integrales';
        $username = $env['DB_USER'] ?? 'root';
        $password = $env['DB_PASSWORD'] ?? '';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // Configurar charset directamente en DSN para evitar deprecación
        // Ya está en el DSN: charset=utf8mb4
        // Solo agregamos el comando INIT si es necesario
        
        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar una instancia de Database");
    }
}

