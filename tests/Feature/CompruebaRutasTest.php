<?php

namespace Tests\Feature;

use App\Enums\NombreRole;
use App\Models\Cita;
use App\Models\Fotografia;
use App\Models\Formato;
use App\Models\Item;
use App\Models\Pedido;
use App\Models\Reportaje;
use App\Models\Role;
use App\Models\Soporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testea que todas las rutas de la aplicación están registradas y responden
 * al protocolo HTTP correcto (que no den 404 ó 405).
 *
 * Usa withoutMiddleware() para no tener que pasar por autenticación y los privilegios, y
 * actingAs para que Auth::user() esté disponible en los controladores que
 * lo usan directamente.
 */
class CompruebaRutasTest extends TestCase
{
    use RefreshDatabase;

        // Test principal, todo en uno para evitar repetir la creación de modelos.
    
    /**
     * Construye la lista de rutas y comprueba
     * que ninguna devuelve 404 (ruta no registrada) ni 405 (metodo incorrecto).
     */
    public function testTodasLasRutasSonAccesibles(): void
    {
        
        // Fixtures, modelos necesarios para incluir en las rutas con parámetros.
        
        $role      = Role::factory()->create(['nombre_role' => NombreRole::CLIENTE->value]); 
        $usuario   = User::factory()->create(['role_id' => $role->id]); // Usuario común, sin privilegios especiales, para usar en actingAs.
        $reportaje = Reportaje::factory()->create(['user_id' => $usuario->id]);
        $foto      = Fotografia::factory()->create(['reportaje_id' => $reportaje->id]);
        $soporte   = Soporte::factory()->create();
        $formato   = Formato::factory()->create();
        $pedido    = Pedido::factory()->create(['user_id' => $usuario->id]);
        $item      = Item::factory()->create([
            'pedido_id'    => $pedido->id,
            'soporte_id'   => $soporte->id,
            'formato_id'   => $formato->id,
            'fotografia_id'=> $foto->id,
        ]);
        $cita = Cita::factory()->create(['user_id' => $usuario->id]);
        // Registros para usar en las rutas borrar.
        $soporteParaBorrar  = Soporte::factory()->create();
        $formatoParaBorrar  = Formato::factory()->create();
        $fotoParaBorrar     = Fotografia::factory()->create(['reportaje_id' => $reportaje->id]);
        $pedidoParaBorrar   = Pedido::factory()->create(['user_id' => $usuario->id]);
        $itemParaBorrar     = Item::factory()->create([
            'pedido_id'    => $pedidoParaBorrar->id,
            'soporte_id'   => $soporte->id,
            'formato_id'   => $formato->id,
            'fotografia_id'=> $foto->id,
        ]);
        $reportajeParaBorrar = Reportaje::factory()->create(['user_id' => $usuario->id]);
        // Cita extra en usuario distinto (user_id es único en citas)
        $usuarioAuxCita      = User::factory()->create(['role_id' => $role->id]);
        $citaParaBorrar      = Cita::factory()->create(['user_id' => $usuarioAuxCita->id]);
        $usuarioParaBorrar   = User::factory()->create(['role_id' => $role->id]);

        
        // Lista de rutas que hay que comporbar: [verbo, url]
        // El orden importa: las operaciones de lectura/edición
        // van antes que las DELETE para evitar borrar fixturas compartidas.
        
        $rutas = [
            //páginas públicas (directamente en web.php)
            ['GET', '/'],
            ['GET', '/contacto'],
            ['GET', '/cookies'],
            ['GET', '/about'],

            // aplicacion/autenticacion.php
            ['GET',  '/login'],
            ['POST', '/login'],
            ['GET',  '/logout'],
            ['GET',  '/accesodenegado'],
            ['GET',  '/noautenticado'],

            // web.php: zona privada (requiere Auth::user(), cubierto con actingAs)
            ['GET', '/zonaprivada'],

            // aplicacion/servirfotosrepor.php
            // /private requiere fichero físico; solo comprobamos que no sea 405
            ['GET', "/reportaje/{$reportaje->id}/fotos"],
            ['GET', '/fotospublicas'],
            ['GET', "/foto/{$foto->id}"],
            ['GET', "/fotopublica/{$foto->id}"],
            ['GET', '/filtrarporetiqueta'],

            // aplicacion/carrito.php
            ['POST', "/mostrarformcarrito/{$foto->id}"],
            ['POST', '/procesaritemcarrito'],
            ['GET',  '/mostrarcarrito'],
            ['POST', '/borraritemcarrito'],
            ['POST', '/vaciarcarrito'],
            ['POST', '/procesarcarrito'],

            // web.php: mantenimiento BD
            ['GET', '/filtrarclientesfantasma'],
            ['GET', '/filtrarreportajesfantasma'],
            ['GET', '/filtrarfotosfantasma'],
            ['GET', '/filtrarpedidosfantasma'],

            // administracion/admincitas.php
            ['GET',  '/listarcitas'],
            ['GET',  "/formeditarcita/{$cita->id}"],
            ['PUT',  "/editarcita/{$cita->id}"],
            ['GET',  '/filtrarcitas'],
            ['GET',  '/calendario'],
            ['GET',  '/api/citas'],
            ['PUT',  '/api/micita'],

            // administracion/adminetiquetas.php
            ['PUT',  "/anadiretiquetafoto/{$foto->id}"],
            ['PUT',  "/borraretiquetafoto/{$foto->id}"],
            ['POST', '/borrarEtiqueta'],
            ['POST', '/crearEtiqueta'],

            // administracion/adminformatos.php
            ['GET',  '/listarformatos'],
            ['GET',  "/formeditarformato/{$formato->id}"],
            ['PUT',  "/editarformato/{$formato->id}"],
            ['GET',  '/filtrarformatos'],
            ['GET',  '/nuevoformato'],
            ['POST', '/nuevoformato'],

            // administracion/adminfotografias.php
            ['GET',  '/listarfotografias'],
            ['GET',  '/filtrarfotografias'],
            ['GET',  '/formnuevafotografia'],
            ['POST', '/nuevafotografia'],
            ['PUT',  "/editarfotografia/{$foto->id}"],

            // administracion/adminpedidos.php
            ['GET', '/listarpedidos'],
            ['GET', "/verdetallepedido/{$pedido->id}"],
            ['GET', '/filtrarpedidos'],
            ['GET', "/formeditarpedido/{$pedido->id}"],
            ['PUT', "/editarpedido/{$pedido->id}"],

            // administracion/adminreportajes.php
            ['GET',  '/listarreportajes'],
            ['GET',  "/formeditarreportaje/{$reportaje->id}"],
            ['PUT',  "/editarreportaje/{$reportaje->id}"],
            ['GET',  '/filtrarreportajes'],
            ['GET',  '/nuevoreportaje'],
            ['POST', '/nuevoreportaje'],

            // administracion/adminsoportes.php
            ['GET',  '/listarsoportes'],
            ['GET',  "/formeditarsoporte/{$soporte->id}"],
            ['PUT',  "/editarsoporte/{$soporte->id}"],
            ['GET',  '/filtrarsoportes'],
            ['GET',  '/nuevosoporte'],
            ['POST', '/nuevosoporte'],

            // administracion/adminusuarios.php
            ['GET',   '/listarusuarios'],
            ['GET',   '/filtrarusuarios'],
            ['GET',   "/editarusuario/{$usuario->id}"],
            ['PUT',   "/editarusuario/{$usuario->id}"],
            ['GET',   '/editarmiperfil'],
            ['PATCH', '/editarmiperfil'],
            ['POST',  '/administracion/marcadoborrarpropia'],
            ['GET',   '/nuevousuario'],
            ['POST',  '/nuevousuario'],

            
            // DELETE al final: usan registros exclusivos para no romper
            // las rutas de lectura/edición anteriores.
            
            ['DELETE', "/borrarcita/{$citaParaBorrar->id}"],
            ['DELETE', "/borrarformato/{$formatoParaBorrar->id}"],
            ['DELETE', "/borrarfotografia/{$fotoParaBorrar->id}"],
            ['DELETE', "/borraritempedido/{$itemParaBorrar->id}"],
            ['DELETE', "/borrarpedido/{$pedidoParaBorrar->id}"],
            ['DELETE', "/borrarreportaje/{$reportajeParaBorrar->id}"],
            ['DELETE', "/borrarsoporte/{$soporteParaBorrar->id}"],
            ['DELETE', "/borrarusuario/{$usuarioParaBorrar->id}"],
            ['DELETE', '/api/micita'],
        ];

        
        // Aqui comprobamos que ninguna ruta da 404 ni 405
        
        foreach ($rutas as [$verbo, $url]) {
            $respuesta = $this->actingAs($usuario)
                ->withoutMiddleware()
                ->call($verbo, $url);

            $estado = $respuesta->getStatusCode();

            $this->assertNotEquals(
                404,
                $estado,
                "La ruta [$verbo $url] devolvió 404 — " .
                "posiblemente no está registrada o el registro no existe."
            );
            $this->assertNotEquals(
                405,
                $estado,
                "La ruta [$verbo $url] devolvió 405 — " .
                "el verbo HTTP no está admitido por esta ruta."
            );
        }
    }
}
