<?php
/**
 * Página de descargas - Repositorio de documentos
 */
require_once 'config.php';
verificarSesion();

// Directorio de descargas
$directorio_descargas = 'descargas/';

// Obtener lista de archivos
$archivos = [];
if (is_dir($directorio_descargas)) {
    $archivos = array_diff(scandir($directorio_descargas), array('.', '..'));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descargas - Sistema de Juegos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>📥 Repositorio de Descargas</h1>
        
        <div class="downloads-info">
            <p>Aquí encontrarás todos los documentos y ensayos disponibles para descargar.</p>
        </div>
        
        <div class="downloads-container">
            <?php if (count($archivos) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nombre del Archivo</th>
                            <th>Tamaño</th>
                            <th>Fecha de Modificación</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archivos as $archivo): ?>
                            <?php 
                                $ruta_completa = $directorio_descargas . $archivo;
                                $tamano = filesize($ruta_completa);
                                $fecha = date("d/m/Y H:i", filemtime($ruta_completa));
                                
                                // Formatear tamaño
                                if ($tamano < 1024) {
                                    $tamano_formato = $tamano . ' B';
                                } elseif ($tamano < 1048576) {
                                    $tamano_formato = round($tamano / 1024, 2) . ' KB';
                                } else {
                                    $tamano_formato = round($tamano / 1048576, 2) . ' MB';
                                }
                            ?>
                            <tr>
                                <td>
                                    <span class="file-icon">📄</span>
                                    <?php echo htmlspecialchars($archivo); ?>
                                </td>
                                <td><?php echo $tamano_formato; ?></td>
                                <td><?php echo $fecha; ?></td>
                                <td>
                                    <a href="<?php echo $ruta_completa; ?>" 
                                       class="btn btn-primary btn-small" 
                                       download>
                                        Descargar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-downloads">
                    <p>📂 No hay archivos disponibles para descargar en este momento.</p>
                    <p class="hint">Los archivos deben colocarse en la carpeta <code>descargas/</code></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
