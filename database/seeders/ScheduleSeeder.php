<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::firstOrCreate([
            'event_id' => 1,
            'activity_name' => 'Concurso de dibujo',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'location_description' => 'Sala 2'
        ]);

        Schedule::firstOrCreate([
            'event_id' => 1,
            'activity_name' => 'Desfile cosplay',
            'start_time' => '18:00',
            'end_time' => '18:30',
            'location_description' => 'Escenario'
        ]);

        Schedule::firstOrCreate([
            'event_id' => 2,
            'activity_name' => 'Concurso de k-dance',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'location_description' => 'Sala central'
        ]);

        Schedule::firstOrCreate([
            'event_id' => 2,
            'activity_name' => 'Show en vivo',
            'start_time' => '16:00',
            'end_time' => '17:00',
            'location_description' => 'Escenario - Salón de eventos'
        ]);
    }
}
