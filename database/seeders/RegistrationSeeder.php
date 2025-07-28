<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Registration::firstOrCreate([
            'user_id' => 2,
            'event_id' => 1,
            'role_in_event' => 'customer',
            'status' => 'confirmed',
        ]);

        Registration::firstOrCreate([
            'user_id' => 3,
            'event_id' => 1,
            'role_in_event' => 'seller',
            'status' => 'confirmed',
        ]);
    }
}
