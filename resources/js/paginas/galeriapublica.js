/**
 * galeriapublica.js
 * Comportamiento interactivo para la vista pública de la galería:
 * - despliegue/plegado de la lista de colecciones y la nube de etiquetas
 * - carga dinámica (por AJAX) del resultado al filtrar por etiqueta
 *
 */

document.addEventListener("DOMContentLoaded", function () {
    // Ocultar paneles desplegables de inicio
    $("#tabla-colecciones-publicas").hide();
    $("#nube-etiquetas").hide();

    // Mostrar/ocultar tabla de colecciones y rotar flecha
    $("#despliega-tabla-colecciones").on("click", function () {
        const $cuerpo = $("#tabla-colecciones-publicas");
        const $flecha_1 = $("#flecha_1");

        if ($cuerpo.is(":hidden")) {
            $(this).css("border-radius", "8px 8px 0 0");
            $cuerpo.slideDown(700);
            $flecha_1.css("transform", "rotate(180deg)");
        } else {
            $cuerpo.slideUp(700, function () {
                $("#despliega-tabla-colecciones").css("border-radius", "8px");
            });
            $flecha_1.css("transform", "rotate(0deg)");
        }
    });

    // Mostrar/ocultar nube de etiquetas y rotar su flecha
    $("#despliega-nube-etiquetas").on("click", function () {
        const $nube = $("#nube-etiquetas");
        const $flecha_2 = $("#flecha_2");
        if ($nube.is(":hidden")) {
            $nube.slideDown(700);
            $flecha_2.css("transform", "rotate(180deg)");
        } else {
            $nube.slideUp(700, function () {
                $flecha_2.css("transform", "rotate(0deg)");
            });
        }
    });

    /**
     * Realiza una petición AJAX (GET) a 'ruta' y reemplaza el contenido del contenedor
     * '#contenedor-galeria-fotos' con el HTML (la vista lista de fotos) recibido.
     *
     * @param {string} ruta - URL a la que realizar la petición GET
     */
    function cargarGaleria(ruta) {
        $.ajax({
            url: ruta, 
            method: "GET",
            success: function (respuesta) {
                // Reemplazamos el HTML del contenedor con la respuesta (vista parcial)
                $("#contenedor-galeria-fotos").html(respuesta);
            },
            error: function () {
                // Silencioso: Si no puede filtrar, se queda como es
            },
        });
    }

    // Intercepta el formulario de búsqueda por etiqueta y carga resultados vía AJAX,
    // que procesará el controlador para devolver la vista parcial con las fotos filtradas.
    $("#campo-busqueda-etiqueta form").on("submit", function (e) {
        e.preventDefault();
        const busqueda = $(this).find('[name="busqueda"]').val();
        const ruta = $(this).attr("action") + "?busqueda=" + encodeURIComponent(busqueda);
        cargarGaleria(ruta);
    });

    // Los enlaces de la nube de etiquetas tienen clase '.tw-enlace-etiqueta'
    // y pueden generarse dinámicamente; se captura el clic y se carga vía AJAX.
    $(document).on("click", ".tw-enlace-etiqueta", function (envio) {
        envio.preventDefault();
        cargarGaleria($(this).attr("href"));
    });
});
