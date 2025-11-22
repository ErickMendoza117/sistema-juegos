<?php
/**
 * Archivo de configuración de la base de datos
 * Ajustar credenciales según el entorno (WAMP/LAMP)
 */

// Configuración para WAMP (desarrollo local)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'sistema_juegos');

// Para AWS EC2 con LAMP, cambiar las credenciales:
// define('DB_HOST', 'localhost');
// define('DB_USER', 'juegos_user');
// define('DB_PASS', 'tu_contraseña_segura');
// define('DB_NAME', 'sistema_juegos');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS en producción

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

/**
 * Función para verificar si el usuario está autenticado
 */
function verificarSesion() {
    if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['nombre_usuario'])) {
        header("Location: login.php");
        exit();
    }
    
    // Actualizar tiempo de última actividad
    $_SESSION['ultima_actividad'] = time();
}

/**
 * Función para cerrar sesión
 */
function cerrarSesion() {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

/**
 * Función para limpiar datos de entrada
 */
function limpiarDatos($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}
?>
