<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Ivan',
                'surname' => 'Ivanov',
                'lastname' => 'Ivanovich',
                'birth_date' => '2002-12-05',
                'email' => 'ivan12@gmail.com',
                'user_name' => 'ivan12',
                'created_at' => Carbon::now()->subYears(10),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Anton',
                'surname' => 'Antonov',
                'lastname' => 'Antonovich',
                'birth_date' => '1999-01-11',
                'email' => 'anton123@mail.ru',
                'user_name' => 'antonio',
                'created_at' => Carbon::now()->subYears(6),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Viktor',
                'surname' => 'Kruchin',
                'lastname' => 'Viktorovich',
                'birth_date' => '2004-03-28',
                'email' => 'vekts@gmail.com',
                'user_name' => 'vekts666',
                'created_at' => Carbon::now()->subYears(4),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Alena',
                'surname' => 'Bachaeva',
                'lastname' => 'Sergeevna',
                'birth_date' => '2001-10-22',
                'email' => 'alena@mail.ru',
                'user_name' => 'alenka',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now(),
            ]
        ]);

    }
}
