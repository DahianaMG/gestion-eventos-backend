<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::firstOrCreate([
            'user_id' => '2',
            'event_id' => '1',
            'stand_name' => 'Comics Corrientes',
            'stand_description' => 'Venta de comics y figuras coleccionables',
            'stand_location' => 'Plataforma 9 3/4'
        ]);

        Vendor::firstOrCreate([
            'user_id' => '3',
            'event_id' => '2',
            'stand_name' => 'Sushi y torta frita',
            'stand_description' => 'Venta de comida nacional y japonesa',
            'stand_location' => 'Sala 3,14'
        ]);
    }
}
