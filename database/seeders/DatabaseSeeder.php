<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // SeederDesarrollo::class,  // Semillas para desarrollo (datos de ejemplo)
            SeederProduccion::class, // Semillas basadas en SEED.xhtml
            //Seeder_Random15::class,  // Datos de prueba adicionales (15 registros aleatorios)
        ]);
    }
}
