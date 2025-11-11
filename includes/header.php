<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <a href="index.php">🎮 Sistema de Juegos</a>
        </div>
        <nav class="main-nav">
            <a href="index.php">Inicio</a>
            <a href="altas.php">Registrar</a>
            <a href="consultas.php">Consultar</a>
            <a href="descargas.php">Descargas</a>
        </nav>
        <div class="user-menu">
            <span>👤 <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>
</header>
