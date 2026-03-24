import { Calendar } from '@fullcalendar/core' 
import dayGridPlugin from '@fullcalendar/daygrid' 
import interactionPlugin from '@fullcalendar/interaction'

// He instalado esta api, FullCalendar, y sus plugins necesarios para mostrar el calendario y gestionar citas por el usuario cliente.
// (npm install @fullcalendar/core @fullcalendar/daygrid @fullcalendar/interaction)
// Este script se encarga de cargar las citas desde el backend, mostrar la disponibilidad en el calendario,
// y permitir al usuario reservar, modificar o cancelar su propia cita.
// La documantacion de FullCalendar esta en https://fullcalendar.io/docs.

// Obtener el CSRF token del meta tag, es necesario para las peticiones POST/PUT/DELETE en Laravel
function getCsrfToken() { // Busca el meta tag con el token CSRF
  const metaTag = document.querySelector('meta[name="csrf-token"]') //
  return metaTag ? metaTag.getAttribute('content') : '' // Devuelve el token o una cadena vacía si no se encuentra
}

document.addEventListener('DOMContentLoaded', () => { 
  const elementoCalendario = document.getElementById('calendario') 
  const csrfToken = getCsrfToken() 

/* Opciones de configuración del calendario, incluyendo la función para cargar eventos
desde el backend y la función para manejar clicks en los días del calendario. */

  const calendario = new Calendar(elementoCalendario, {  
    plugins: [dayGridPlugin, interactionPlugin], // Usamos el plugin de vista de mes y el de interacción para clicks
    initialView: 'dayGridMonth', 
    locale: 'es',
    firstDay: 1,
    height: 'auto',
    // Quiero ver 0-2 líneas por día para los turnnos
    dayMaxEvents: false,
    eventDisplay: 'block',
    // Texto del boton today
    buttonText: {
      today: 'Hoy'
    },
    // Formato del título del mes
    titleFormat: { year: 'numeric', month: 'long' },    


    events: async (info, success, failure) => { // Función para cargar citas desde el backend
      try {
        // Usar ruta relativa a la ubicación actual
        const url = new URL('api/citas', window.location.href) 
        url.searchParams.set('start', info.startStr)
        url.searchParams.set('end', info.endStr)

        const respuesta = await fetch(url, { // Peticion para obtener las citas del mes mostrado
          headers: { 'Accept': 'application/json' },
          credentials: 'same-origin',
        })

        if (!respuesta.ok) throw new Error('Error cargando citas') 
        success(await respuesta.json())
      } catch (e) {
        failure(e)
      }
    },

    dateClick: async (info) => { // Función que se ejecuta al hacer click en un día del calendario
      const fecha = info.dateStr
      
      // No permito reservar para días pasados
      if (new Date(fecha) < new Date().setHours(0, 0, 0, 0)) {
        alert('No puedes reservar para días pasados.')
        return
      }
      // Detecta ocupación ya cargada para ese día
      const diasReservados = calendario.getEvents().filter(ev => ev.startStr === fecha) // Esto asume que el backend devuelve eventos con startStr igual a la fecha y extendedProps con el turno ocupado
      const reservado = new Set(diasReservados.map(ev => ev.extendedProps?.turno)) // Esto asume que el backend devuelve un campo 'turno' con 'mañana' o 'tarde' para cada cita

      const libre = [] // Determina qué turnos están libres
      if (!reservado.has('mañana')) libre.push('mañana')
      if (!reservado.has('tarde')) libre.push('tarde')

      if (libre.length === 0) { 
        alert('Ese día está completo (mañana y tarde).')
        return
      }

      const turno = prompt( // Pide al usuario que elija un turno libre
        `reserva para ${fecha}\nOpciones: ${libre.join(', ')}`
      )
      if (!turno) return

      const turnoNormalizado = turno.trim().toLowerCase() // Normaliza la entrada del usuario
      if (!['mañana', 'tarde'].includes(turnoNormalizado)) {
        alert('Turno inválido. Usa "mañana" o "tarde".')
        return
      }
      if (reservado.has(turnoNormalizado)) {
        alert('Ese turno ya está ocupado.')
        return
      }

      // Usar ruta relativa a la ubicación actual
      const respuesta = await fetch('api/micita', { // Petición para reservar o modificar la cita del usuario envia al backend la fecha y el turno elegido
        method: 'PUT', // Usamos PUT para crear o actualizar la cita del usuario
        headers: {  // Enviar el turno elegido en el cuerpo de la petición
          'Content-Type': 'application/json', 
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken 
        },
        credentials: 'same-origin',
        body: JSON.stringify({ fecha, turno: turnoNormalizado }),
      })

      if (!respuesta.ok) { // Si la respuesta no es OK, intenta obtener el mensaje de error del backend
        const err = await respuesta.json().catch(() => ({}))
        console.error('Error al reservar:', respuesta.status, err)
        alert(err?.message || err?.error || `Error ${respuesta.status}: No se pudo reservar`)
        return
      }

      calendario.refetchEvents() // Refresca las citas en el calendario para mostrar la nueva reserva o modificación
    },
  })

  calendario.render() // Renderiza el calendario en el elemento HTML

  // Cancelar mi cita
  const botonCancelarCita = document.getElementById('botonCancelarCita')
  botonCancelarCita?.addEventListener('click', async () => {
    if (!confirm('¿Cancelar tu cita?')) return

    // Usar ruta relativa a la ubicación actual
    const respuesta = await fetch('api/micita', { // Petición para cancelar la cita del usuario, envia una petición DELETE al backend
      method: 'DELETE',
      headers: { 
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken 
      },
      credentials: 'same-origin',
    })

    if (!respuesta.ok) {
      alert('No se pudo cancelar.')
      return
    }

    calendario.refetchEvents()
  })
})