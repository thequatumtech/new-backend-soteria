<?php

namespace Database\Seeders;

use App\Models\LineOfBusiness;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LineOfBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LineOfBusiness::create([
            'name' => 'Critical Illness Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Life Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Home Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Office Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Medical Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Motor Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Dental Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Marine Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Personal Accidents Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Travel Insurance',
        ]);

        LineOfBusiness::create([
            'name' => 'Pets Insurance',
        ]);
    }
}
