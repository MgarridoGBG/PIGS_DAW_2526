<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

================================================================================
  GUÍA DE INSTALACIÓN — OPTA PHOTOS
  Aplicación web Laravel 12  /  PHP 8.2+  /  MySQL  /  Node.js  /  Vite
================================================================================

ÍNDICE
------
  1.  Requisitos previos
  2.  Instalación de XAMPP
  3.  Instalación de Composer
  4.  Instalación de Node.js y npm
  5.  Obtención del código fuente
  6.  Configuración del entorno (.env)
  7.  Instalación de dependencias PHP
  8.  Instalación de dependencias Node / compilación de assets
  9.  Configuración de la base de datos en XAMPP
  10. Migraciones y seeders
  11. Configuración del almacenamiento (carpeta de fotos)
  12. Permisos de carpetas
  13. Verificación final y arranque
  14. Credenciales de acceso por defecto
  15. Subida a producción (servidor real)
  16. Solución de problemas frecuentes


================================================================================
  1.  REQUISITOS PREVIOS
================================================================================

  Antes de comenzar, asegúrate de disponer de:

  - Windows 10/11 (64 bits)
  - XAMPP 8.2 o superior  →  https://www.apachefriends.org/es/download.html
  - Composer 2.x          →  https://getcomposer.org/download/
  - Node.js 20 LTS o superior + npm  →  https://nodejs.org/
  - Git (opcional, recomendado)      →  https://git-scm.com/

  Versiones mínimas requeridas por el proyecto:
    PHP    >= 8.2
    MySQL  >= 5.7  (incluido en XAMPP)
    Node   >= 20
    npm    >= 10
    Composer >= 2


================================================================================
  2.  INSTALACIÓN DE XAMPP
================================================================================

  2.1  Descarga el instalador desde https://www.apachefriends.org
       Elige la versión con PHP 8.2 o superior.

  2.2  Ejecuta el instalador como Administrador.
       Durante la instalación, marca como mínimo los componentes:
         [x] Apache
         [x] MySQL
         [x] PHP
         [x] phpMyAdmin

  2.3  Ruta de instalación recomendada: C:\xampp
       (evita espacios o caracteres especiales en la ruta)

  2.4  Una vez instalado, abre el Panel de Control de XAMPP
       (C:\xampp\xampp-control.exe) y arranca los servicios:
         - Apache  →  pulsa "Start"
         - MySQL   →  pulsa "Start"

  2.5  Comprueba que PHP está disponible en la línea de comandos.
       Abre una PowerShell y ejecuta:

         php -v

       Debes ver algo similar a:
         PHP 8.2.x (cli) ...

       Si no reconoce el comando, añade PHP al PATH manualmente:
         - Abre: Panel de control → Sistema → Variables de entorno
         - En "Variables del sistema", edita "Path"
         - Añade la ruta: C:\xampp\php
         - Acepta y vuelve a abrir la consola.


================================================================================
  3.  INSTALACIÓN DE COMPOSER
================================================================================

  3.1  Descarga el instalador de Composer desde https://getcomposer.org/download/
       Ejecuta "Composer-Setup.exe".

  3.2  El instalador detectará automáticamente el ejecutable de PHP
       en C:\xampp\php\php.exe. Confirma la ruta.

  3.3  Completa la instalación. Composer se añade automáticamente al PATH.

  3.4  Verifica la instalación en PowerShell:

         composer --version

       Resultado esperado:
         Composer version 2.x.x ...

  3.5 https://getcomposer.org/doc/00-intro.md#installation-windows


================================================================================
  4.  INSTALACIÓN DE NODE.JS Y NPM
================================================================================

  4.1  Descarga Node.js LTS desde https://nodejs.org/
       Elige la versión 20 LTS o superior.

  4.2  Ejecuta el instalador (incluye npm automáticamente).

  4.3  Verifica en PowerShell:

         node -v
         npm -v

       Resultados esperados:
         v20.x.x
         10.x.x


================================================================================
  5.  OBTENCIÓN DEL CÓDIGO FUENTE
================================================================================

  OPCIÓN A — Copiar los archivos manualmente
  -------------------------------------------
  Copia la carpeta del proyecto completa dentro de:

    C:\xampp\htdocs\

  La estructura resultante debe ser:

    C:\xampp\htdocs\
      artisan
      composer.json
      package.json
      vite.config.js
      app\
      bootstrap\
      config\
      database\
      public\
      resources\
      routes\
      storage\
      ...

  OPCIÓN B — Clonar desde repositorio Git
  ----------------------------------------
  Abre PowerShell en C:\xampp\htdocs y ejecuta:

    cd C:\xampp\htdocs
    git clone <URL_DEL_REPOSITORIO> .

  (el punto final clona en el directorio actual)


================================================================================
  6.  CONFIGURACIÓN DEL ENTORNO (.env)
================================================================================

  6.1  En la raíz del proyecto (C:\xampp\htdocs) crea el archivo .env
       copiando el archivo de ejemplo incluido:

         cd C:\xampp\htdocs
         copy .env.example .env

  6.2  Abre el archivo .env con un editor de texto y ajusta los valores:

  --- CONFIGURACIÓN MÍNIMA NECESARIA ---

    APP_NAME=OptaPhotos
    APP_ENV=local
    APP_KEY=                        ← se genera automáticamente en el paso 7.3
    APP_DEBUG=true
    APP_URL=http://localhost/        ← ajusta si la app no está en el root

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=optaphotos           ← nombre de la base de datos (ver paso 9)
    DB_USERNAME=root
    DB_PASSWORD=                     ← contraseña de MySQL (vacía por defecto en XAMPP)

    SESSION_DRIVER=database
    QUEUE_CONNECTION=database
    CACHE_STORE=database
    FILESYSTEM_DISK=local

  NOTAS IMPORTANTES:
  - APP_URL debe coincidir exactamente con la URL desde la que se accede.
    Si la app está en una subcarpeta, por ejemplo:
      APP_URL=http://localhost/proyecto/public
  - Con XAMPP, la contraseña de MySQL es vacía por defecto (DB_PASSWORD=).
  - El valor de APP_KEY se genera en el siguiente paso; no lo escribas a mano.


================================================================================
  7.  INSTALACIÓN DE DEPENDENCIAS PHP (COMPOSER)
================================================================================

  7.1  Abre PowerShell en la raíz del proyecto:

         cd C:\xampp\htdocs

  7.2  Instala todas las dependencias PHP declaradas en composer.json:

         composer install

       Esto descarga los paquetes en la carpeta vendor\.
       La primera vez puede tardar varios minutos.

  7.3  Genera la clave de la aplicación (rellena APP_KEY en .env):

         php artisan key:generate

       Resultado esperado:
         Application key set successfully.

  7.4  Limpia y precarga la configuración de Laravel:

         php artisan config:clear
         php artisan cache:clear


================================================================================
  8.  INSTALACIÓN DE DEPENDENCIAS NODE Y COMPILACIÓN DE ASSETS
================================================================================

  8.1  Instala las dependencias de Node.js (TailwindCSS, Vite, etc.):

         npm install

       Esto crea la carpeta node_modules\.

  8.2  MODO DESARROLLO — compila los assets con watcher en tiempo real:

         npm run dev

       Deja esta consola abierta mientras desarrollas.
       Vite arrancará un servidor en http://localhost:5173

  8.3  MODO PRODUCCIÓN — compila y minimiza todos los assets:

         npm run build

       Esto genera los archivos finales en public\build\.
       Necesario antes de desplegar o para ver la versión final sin Vite dev.

  NOTA: Para desarrollo diario, usa "npm run dev" en una consola separada
  y accede a la app a través de XAMPP (no directamente por el puerto de Vite).


================================================================================
  9.  CONFIGURACIÓN DE LA BASE DE DATOS EN XAMPP
================================================================================

  9.1  Asegúrate de que el servicio MySQL está arrancado en el Panel de XAMPP.

  9.2  Crea la base de datos. Tienes dos opciones:

  OPCIÓN A — Mediante phpMyAdmin (interfaz gráfica):
  --------------------------------------------------
    - Abre el navegador en: http://localhost/phpmyadmin
    - En el panel izquierdo, haz clic en "Nueva"
    - Nombre de la base de datos: optaphotos
    - Cotejamiento: utf8mb4_unicode_ci
    - Haz clic en "Crear"

  OPCIÓN B — Mediante línea de comandos MySQL:
  --------------------------------------------
    Abre PowerShell y ejecuta:

      C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE optaphotos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

  9.3  Verifica que el archivo .env tiene los datos correctos:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=optaphotos
    DB_USERNAME=root
    DB_PASSWORD=


================================================================================
  10. MIGRACIONES Y SEEDERS
================================================================================

  10.1  Ejecuta todas las migraciones para crear las tablas en la base de datos:

          php artisan migrate

        Esto crea las siguientes tablas (entre otras):
          - roles
          - privilegios
          - roles_privilegios (pivote)
          - users
          - reportajes
          - fotografias
          - fotografias_etiquetas (pivote)
          - etiquetas
          - pedidos
          - items
          - citas
          - formatos
          - soportes
          - personal_access_tokens
          - cache / jobs / sessions (tablas de infraestructura Laravel)

        Resultado esperado al finalizar:
          INFO  Running migrations.
          ... OK x16

  10.2  Pobla la base de datos con los datos iniciales de producción
        (roles, privilegios, formatos, soportes, etiquetas y usuarios de prueba):

          php artisan db:seed

        O de forma explícita:

          php artisan db:seed --class=SeederProduccion

        Resultado esperado:
          INFO  Seeding database.
          ... DONE

  10.3  Si necesitas reiniciar la base de datos desde cero
        (borra todo y vuelve a crear + sembrar):

          php artisan migrate:fresh --seed

        ¡ATENCIÓN! Este comando elimina TODOS los datos. No usar en producción.

  10.4  Crea el enlace simbólico para el disco público (necesario para
        servir archivos del storage públicamente):

          php artisan storage:link


================================================================================
  11. CONFIGURACIÓN DEL ALMACENAMIENTO (CARPETA DE FOTOS)
================================================================================

  La aplicación gestiona fotografías de reportajes almacenadas en:

    storage\app\private\fotosreportajes\

  Dentro de esa carpeta, cada reportaje tiene su propia subcarpeta con el
  nombre del CÓDIGO del reportaje. Dentro de cada subcarpeta existe además
  una carpeta "thumbs" con las miniaturas generadas.

  Estructura esperada:

    storage\
      app\
        private\
          fotosreportajes\
            20250101REPORT0001\
              foto1.jpg
              foto2.jpg
              thumbs\
                foto1.jpg
                foto2.jpg
            20250202REPORT0002\
              ...

  11.1  La carpeta fotosreportajes ya existe en el repositorio (con .gitignore
        interno). Comprueba que existe:

          C:\xampp\htdocs\storage\app\private\fotosreportajes\

        Si no existe, créala:

          mkdir C:\xampp\htdocs\storage\app\private\fotosreportajes

  11.2  Si dispones de fotografías de prueba, cópialas dentro de la carpeta
        del reportaje que corresponda, respetando exactamente la estructura
        descrita. El nombre de la carpeta debe ser idéntico al código del
        reportaje registrado en la base de datos.

  11.3  Las miniaturas (thumbs) se encuentran en una subcarpeta "thumbs"
        dentro de la carpeta de cada reportaje. Deben tener el mismo nombre
        de archivo que la foto original.