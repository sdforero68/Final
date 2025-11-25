<?php
/**
 * Configuración de conexión a la base de datos
 * Funciona tanto con variables de entorno (Render/nube) como con archivo .env (local)
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // PRIORIDAD 1: Variables de entorno (para Render/nube)
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $dbSsl = getenv('DB_SSL');
        
        // PRIORIDAD 2: Archivo .env (para desarrollo local)
        if (!$host) {
            // Buscar database.env en múltiples ubicaciones
            $envFiles = [
                __DIR__ . '/database.env',              // En api/
                __DIR__ . '/../config/database.env',    // En config/
            ];
            
            $envFile = null;
            foreach ($envFiles as $file) {
                if (file_exists($file)) {
                    $envFile = $file;
                    break;
                }
            }
            
            if ($envFile) {
                $env = parse_ini_file($envFile);
                
                $host = $env['DB_HOST'] ?? 'localhost';
                $port = $env['DB_PORT'] ?? '3306';
                $dbname = $env['DB_NAME'] ?? 'anita_integrales';
                $username = $env['DB_USER'] ?? 'root';
                $password = $env['DB_PASSWORD'] ?? '';
                $dbSsl = $env['DB_SSL'] ?? null;
            }
        }
        
        // Valores por defecto para desarrollo local si no hay configuración
        $host = $host ?: 'localhost';
        $port = $port ?: '3306';
        $dbname = $dbname ?: 'anita_integrales';
        $username = $username ?: 'root';
        $password = $password ?: '';
        
        // Construir DSN
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        // SSL opcional para servicios en la nube que lo requieren
        if ($dbSsl === 'true' || $dbSsl === true) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = true;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
        
        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            // Log del error con información útil pero sin exponer credenciales
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos. Verifica la configuración.");
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
