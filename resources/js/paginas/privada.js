/* 
 * Controla las secciones desplegables del panel de usuario (zona privada).
 * Usa jQuery
 */

document.addEventListener('DOMContentLoaded', () => {
    // Ocultar todas las seccione al cargar la página
    $('.contenedor-dash-desplegable').hide();
    
    // Click en los titulares de las secciones, abre y cierra la sección correspondiente
    $('.dash-pulsador-desplegar').click(function() {
        // Selecciona el contenedor desplegable inmediatamente siguiente al pulsador
        const lista = $(this).next('.contenedor-dash-desplegable');
        const estaOculta = lista.is(':hidden');
        
        if (estaOculta) {
            // Si la lista está oculta, mostrarla y ocultar las demás
            $('.contenedor-dash-desplegable').slideUp(300);
            $('.dash-pulsador-desplegar').removeClass('activo').find('.flecha').css("transform", "rotate(0deg)");
            lista.slideDown(300);
            $(this).addClass('activo');
            $(this).find('.flecha').css("transform", "rotate(180deg)");
        } else {
            // Si la lista está visible, ocultarla
            lista.slideUp(300);
            $(this).removeClass('activo');
            $(this).find('.flecha').css("transform", "rotate(0deg)");
        }
    });
});