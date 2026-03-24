/*
 * Lazy loading de la lista de reportajes fantasma.
 * Al cargar la página realiza una petición AJAX al mismo endpoint
 * y reemplaza el spinner con el contenido HTML devuelto por el servidor.
 * La paginación funciona de forma transparente: cada clic a ?page=N recarga
 * la página shell y el AJAX se lanza de nuevo con el parámetro correcto.
 */

document.addEventListener('DOMContentLoaded', () => {
    const $contenedor = $('#lista-lazyload');
    const url = $contenedor.data('url') + window.location.search;

    $.ajax({
        url: url,
        type: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function (html) {
            $contenedor.html(html);
        },
        error: function () {
            $contenedor.html('<p class="mensaje-cuenta">Error al cargar los datos. Por favor, recarga la página.</p>');
        }
    });
});
