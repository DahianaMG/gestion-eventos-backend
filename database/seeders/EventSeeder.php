<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::firstOrCreate([
            'user_id' => 2,
            'title' => 'AnimeFest',
            'description' => 'Evento para fans del anime',
            'date_time' => '2025/09/21',
            'location' => 'Club Regatas',
            'has_fair' => true,
            'capacity' => 105
        ]);

        Event::firstOrCreate([
            'user_id' => 3,
            'title' => 'AnimeCon',
            'description' => 'Convención de anime que celebra lo mejor del manga, cosplay y la cultura japonesa',
            'date_time' => '2026/01/14',
            'location' => 'Parque Camba Cua',
            'has_fair' => true,
            'capacity' => 150
        ]);
    }
}
