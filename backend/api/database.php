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
        $host = getenv('DB_HOST') ?: getenv('DATABASE_HOST');
        $port = getenv('DB_PORT') ?: getenv('DATABASE_PORT');
        $dbname = getenv('DB_NAME') ?: getenv('DATABASE_NAME');
        $username = getenv('DB_USER') ?: getenv('DATABASE_USER');
        $password = getenv('DB_PASSWORD') ?: getenv('DATABASE_PASSWORD');
        $dbSsl = getenv('DB_SSL') ?: getenv('DATABASE_SSL');
        
        // Si hay DATABASE_URL (formato usado por algunos servicios), parsearlo
        $databaseUrl = getenv('DATABASE_URL');
        if ($databaseUrl && !$host) {
            $urlParts = parse_url($databaseUrl);
            if ($urlParts) {
                $host = $urlParts['host'] ?? null;
                $port = $urlParts['port'] ?? '3306';
                $dbname = isset($urlParts['path']) ? ltrim($urlParts['path'], '/') : null;
                $username = $urlParts['user'] ?? null;
                $password = $urlParts['pass'] ?? null;
            }
        }
        
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
        
        // Validar que tenemos los datos mínimos necesarios
        if (empty($host) || empty($dbname)) {
            throw new Exception("Configuración de base de datos incompleta. Verifica las variables de entorno DB_HOST y DB_NAME.");
        }
        
        // Construir DSN
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 5 // Timeout de 5 segundos
        ];
        
        // SSL opcional para servicios en la nube que lo requieren
        if ($dbSsl === 'true' || $dbSsl === true || $dbSsl === '1') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = true;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
        
        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            // Log del error con información útil pero sin exponer credenciales
            error_log("Error de conexión a la base de datos. Host: {$host}, DB: {$dbname}, Error: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos. Verifica la configuración de las variables de entorno.");
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
