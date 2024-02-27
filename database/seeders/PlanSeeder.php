<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'stripe_plan' => 'price_1Mp0C5IJDAURzXRvJUXYDWQu', //'price_1LXOzsGzlk2XAanfTskz9n',
                'price' => 10,
                'description' => 'Basic'
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }

    }
}
