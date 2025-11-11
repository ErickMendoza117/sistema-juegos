<?php
/**
 * Página para dar de alta nuevos juegos
 */
require_once 'config.php';
verificarSesion();

$mensaje = '';
$tipo_mensaje = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = limpiarDatos($_POST['titulo']);
    $genero = limpiarDatos($_POST['genero']);
    $plataforma = limpiarDatos($_POST['plataforma']);
    $desarrollador = limpiarDatos($_POST['desarrollador']);
    $fecha_lanzamiento = limpiarDatos($_POST['fecha_lanzamiento']);
    $precio = floatval($_POST['precio']);
    $clasificacion = limpiarDatos($_POST['clasificacion']);
    $descripcion = limpiarDatos($_POST['descripcion']);
    $stock = intval($_POST['stock']);
    
    if (!empty($titulo) && !empty($genero) && !empty($plataforma)) {
        $sql = "INSERT INTO juegos (titulo, genero, plataforma, desarrollador, fecha_lanzamiento, precio, clasificacion, descripcion, stock) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdssi", $titulo, $genero, $plataforma, $desarrollador, $fecha_lanzamiento, $precio, $clasificacion, $descripcion, $stock);
        
        if ($stmt->execute()) {
            $mensaje = "Juego registrado exitosamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar el juego: " . $conn->error;
            $tipo_mensaje = "error";
        }
        $stmt->close();
    } else {
        $mensaje = "Por favor, complete los campos obligatorios";
        $tipo_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Juegos - Sistema de Juegos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>➕ Registrar Nuevo Juego</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título: *</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="genero">Género: *</label>
                        <select id="genero" name="genero" required>
                            <option value="">Seleccione...</option>
                            <option value="Acción">Acción</option>
                            <option value="Aventura">Aventura</option>
                            <option value="RPG">RPG</option>
                            <option value="Deportes">Deportes</option>
                            <option value="Shooter">Shooter</option>
                            <option value="Estrategia">Estrategia</option>
                            <option value="Simulación">Simulación</option>
                            <option value="Plataformas">Plataformas</option>
                            <option value="Sandbox">Sandbox</option>
                            <option value="Terror">Terror</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="plataforma">Plataforma: *</label>
                        <input type="text" id="plataforma" name="plataforma" placeholder="Ej: PC, PlayStation 5, Xbox" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="desarrollador">Desarrollador:</label>
                        <input type="text" id="desarrollador" name="desarrollador">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_lanzamiento">Fecha de Lanzamiento:</label>
                        <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento">
                    </div>
                    
                    <div class="form-group">
                        <label for="precio">Precio ($):</label>
                        <input type="number" id="precio" name="precio" step="0.01" min="0" value="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="clasificacion">Clasificación:</label>
                        <select id="clasificacion" name="clasificacion">
                            <option value="">Seleccione...</option>
                            <option value="E">E - Everyone</option>
                            <option value="E10+">E10+ - Everyone 10+</option>
                            <option value="T">T - Teen</option>
                            <option value="M">M - Mature</option>
                            <option value="AO">AO - Adults Only</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock:</label>
                        <input type="number" id="stock" name="stock" min="0" value="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Registrar Juego</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
