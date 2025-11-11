# Sistema de Gestión de Juegos

Sistema web dinámico desarrollado con PHP, MySQL y Apache para la gestión de un catálogo de videojuegos.

## 🚀 Características

- **Sistema de autenticación** con usuarios y contraseñas
- **Gestión de juegos**: Altas, consultas, ediciones y eliminaciones
- **Búsqueda y filtros** avanzados
- **Repositorio de descargas** para documentos
- **Diseño responsive** y moderno
- **Seguridad**: Sesiones con cookies, contraseñas hasheadas con bcrypt

## 📋 Requisitos

### Para WAMP (Desarrollo Local)

- Windows
- WAMP Server (Apache + PHP + MySQL)
- PHP 7.4 o superior
- MySQL 5.7 o superior

### Para AWS EC2 (Producción)

- Ubuntu Server
- LAMP Stack (Linux + Apache + MySQL + PHP)
- PHP 7.4 o superior
- MySQL 5.7 o superior

## 🔧 Instalación

### En WAMP (Local)

1. **Instalar WAMP Server**

   - Descargar de: https://www.wampserver.com/
   - Instalar y ejecutar

2. **Copiar archivos del proyecto**

   ```
   C:\wamp64\www\sistema_juegos\
   ```

3. **Crear la base de datos**

   - Abrir phpMyAdmin: http://localhost/phpmyadmin
   - Importar el archivo `database.sql`

4. **Configurar conexión**

   - Editar `config.php` si es necesario (por defecto usa root sin contraseña)

5. **Acceder al sistema**
   - URL: http://localhost/sistema_juegos/
   - Usuario: `admin`
   - Contraseña: `password`

### En AWS EC2 con LAMP

1. **Conectar a la instancia EC2**

   ```bash
   ssh -i tu-clave.pem ubuntu@tu-ip-publica
   ```

2. **Instalar LAMP Stack**

   ```bash
   sudo apt update
   sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql -y
   ```

3. **Configurar MySQL**

   ```bash
   sudo mysql_secure_installation
   sudo mysql -u root -p
   ```

   En MySQL:

   ```sql
   CREATE USER 'juegos_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
   GRANT ALL PRIVILEGES ON sistema_juegos.* TO 'juegos_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. **Subir archivos del proyecto**

   ```bash
   sudo mkdir /var/www/html/sistema_juegos
   # Subir archivos vía SCP o FTP
   sudo chown -R www-data:www-data /var/www/html/sistema_juegos
   ```

5. **Importar base de datos**

   ```bash
   mysql -u juegos_user -p < database.sql
   ```

6. **Configurar Apache**

   ```bash
   sudo nano /etc/apache2/sites-available/sistema_juegos.conf
   ```

   Agregar:

   ```apache
   <VirtualHost *:80>
       ServerAdmin admin@ejemplo.com
       DocumentRoot /var/www/html/sistema_juegos
       <Directory /var/www/html/sistema_juegos>
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   Activar sitio:

   ```bash
   sudo a2ensite sistema_juegos.conf
   sudo systemctl restart apache2
   ```

7. **Configurar Security Group en AWS**

   - Permitir tráfico HTTP (puerto 80)
   - Permitir tráfico HTTPS (puerto 443) si usas SSL

8. **Editar config.php**

   - Actualizar credenciales de base de datos
   - Cambiar `session.cookie_secure` a 1 si usas HTTPS

9. **Acceder al sistema**
   - URL: http://tu-ip-publica/

## 👥 Usuarios de Prueba

| Usuario  | Contraseña | Rol           |
| -------- | ---------- | ------------- |
| admin    | password   | Administrador |
| usuario1 | password   | Usuario       |
| usuario2 | password   | Usuario       |

## 📁 Estructura del Proyecto

```
sistema_juegos/
├── config.php              # Configuración de BD y funciones
├── login.php              # Página de inicio de sesión
├── logout.php             # Cerrar sesión
├── index.php              # Página principal
├── altas.php              # Registrar juegos
├── consultas.php          # Consultar/editar juegos
├── editar.php             # Editar juego específico
├── descargas.php          # Repositorio de archivos
├── database.sql           # Script de base de datos
├── css/
│   └── styles.css         # Estilos del sistema
├── includes/
│   ├── header.php         # Encabezado común
│   └── footer.php         # Pie de página común
└── descargas/             # Carpeta para archivos descargables
    └── README.txt
```

## 🔒 Seguridad

- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Protección contra SQL Injection con prepared statements
- Validación y sanitización de datos de entrada
- Sesiones con cookies HTTP-only
- Verificación de autenticación en todas las páginas

## 📥 Agregar Archivos para Descarga

1. Colocar archivos en la carpeta `descargas/`
2. Los archivos aparecerán automáticamente en la página de descargas
3. Formatos soportados: PDF, DOC, DOCX, XLS, XLSX, TXT, etc.

## 🛠️ Solución de Problemas

### Error de conexión a la base de datos

- Verificar credenciales en `config.php`
- Asegurar que MySQL esté ejecutándose
- Verificar que la base de datos `sistema_juegos` exista

### Página en blanco

- Activar errores de PHP en desarrollo:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

### Problemas con sesiones

- Verificar permisos de la carpeta de sesiones de PHP
- Limpiar cookies del navegador

## 📝 Notas Adicionales

- Para producción, cambiar las contraseñas de prueba
- Configurar HTTPS con Let's Encrypt en AWS
- Realizar backups regulares de la base de datos
- Monitorear logs de Apache y MySQL

## 📄 Licencia

Proyecto educativo - Libre uso para fines académicos

## 👨‍💻 Autor

Desarrollado como proyecto académico para la materia de Desarrollo Web
