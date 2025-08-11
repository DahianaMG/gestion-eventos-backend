<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityParticipant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ActivityParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivityParticipant::firstOrCreate([
            'schedule_id' => 1,
            'user_id' => 1,
            'display_name' => 'Leonardo Da Vinci',
        ]);

        ActivityParticipant::firstOrCreate([
            'schedule_id' => 4,
            'user_id' => 2,
            'display_name' => 'Gustavo Cerati',
        ]);
    }
}
