<?php
/**
 * Página para consultar y editar juegos
 */
require_once 'config.php';
verificarSesion();

$mensaje = '';
$tipo_mensaje = '';

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = "DELETE FROM juegos WHERE id_juego = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensaje = "Juego eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar el juego";
        $tipo_mensaje = "error";
    }
    $stmt->close();
}

// Obtener filtros
$busqueda = isset($_GET['busqueda']) ? limpiarDatos($_GET['busqueda']) : '';
$genero_filtro = isset($_GET['genero']) ? limpiarDatos($_GET['genero']) : '';
$ordenar_por = isset($_GET['ordenar_por']) ? $_GET['ordenar_por'] : 'fecha_registro';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'DESC';

// Validar columnas permitidas para ordenamiento
$columnas_permitidas = ['titulo', 'precio', 'fecha_lanzamiento', 'stock', 'fecha_registro'];
if (!in_array($ordenar_por, $columnas_permitidas)) {
    $ordenar_por = 'fecha_registro';
}
if ($orden !== 'ASC' && $orden !== 'DESC') {
    $orden = 'DESC';
}

// Construir consulta
$sql = "SELECT * FROM juegos WHERE 1=1";
$params = [];
$types = "";

if (!empty($busqueda)) {
    $sql .= " AND (titulo LIKE ? OR desarrollador LIKE ? OR plataforma LIKE ?)";
    $busqueda_param = "%$busqueda%";
    $params[] = $busqueda_param;
    $params[] = $busqueda_param;
    $params[] = $busqueda_param;
    $types .= "sss";
}

if (!empty($genero_filtro)) {
    $sql .= " AND genero = ?";
    $params[] = $genero_filtro;
    $types .= "s";
}

$sql .= " ORDER BY $ordenar_por $orden";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

// Obtener géneros únicos para el filtro
$generos = $conn->query("SELECT DISTINCT genero FROM juegos ORDER BY genero")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Juegos - Sistema de Juegos</title>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>🔍 Consultar Juegos</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="search-container">
            <form method="GET" action="" class="search-form">
                <input type="text" name="busqueda" placeholder="Buscar por título, desarrollador o plataforma..." 
                       value="<?php echo htmlspecialchars($busqueda); ?>">
                
                <select name="genero">
                    <option value="">Todos los géneros</option>
                    <?php foreach ($generos as $gen): ?>
                        <option value="<?php echo htmlspecialchars($gen['genero']); ?>" 
                                <?php echo ($genero_filtro === $gen['genero']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($gen['genero']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="ordenar_por" title="Ordenar por">
                    <option value="fecha_registro" <?php echo ($ordenar_por === 'fecha_registro') ? 'selected' : ''; ?>>Más Recientes</option>
                    <option value="titulo" <?php echo ($ordenar_por === 'titulo') ? 'selected' : ''; ?>>Título</option>
                    <option value="precio" <?php echo ($ordenar_por === 'precio') ? 'selected' : ''; ?>>Precio</option>
                    <option value="fecha_lanzamiento" <?php echo ($ordenar_por === 'fecha_lanzamiento') ? 'selected' : ''; ?>>Fecha Lanzamiento</option>
                    <option value="stock" <?php echo ($ordenar_por === 'stock') ? 'selected' : ''; ?>>Stock</option>
                </select>

                <select name="orden" title="Dirección del orden">
                    <option value="ASC" <?php echo ($orden === 'ASC') ? 'selected' : ''; ?>>Ascendente (A-Z / 0-9)</option>
                    <option value="DESC" <?php echo ($orden === 'DESC') ? 'selected' : ''; ?>>Descendente (Z-A / 9-0)</option>
                </select>
                
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="consultas.php" class="btn btn-secondary">Limpiar</a>
            </form>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Género</th>
                        <th>Plataforma</th>
                        <th>Desarrollador</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado->num_rows > 0): ?>
                        <?php while ($juego = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $juego['id_juego']; ?></td>
                                <td><strong><?php echo htmlspecialchars($juego['titulo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($juego['genero']); ?></td>
                                <td><?php echo htmlspecialchars($juego['plataforma']); ?></td>
                                <td><?php echo htmlspecialchars($juego['desarrollador']); ?></td>
                                <td>$<?php echo number_format($juego['precio'], 2); ?></td>
                                <td><?php echo $juego['stock']; ?></td>
                                <td class="actions">
                                    <a href="editar.php?id=<?php echo $juego['id_juego']; ?>" class="btn-action btn-edit" title="Editar">✏️</a>
                                    <a href="?eliminar=<?php echo $juego['id_juego']; ?>" 
                                       class="btn-action btn-delete" 
                                       title="Eliminar"
                                       onclick="return confirm('¿Está seguro de eliminar este juego?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">No se encontraron juegos</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="results-info">
            <p>Total de resultados: <strong><?php echo $resultado->num_rows; ?></strong></p>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
