import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

import { globSync } from "glob";

export default defineConfig({

    //base: '/proyecto/public/build/',
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",

                //CSS                
                "resources/css/plantillaprincipal.css",
                "resources/css/paginas/paginainicio.css",                
                "resources/css/paginas/galeria.css",
                "resources/css/paginas/galeriapublica.css",
                "resources/css/paginas/vistafotosreportajes.css",
                "resources/css/paginas/listados.css",
                "resources/css/paginas/privada.css",
                "resources/css/paginas/confirmaciones.css",
                "resources/css/paginas/errores.css",
                "resources/css/paginas/manual.css",

                //JS
                // Registra masivamente todos los archivos JS dentro de la carpeta 'paginas'
                ...globSync("resources/js/paginas/*.js"),
                
                
                // Calendario
                "resources/js/paginas/calendario.js",
                "resources/css/paginas/calendario.css",
                "resources/js/paginas/privada.js",

                // Assets estáticos
                "resources/images/favicon.svg",
                
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
