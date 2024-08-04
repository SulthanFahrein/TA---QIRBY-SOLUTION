<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criterion;

class CriterionSeeder extends Seeder
{
    public function run()
    {
        $criteria = ['Facility', 'Access', 'Distance', 'Price'];

        foreach ($criteria as $criterion) {
            Criterion::create(['name' => $criterion]);
        }
    }
}
