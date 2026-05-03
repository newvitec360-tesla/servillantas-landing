# Checklist de Acciones Inmediatas - Servillantas MVC

Antes de que el equipo de desarrollo proceda a crear nuevos endpoints o a modificar interfaces, se debe cumplir con las siguientes acciones.

## Acciones de Configuración Base
- [ ] Renombrar o copiar `.env.example` a `.env` y configurar credenciales de la base de datos (según `Datos-FTP-DB.txt`).
- [ ] Ejecutar el script `database/schema.sql` en el entorno local de desarrollo para crear las 18 tablas.
- [ ] Opcional: Ejecutar `database/seeds/seed_demo.sql` si existe para tener datos iniciales (Usuarios administradores, roles).
- [ ] Verificar que la carpeta `storage/uploads/` y `storage/logs/` tenga permisos de escritura (`chmod 775`).

## Acciones Estructurales MVC
- [ ] Crear el sistema de `Router` que soporte middlewares, o definir cómo se protegerán las rutas administrativas (`config/routes_desktop.php`).
- [ ] Integrar el formulario de `public/login.php` dentro del flujo MVC (ej. `AuthController@showLoginForm`).
- [ ] Implementar la protección CSRF base en `app/Core/Controller.php` o utilidades de seguridad.

## Reglas de Proyecto a Socializar
- [ ] **Desktop vs Mobile**: El equipo frontend debe ser instruido en aislar sus clases CSS. No usar `app.css` de Desktop en la vista Mobile.
- [ ] **SQL Injection Prevention**: El equipo backend debe usar estricta y únicamente "Prepared Statements" PDO dentro de `app/Core/Database.php` y los Models.
- [ ] **VADR Enforcement**: El equipo backend no debe incluir lógica de negocio en los Controladores; todo debe delegarse a `Services`.

## Ingesta de Conocimiento (IA)
- [ ] Ejecutar la persistencia de este entorno en Antigravity a través de la herramienta `super-context-loader` para que los KI estén actualizados de cara a los prompts de generación de código.
