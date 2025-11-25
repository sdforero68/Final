<?php
/**
 * Configuración de conexión a la base de datos
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Cargar configuración: primero variables de entorno (Render), luego archivo .env (local)
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $dbSsl = getenv('DB_SSL');
        
        // Si no hay variables de entorno, cargar desde archivo .env
        if (!$host) {
            $envFile = __DIR__ . '/database.env';
            
            if (!file_exists($envFile)) {
                throw new Exception("Archivo de configuración database.env no encontrado y no hay variables de entorno configuradas");
            }
            
            $env = parse_ini_file($envFile);
            
            $host = $env['DB_HOST'] ?? 'localhost';
            $port = $env['DB_PORT'] ?? '3306';
            $dbname = $env['DB_NAME'] ?? 'anita_integrales';
            $username = $env['DB_USER'] ?? 'root';
            $password = $env['DB_PASSWORD'] ?? '';
            $dbSsl = $env['DB_SSL'] ?? null;
        }
        
        // Usar valores por defecto si faltan
        $host = $host ?: 'localhost';
        $port = $port ?: '3306';
        $dbname = $dbname ?: 'anita_integrales';
        $username = $username ?: 'root';
        $password = $password ?: '';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // SSL opcional para servicios como PlanetScale
        // Si DB_SSL está configurado como 'true', activa SSL
        if ($dbSsl === 'true' || $dbSsl === true) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = true;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false; // Para desarrollo
        }
        
        // Configurar charset directamente en DSN para evitar deprecación
        // Ya está en el DSN: charset=utf8mb4
        
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

