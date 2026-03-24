<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Formato;
use App\Models\Soporte;
use App\Models\Etiqueta;
use App\Models\Privilegio;
use App\Models\Role;
use App\Models\User;
use App\Models\Cita;
use App\Models\Reportaje;
use App\Models\Fotografia;
use App\Models\Pedido;
use App\Models\Item;

class SeederProduccion extends Seeder
{

    public function run(): void
    {
        // FORMATOS 
        $formatos = [
            ['nombre_format' => '10x15', 'ancho' => 10, 'alto' => 15],
            ['nombre_format' => '13x18', 'ancho' => 13, 'alto' => 18],
            ['nombre_format' => '18x24', 'ancho' => 18, 'alto' => 24],
            ['nombre_format' => '24x36', 'ancho' => 24, 'alto' => 36],
            ['nombre_format' => '20x30', 'ancho' => 20, 'alto' => 30],
            ['nombre_format' => '30x40', 'ancho' => 30, 'alto' => 40],
            ['nombre_format' => '40x50', 'ancho' => 40, 'alto' => 50],
            ['nombre_format' => '50x60', 'ancho' => 50, 'alto' => 60],
            ['nombre_format' => '50x70', 'ancho' => 50, 'alto' => 70],
            ['nombre_format' => '70x100', 'ancho' => 70, 'alto' => 100],
            ['nombre_format' => '50x50', 'ancho' => 50, 'alto' => 50],
            ['nombre_format' => '20x20', 'ancho' => 20, 'alto' => 20],
            ['nombre_format' => '9x13', 'ancho' => 9, 'alto' => 13],
            ['nombre_format' => 'A2', 'ancho' => 42, 'alto' => 59.4],
            ['nombre_format' => 'A3', 'ancho' => 29.7, 'alto' => 42],
            ['nombre_format' => 'A4', 'ancho' => 21, 'alto' => 29.7],
            ['nombre_format' => 'DIGITAL', 'ancho' => 100, 'alto' => 100],
        ];
        foreach ($formatos as $data) {
            Formato::create($data);
        }

        // SOPORTES 
        $soportes = [
            ['nombre_soport' => 'Papel Brillo', 'disponibilidad' => true, 'precio' => 40],
            ['nombre_soport' => 'Papel Mate', 'disponibilidad' => true, 'precio' => 40],
            ['nombre_soport' => 'Papel Metálico', 'disponibilidad' => true, 'precio' => 45.5],
            ['nombre_soport' => 'Papel Seda', 'disponibilidad' => true, 'precio' => 45.5],
            ['nombre_soport' => 'Papel Underwood', 'disponibilidad' => true, 'precio' => 55],
            ['nombre_soport' => 'Papel Kraft', 'disponibilidad' => false, 'precio' => 50],
            ['nombre_soport' => 'Papel Reciclado', 'disponibilidad' => false, 'precio' => 40.1],
            ['nombre_soport' => 'Papel Algodón', 'disponibilidad' => true, 'precio' => 50.3],
            ['nombre_soport' => 'Cartón Pluma 10mm', 'disponibilidad' => true, 'precio' => 45.1],
            ['nombre_soport' => 'Cartón Pluma 5mm', 'disponibilidad' => true, 'precio' => 42.5],
            ['nombre_soport' => 'Forex PVC 10mm', 'disponibilidad' => true, 'precio' => 60],
            ['nombre_soport' => 'Forex PVC 5mm', 'disponibilidad' => true, 'precio' => 55],
            ['nombre_soport' => 'Dibond Blanco', 'disponibilidad' => true, 'precio' => 70.1],
            ['nombre_soport' => 'Dibond Plata', 'disponibilidad' => true, 'precio' => 70.1],
            ['nombre_soport' => 'Okume', 'disponibilidad' => false, 'precio' => 100],
            ['nombre_soport' => 'Lona Aquaflex', 'disponibilidad' => true, 'precio' => 65],
            ['nombre_soport' => 'DIGITAL', 'disponibilidad' => true, 'precio' => 25],
        ];
        foreach ($soportes as $data) {
            Soporte::create($data);
        }

        // ETIQUETAS 
        $etiquetas = [
            'NATURALEZA',
            'ANIMALES',
            'RETRATO',
            'PAISAJE',
            'TECNOLOGIA',
            'FLORES',
            'FAMILIAR',
            'PLAYA',
            'COCHES',
            'PRODUCTO',
            'NOCTURNA',
            'CIUDADES',
            'VIAJES',
            'MODA',
            'SUBMARINA',
        ];
        foreach ($etiquetas as $nombre) {
            Etiqueta::create(['nombre_etiqueta' => $nombre]);
        }

        // PRIVILEGIOS 
        $privilegios = [
            'editar_propio',
            'concertar_cita',
            'hacer_pedido',
            'admin_basico',
            'admin_avanzado',
        ];
        foreach ($privilegios as $nombre) {
            Privilegio::create(['nombre_priv' => $nombre]);
        }

        // ROLES + asignación de privilegios 
        // invitado  → ninguno
        $roleInvitado = Role::create(['nombre_role' => 'invitado']);

        // cliente   → privilegios 1,2,3
        $roleCliente = Role::create(['nombre_role' => 'cliente']);
        $roleCliente->privilegios()->sync([1, 2, 3]);

        // empleado  → privilegios 1,2,3,4
        $roleEmpleado = Role::create(['nombre_role' => 'empleado']);
        $roleEmpleado->privilegios()->sync([1, 2, 3, 4]);

        // admin     → privilegios 1,2,3,4,5
        $roleAdmin = Role::create(['nombre_role' => 'admin']);
        $roleAdmin->privilegios()->sync([1, 2, 3, 4, 5]);

        // USERS 
        $users = [
            [
                'email' => 'invitado@opta.com',
                'telefono' => '611111111',
                'nombre' => 'Invitado',
                'apellidos' => 'Apellidos 01',
                'direccion' => 'C/ Java 23',
                'password' => Hash::make('invitado'),
                'dni' => '31111111A',
                'role_id' => 1,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'admin@opta.com',
                'telefono' => '611111112',
                'nombre' => 'Admin',
                'apellidos' => 'Apellidos 02',
                'direccion' => 'Plaza Fortran 12',
                'password' => Hash::make('admin'),
                'dni' => '31111112A',
                'role_id' => 4,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'empleado@opta.com',
                'telefono' => '611111113',
                'nombre' => 'Empleado',
                'apellidos' => 'Apellidos 03',
                'direccion' => 'C/ Basic S/N',
                'password' => Hash::make('empleado'),
                'dni' => '31111113A',
                'role_id' => 3,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'cliente1@opta.com',
                'telefono' => '611111114',
                'nombre' => 'Cliente1',
                'apellidos' => 'Apellidos 04',
                'direccion' => 'Boulevard Python 54',
                'password' => Hash::make('cliente1'),
                'dni' => '31111114A',
                'role_id' => 2,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'cliente2@opta.com',
                'telefono' => '611111115',
                'nombre' => 'Cliente2',
                'apellidos' => 'Apellidos 05',
                'direccion' => 'Vereda del Ensamblador 1',
                'password' => Hash::make('cliente2'),
                'dni' => '31111115A',
                'role_id' => 2,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'cliente3@opta.com',
                'telefono' => '611111116',
                'nombre' => 'Cliente3',
                'apellidos' => 'Apellidos 06',
                'direccion' => 'Plaza COBOL 30',
                'password' => Hash::make('cliente3'),
                'dni' => '31111116A',
                'role_id' => 2,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'cliente4@opta.com',
                'telefono' => '611111117',
                'nombre' => 'Cliente4',
                'apellidos' => 'Apellidos 07',
                'direccion' => 'C/ Pascal 64',
                'password' => Hash::make('cliente4'),
                'dni' => '31111117A',
                'role_id' => 2,
                'marcado_eliminar' => true,
            ],
            [
                'email' => 'clientefantasma1@opta.com',
                'telefono' => '611111118',
                'nombre' => 'ClienteFantasma1',
                'apellidos' => 'Apellidos 08',
                'direccion' => 'Trocha del JavaScript 1970',
                'password' => Hash::make('clientefantasma1'),
                'dni' => '31111118A',
                'role_id' => 2,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'clientefantasma2@opta.com',
                'telefono' => '611111119',
                'nombre' => 'ClienteFantasma2',
                'apellidos' => 'Apellidos 09',
                'direccion' => 'Avda. Kotlin S/N',
                'password' => Hash::make('clientefantasma2'),
                'dni' => '31111119A',
                'role_id' => 2,
                'marcado_eliminar' => false,
            ],
            [
                'email' => 'clienteprueba@opta.com',
                'telefono' => '611111120',
                'nombre' => 'ClientePrueba',
                'apellidos' => 'Apellidos 10',
                'direccion' => 'Boulevard PHP 21',
                'password' => Hash::make('clientePrueba'),
                'dni' => '31111120A',
                'role_id' => 2,
                'marcado_eliminar' => true,
            ],
        ];
        foreach ($users as $data) {
            User::create($data);
        }

        // CITAS 
        $citas = [
            [
                'fecha_cita' => now()->addDays(1)->format('Y-m-d'),
                'turno' => 'mañana',
                'estado_cita' => 'solicitada',
                'user_id' => 4,
            ],
            [
                'fecha_cita' => now()->addDays(1)->format('Y-m-d'),
                'turno' => 'tarde',
                'estado_cita' => 'confirmada',
                'user_id' => 5,
            ],
            [
                'fecha_cita' => now()->addDays(5)->format('Y-m-d'),
                'turno' => 'tarde',
                'estado_cita' => 'confirmada',
                'user_id' => 6,
            ],
        ];
        foreach ($citas as $data) {
            Cita::create($data);
        }

        // REPORTAJES 
        $reportajes = [
            ['tipo' => 'galeria', 'codigo' => '20251012COLEC0001',  'descripcion' => 'Escenas de la Sierra de Huelva', 'fecha_report' => '2025-10-12', 'publico' => true,  'user_id' => 1],
            ['tipo' => 'galeria', 'codigo' => '20260113COLEC0002',  'descripcion' => 'Playas y Mares de Cádiz', 'fecha_report' => '2026-01-13', 'publico' => true,  'user_id' => 1],
            ['tipo' => 'galeria', 'codigo' => '20260201COLEC0003',  'descripcion' => 'Abejas y Plantas Melíferas', 'fecha_report' => '2026-02-01', 'publico' => true,  'user_id' => 1],
            ['tipo' => 'book', 'codigo' => '20240123REPORT0004', 'descripcion' => 'Book de disfraces', 'fecha_report' => '2024-01-23', 'publico' => false, 'user_id' => 4],
            ['tipo' => 'infantil', 'codigo' => '20241221REPORT0005', 'descripcion' => 'Infantil con peluches', 'fecha_report' => '2024-12-21', 'publico' => false, 'user_id' => 4],
            ['tipo' => 'producto', 'codigo' => '20240123REPORT0006', 'descripcion' => 'Cosmética ecológica', 'fecha_report' => '2025-01-23', 'publico' => false, 'user_id' => 5],
            ['tipo' => 'moda', 'codigo' => '20260612REPORT0007', 'descripcion' => 'Vestidos de novia', 'fecha_report' => '2026-06-12', 'publico' => false, 'user_id' => 6],
            ['tipo' => 'book', 'codigo' => '20240123REPORT0008', 'descripcion' => 'Casting de modelos para agencia', 'fecha_report' => '2025-07-01', 'publico' => false, 'user_id' => 6],
            ['tipo' => 'publicitario', 'codigo' => '2026011REPORT0009',  'descripcion' => 'Imágenes de recursos tecnológicos', 'fecha_report' => '2026-01-12', 'publico' => false, 'user_id' => 7],
            ['tipo' => 'publicitario', 'codigo' => '20260223REPORT0010', 'descripcion' => 'Publi para establecimiento hotelero', 'fecha_report' => '2026-02-23', 'publico' => false, 'user_id' => 7],
            ['tipo' => 'otro', 'codigo' => '20260224REPORT0011', 'descripcion' => 'FANTASMA', 'fecha_report' => '2026-02-24', 'publico' => false, 'user_id' => 1],
        ];
        foreach ($reportajes as $data) {
            Reportaje::create($data);
        }

        // FOTOGRAFIAS 
        // Rangos: [inicio, fin, reportaje_id]
        $rangos = [
            [1, 15, 1],
            [16, 30, 2],
            [31, 45, 3],
            [46, 55, 4],
            [56, 60, 5],
            [61, 75, 6],
            [76, 85, 7],
            [86, 100, 8],
            [101, 115, 9],
            [116, 130, 10],
        ];
        foreach ($rangos as [$inicio, $fin, $reportajeId]) {
            for ($i = $inicio; $i <= $fin; $i++) {
                Fotografia::create([
                    'nombre_foto'   => 'MGB' . str_pad($i, 4, '0', STR_PAD_LEFT) . '.jpg',
                    'reportaje_id'  => $reportajeId,
                ]);
            }
        }

        // Fotografías "fantasma" (reportaje 2, sin archivo en storage)
        Fotografia::create([
            'nombre_foto'   => 'Fantasma_01.jpg',
            'reportaje_id'  => 2,
        ]);
        Fotografia::create([
            'nombre_foto'   => 'Fantasma_02.jpg',
            'reportaje_id'  => 2,
        ]);



        // ETIQUETAS A FOTOGRAFÍAS (fotos 1 a 45, reportajes Públicos, 4 etiquetas aleatorias cada una) 
        $totalEtiquetas = Etiqueta::count(); // 15
        for ($fotoId = 1; $fotoId <= 45; $fotoId++) {
            $foto = Fotografia::find($fotoId);
            if ($foto) {
                // Elegir 4 IDs de etiqueta únicos al azar
                $etiquetaIds = \Illuminate\Support\Arr::random(
                    range(1, $totalEtiquetas),
                    4
                );
                $foto->etiquetas()->sync($etiquetaIds);
            }
        }

        // PEDIDOS 
        $pedidos = [
            ['estado_pedido' => 'emitido', 'fecha_pedido' => '2025-12-23', 'user_id' => 4],
            ['estado_pedido' => 'pagado', 'fecha_pedido' => '2025-10-01', 'user_id' => 4],
            ['estado_pedido' => 'aceptado', 'fecha_pedido' => '2026-01-02', 'user_id' => 5],
            ['estado_pedido' => 'pagado', 'fecha_pedido' => '2026-01-10', 'user_id' => 6],
            ['estado_pedido' => 'presupuestado', 'fecha_pedido' => '2026-02-24', 'user_id' => 7],
        ];
        foreach ($pedidos as $data) {
            Pedido::create($data);
        }

        // ITEMS (5 por pedido, fotos de reportajes 1, 2 y 3 → IDs 1-45) 
        // IDs de fotografías de los reportajes 1, 2 y 3
        $fotosDisponibles = Fotografia::whereIn('reportaje_id', [1, 2, 3])->pluck('id')->toArray();
        $formatoIds  = Formato::pluck('id')->toArray();
        $soporteIds  = Soporte::pluck('id')->toArray();

        $totalPedidos = Pedido::count();
        for ($pedidoId = 1; $pedidoId <= $totalPedidos; $pedidoId++) {
            // Elegir 5 fotografías al azar (con posible repetición permitida)
            for ($j = 0; $j < 5; $j++) {
                $fotoId    = $fotosDisponibles[array_rand($fotosDisponibles)];
                $formatoId = $formatoIds[array_rand($formatoIds)];
                $soporteId = $soporteIds[array_rand($soporteIds)];
                $cantidad  = rand(1, 5);
                $precio    = round(rand(500, 5000) / 100, 2); // precio aleatorio entre 5.00 y 50.00

                Item::create([
                    'pedido_id' => $pedidoId,
                    'fotografia_id' => $fotoId,
                    'formato_id' => $formatoId,
                    'soporte_id' => $soporteId,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                ]);
            }
        }
    }
}
