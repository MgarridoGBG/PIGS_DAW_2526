/*
 * Controla el comportamiento del menú hamburguesa en la plantilla principal con jQuery.
*/

document.addEventListener('DOMContentLoaded', () => {

    // Alternar menú al pulsar el botón hamburguesa
    $("#hamburguesa").on("click", function () {
        // 'open' controla la visibilidad del panel (CSS) y 'active' anima el botón
        $("#nav").toggleClass("open");
        $(this).toggleClass("active");
        // Actualizar atributo ARIA para accesibilidad (true/false)
        var isOpen = $(this).hasClass("active");
        $(this).attr("aria-expanded", isOpen);
    });

    // Cerrar menú al pulsar un enlace (mejora en móvil)
    $(".navegacion_link").on("click", function () {
        $("#nav").removeClass("open");
        $("#hamburguesa").removeClass("active").attr("aria-expanded", "false");
    });

    // Cerrar menú si la ventana se agranda por encima del breakpoint
    $(window).on("resize", function () {
        if ($(window).width() > 766) {
            $("#nav").removeClass("open");
            $("#hamburguesa").removeClass("active").attr("aria-expanded", "false");
        }
    });

});
