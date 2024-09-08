<?php

namespace Database\Seeders;

use App\Models\MasterWorkResultType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterWorkResultTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'key' => 'BOOL',
                'value' => 'Yes/No'
            ],
            [
                'key' => 'INT',
                'value' => 'Số nguyên'
            ],
            [
                'key' => 'DBL',
                'value' => 'Số thập phân'
            ],
            [
                'key' => 'PCT',
                'value' => '%'
            ],
            [
                'key' => 'TXT',
                'value' => 'Text'
            ],
        ];
        MasterWorkResultType::truncate();
        foreach ($data as $item) {
            $model = new MasterWorkResultType();
            $model->fill($item);
            $model->save();
        }
    }
}
