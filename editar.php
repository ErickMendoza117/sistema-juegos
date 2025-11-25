<?php
/**
 * Página para editar juegos existentes
 */
require_once 'config.php';
verificarSesion();

$mensaje = '';
$tipo_mensaje = '';
$id_juego = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_juego === 0) {
    header("Location: consultas.php");
    exit();
}

// Procesar actualización
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
    
    $sql = "UPDATE juegos SET titulo=?, genero=?, plataforma=?, desarrollador=?, fecha_lanzamiento=?, 
            precio=?, clasificacion=?, descripcion=?, stock=? WHERE id_juego=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssdssii", $titulo, $genero, $plataforma, $desarrollador, $fecha_lanzamiento, 
                      $precio, $clasificacion, $descripcion, $stock, $id_juego);
    
    if ($stmt->execute()) {
        $mensaje = "Juego actualizado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar el juego";
        $tipo_mensaje = "error";
    }
    $stmt->close();
}

// Obtener datos del juego
$sql = "SELECT * FROM juegos WHERE id_juego = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_juego);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: consultas.php");
    exit();
}

$juego = $resultado->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Juego - Sistema de Juegos</title>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>✏️ Editar Juego</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título: *</label>
                        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($juego['titulo']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="genero">Género: *</label>
                        <select id="genero" name="genero" required>
                            <option value="">Seleccione...</option>
                            <?php
                            $generos = ['Acción', 'Aventura', 'RPG', 'Deportes', 'Shooter', 'Estrategia', 'Simulación', 'Plataformas', 'Sandbox', 'Terror'];
                            foreach ($generos as $gen) {
                                $selected = ($juego['genero'] === $gen) ? 'selected' : '';
                                echo "<option value='$gen' $selected>$gen</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="plataforma">Plataforma: *</label>
                        <input type="text" id="plataforma" name="plataforma" value="<?php echo htmlspecialchars($juego['plataforma']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="desarrollador">Desarrollador:</label>
                        <input type="text" id="desarrollador" name="desarrollador" value="<?php echo htmlspecialchars($juego['desarrollador']); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_lanzamiento">Fecha de Lanzamiento:</label>
                        <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" value="<?php echo $juego['fecha_lanzamiento']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="precio">Precio ($):</label>
                        <input type="number" id="precio" name="precio" step="0.01" min="0" max="999999.99" value="<?php echo $juego['precio']; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="clasificacion">Clasificación:</label>
                        <select id="clasificacion" name="clasificacion">
                            <option value="">Seleccione...</option>
                            <?php
                            $clasificaciones = ['E' => 'E - Everyone', 'E10+' => 'E10+ - Everyone 10+', 'T' => 'T - Teen', 'M' => 'M - Mature', 'AO' => 'AO - Adults Only'];
                            foreach ($clasificaciones as $key => $value) {
                                $selected = ($juego['clasificacion'] === $key) ? 'selected' : '';
                                echo "<option value='$key' $selected>$value</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock:</label>
                        <input type="number" id="stock" name="stock" min="0" value="<?php echo $juego['stock']; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($juego['descripcion']); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Actualizar Juego</button>
                    <a href="consultas.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
