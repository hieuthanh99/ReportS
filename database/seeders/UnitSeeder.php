<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['name' => 'Doanh nghiệp'],
            ['name' => '%'],
            ['name' => 'DVC'],
            ['name' => 'Hệ thống'],
            ['name' => 'Cơ quan']
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}

