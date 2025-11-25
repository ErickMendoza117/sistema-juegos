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
    
    if (!empty($titulo) && !empty($genero) && !empty($plataforma) && !empty($fecha_lanzamiento)) {
        $sql = "INSERT INTO juegos (titulo, genero, plataforma, desarrollador, fecha_lanzamiento, precio, clasificacion, descripcion, stock) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssdssi", $titulo, $genero, $plataforma, $desarrollador, $fecha_lanzamiento, $precio, $clasificacion, $descripcion, $stock);
        
        if ($stmt->execute()) {
            $mensaje = "Juego registrado exitosamente";
            $tipo_mensaje = "success";
            $registro_exitoso = true;
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
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <h1>➕ Registrar Nuevo Juego</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
            <?php if (isset($registro_exitoso) && $registro_exitoso): ?>
                <div class="post-registro-actions" style="margin-bottom: 20px; text-align: center; padding: 20px; background-color: rgba(0,0,0,0.05); border-radius: 8px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.2em;">¿Qué desea hacer ahora?</h3>
                    <div>
                        <a href="index.php" class="btn btn-primary">Volver al inicio</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="titulo">Título: *</label>
                        <input type="text" id="titulo" name="titulo" maxlength="50" required>
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
                        <div class="multi-select-container" id="plataforma-container">
                            <div class="multi-select-box" id="plataforma-box">
                                <span class="multi-select-placeholder">Seleccione plataformas...</span>
                            </div>
                            <div class="multi-select-dropdown" id="plataforma-dropdown">
                                <!-- Options injected by JS -->
                            </div>
                            <input type="hidden" id="plataforma" name="plataforma" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="desarrollador">Desarrollador:</label>
                        <input type="text" id="desarrollador" name="desarrollador" maxlength="50">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_lanzamiento">Fecha de Lanzamiento: *</label>
                        <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="precio">Precio ($):</label>
                        <input type="number" id="precio" name="precio" step="0.01" min="0" max="999999.99" value="0">
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
                        <input type="number" id="stock" name="stock" min="0" max="99999" value="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4" maxlength="5000"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Registrar Juego</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const platforms = [
                'PC', 'PlayStation 5', 'Xbox', 'Nintendo Switch'
            ];
            
            const box = document.getElementById('plataforma-box');
            const dropdown = document.getElementById('plataforma-dropdown');
            const hiddenInput = document.getElementById('plataforma');
            
            let selectedPlatforms = [];
            
            // Initialize dropdown options
            platforms.forEach(platform => {
                const option = document.createElement('div');
                option.className = 'multi-select-option';
                option.innerHTML = `
                    <div class="checkbox"></div>
                    <span>${platform}</span>
                `;
                option.onclick = (e) => {
                    e.stopPropagation();
                    togglePlatform(platform);
                };
                dropdown.appendChild(option);
            });
            
            // Toggle dropdown
            box.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('show');
                box.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', () => {
                dropdown.classList.remove('show');
                box.classList.remove('active');
            });
            
            function togglePlatform(platform) {
                const index = selectedPlatforms.indexOf(platform);
                if (index === -1) {
                    selectedPlatforms.push(platform);
                } else {
                    selectedPlatforms.splice(index, 1);
                }
                updateUI();
            }
            
            function updateUI() {
                // Update hidden input
                hiddenInput.value = selectedPlatforms.join(', ');
                
                // Update box content
                box.innerHTML = '';
                if (selectedPlatforms.length === 0) {
                    box.innerHTML = '<span class="multi-select-placeholder">Seleccione plataformas...</span>';
                } else {
                    selectedPlatforms.forEach(platform => {
                        const tag = document.createElement('div');
                        tag.className = 'multi-select-tag';
                        tag.innerHTML = `
                            ${platform}
                            <span class="remove-tag" onclick="event.stopPropagation(); removePlatform('${platform}')">×</span>
                        `;
                        box.appendChild(tag);
                    });
                }
                
                // Update dropdown selection state
                const options = dropdown.querySelectorAll('.multi-select-option');
                options.forEach(option => {
                    const text = option.querySelector('span').textContent;
                    if (selectedPlatforms.includes(text)) {
                        option.classList.add('selected');
                    } else {
                        option.classList.remove('selected');
                    }
                });
            }
            
            // Expose remove function globally
            window.removePlatform = function(platform) {
                const index = selectedPlatforms.indexOf(platform);
                if (index !== -1) {
                    selectedPlatforms.splice(index, 1);
                    updateUI();
                }
            };
        });
    </script>
</body>
</html>
