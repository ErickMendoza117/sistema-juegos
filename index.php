<?php
/**
 * Página principal del sistema
 */
require_once 'config.php';
verificarSesion();

// Obtener estadísticas
$total_juegos = $conn->query("SELECT COUNT(*) as total FROM juegos")->fetch_assoc()['total'];
$total_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE activo = 1")->fetch_assoc()['total'];
$valor_inventario = $conn->query("SELECT SUM(precio * stock) as total FROM juegos")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Sistema de Juegos</title>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="welcome-section">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>! 🎮</h1>
            <p>Sistema de Gestión de Juegos</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-info">
                    <h3><?php echo $total_juegos; ?></h3>
                    <p>Juegos Registrados</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <h3><?php echo $total_usuarios; ?></h3>
                    <p>Usuarios Activos</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>$<?php echo number_format($valor_inventario, 2); ?></h3>
                    <p>Valor del Inventario</p>
                </div>
            </div>
        </div>
        
        <div class="menu-grid">
            <a href="altas.php" class="menu-card">
                <div class="menu-icon">➕</div>
                <h3>Registrar Juegos</h3>
                <p>Agregar nuevos juegos al sistema</p>
            </a>
            
            <a href="consultas.php" class="menu-card">
                <div class="menu-icon">🔍</div>
                <h3>Consultar Juegos</h3>
                <p>Buscar y editar información de juegos</p>
            </a>
            
            <a href="descargas.php" class="menu-card">
                <div class="menu-icon">📥</div>
                <h3>Descargas</h3>
                <p>Repositorio de documentos y ensayos</p>
            </a>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
