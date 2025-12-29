<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::truncate();

        Event::create([
            'client_id' => Str::uuid(),
            'op_number' => 'OP-001',
            'title' => 'Reunión de equipo',
            'start' => now()->setTime(10, 0, 0),
            'end' => now()->setTime(11, 0, 0),
            'all_day' => false,
            'estado' => 'pendiente',
            'codigos_json' => [
                ['codigo' => 'P-001', 'descripcion' => 'Producto A', 'cantidad' => 100]
            ],
            'cantidad_req' => 100,
            'fecha_entrega' => now()->addDays(7)->toDateString(),
            'fecha_produccion' => now()->toDateString(),
            'created_by' => 'seed',
            'version' => 1,
        ]);

        Event::create([
            'client_id' => Str::uuid(),
            'op_number' => 'OP-002',
            'title' => 'Orden de producción - Cliente XYZ',
            'start' => now()->addDay()->setTime(8, 0, 0),
            'end' => now()->addDay()->setTime(17, 0, 0),
            'all_day' => false,
            'estado' => 'en_progreso',
            'codigos_json' => [
                ['codigo' => 'P-002', 'descripcion' => 'Producto B', 'cantidad' => 250]
            ],
            'cantidad_req' => 250,
            'fecha_entrega' => now()->addDays(10)->toDateString(),
            'fecha_produccion' => now()->addDay()->toDateString(),
            'created_by' => 'seed',
            'version' => 1,
        ]);

        Event::create([
            'client_id' => Str::uuid(),
            'op_number' => 'OP-003',
            'title' => 'Mantenimiento',
            'start' => now()->addDays(2)->setTime(9, 0, 0),
            'end' => now()->addDays(2)->setTime(12, 0, 0),
            'all_day' => false,
            'estado' => 'completado',
            'codigos_json' => [
                ['codigo' => 'M-001', 'descripcion' => 'Servicio', 'cantidad' => 1]
            ],
            'cantidad_req' => 1,
            'fecha_entrega' => null,
            'fecha_produccion' => now()->addDays(2)->toDateString(),
            'created_by' => 'seed',
            'version' => 1,
        ]);
    }
}
