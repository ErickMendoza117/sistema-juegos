<?php
/**
 * Página de inicio de sesión
 */
require_once 'config.php';

// Si ya está autenticado, redirigir al inicio
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = limpiarDatos($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    
    if (!empty($usuario) && !empty($contrasena)) {
        $sql = "SELECT id_usuario, nombre_usuario, contrasena, nombre_completo FROM usuarios WHERE nombre_usuario = ? AND activo = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $user = $resultado->fetch_assoc();
            
            // Verificar contraseña
            if (password_verify($contrasena, $user['contrasena'])) {
                // Crear sesión
                $_SESSION['usuario_id'] = $user['id_usuario'];
                $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
                $_SESSION['nombre_completo'] = $user['nombre_completo'];
                $_SESSION['ultima_actividad'] = time();
                
                // Actualizar último acceso
                $update_sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuario = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $user['id_usuario']);
                $update_stmt->execute();
                
                header("Location: index.php");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos";
            }
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
        $stmt->close();
    } else {
        $error = "Por favor, complete todos los campos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Juegos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>🎮 Sistema de Gestión de Juegos</h1>
            <h2>Iniciar Sesión</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </form>
            

        </div>
    </div>
</body>
</html>
