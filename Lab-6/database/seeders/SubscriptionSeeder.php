<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{

    public function run()
    {
        DB::table('subscriptions')->insert([
            'price' => 400,
            'discount' => 0,
            'next_billing_time' => Carbon::now()->addMonth(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
