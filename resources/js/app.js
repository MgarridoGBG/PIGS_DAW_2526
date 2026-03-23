import './bootstrap';
// Importa jQuery y lo asigna a las variables globales para que esté disponible en todo el proyecto
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// Inicializaciones globales

$(function () {
    console.log("jQuery cargado correctamente");
});