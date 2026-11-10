# Feriando

Aplicación web PHP + JavaScript para descubrir ferias, verlas en un calendario y permitir que emprendedores y ferias se registren.

Documentación completa, archivo por archivo, con SQL y preguntas posibles del profesor: `documentacion.txt`.

## Estructura del proyecto

```
4.15-2026-expotech-feriando/
├── index.php                    Junta todas las partes de la página
├── config/
│   └── configuracion.php        Título de la página y datos de la escuela
├── partes/
│   ├── cabeza.php               Lo que va arriba: fuentes, estilos y colores
│   ├── menu.php                 Menú de arriba y menú lateral
│   ├── pagina_principal.php     Inicio: banner, calendario y detalle de feria
│   ├── formularios/
│   │   ├── emprendedor.php      Formulario público de postulación
│   │   ├── feria.php            Formulario para registrar una feria
│   │   └── aviso.php            Formulario para publicar mensaje y foto
│   ├── ventanas.php             Ventanas emergentes: login, reseñas, asistentes
│   └── pie.php                  Pie de página y carga del programa principal
├── css/
│   └── estilos.css              Estilos propios de la página
├── js/
│   ├── programa.js              Programa principal: sesión, calendario y formularios
│   ├── colores.js               Colores y tipografías de la página
│   └── viejo.js                 Versión anterior, no se usa
├── api/
│   ├── get_ferias.php           Devuelve las ferias activas con su fecha
│   ├── guardar_feria.php        Guarda una feria y su fecha
│   ├── get_avisos.php           Devuelve los mensajes de emprendedores
│   ├── guardar_aviso.php        Guarda un mensaje/foto de emprendedor
│   ├── get_favoritos.php        Devuelve las ferias favoritas
│   ├── guardar_favorito.php     Guarda o quita un favorito
│   ├── guardar_resena.php       Guarda una reseña
│   ├── get_resenas.php          Devuelve las reseñas de una feria
│   ├── eliminar_resena.php      Elimina un comentario (autor u organizador)
│   ├── eliminar_feria.php       Elimina una feria (solo su organizador)
│   ├── guardar_usuario.php      Registra una cuenta
│   ├── login_usuario.php        Inicia sesión y devuelve el rol
│   └── logout_usuario.php       Cierra la sesión
├── uploads/                     Fotos subidas por los emprendedores
├── db.php                       Conexión a MySQL
└── feriando.sql                 Estructura de la base de datos
```

## Base de datos

1. Encienda MySQL desde el panel de XAMPP.
2. Abra phpMyAdmin o la consola de MySQL y ejecute **todo** el archivo `feriando.sql`.
   - Por consola: `mysql -u root -p < feriando.sql`
   - También se puede pegar completo en la pestaña **SQL** de phpMyAdmin.
3. `feriando.sql` se puede importar varias veces: crea las tablas que falten, agrega las columnas, índices y relaciones nuevas, y **no borra** los usuarios, ferias ni contraseñas existentes.
4. Si su MySQL usa otra contraseña, cámbiela en `db.php` (`$user` y `$pass`). La base se llama `feriando` y se crea automáticamente si no existe.

Los usuarios se guardan en la tabla `Usuarios`; la contraseña se almacena cifrada con `password_hash()` y se valida con `password_verify()` al iniciar sesión.

## Cómo funciona el registro de ferias

1. El rol **feria** toca un botón **Registrar Nueva Feria**.
2. Se abre `partes/formularios/feria.php`.
3. `js/programa.js` envía los datos a `api/guardar_feria.php`.
4. La API guarda la feria en `Ferias` y su fecha en `Fechas_Feria`.
5. Al volver, el calendario se recarga y se abre automáticamente el mes de la feria publicada, tanto en el calendario como en **Explorar por Mes**.
