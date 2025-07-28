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
    }
}
