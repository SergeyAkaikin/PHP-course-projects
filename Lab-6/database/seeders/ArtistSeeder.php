<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('artist')->insert([
            [
                'name' => 'Maksim',
                'surname' => 'Tuchin',
                'lastname' => 'Valerievich',
                'birth_date' => '1998-09-20',
                'email' => 'tuchin12@gmail.com',
                'user_name' => 'tucha'
            ],
            [
                'name' => 'Danil',
                'surname' => 'Rodkin',
                'lastname' => 'Vladimirovich',
                'birth_date' => '2000-12-01',
                'email' => 'spirit@mail.ru',
                'user_name' => 'spirit'
            ],
            [
                'name' => 'Denis',
                'surname' => 'Saveliev',
                'lastname' => 'Anatolievich',
                'birth_date' => '1999-02-09',
                'email' => 'freedom@gmail.com',
                'user_name' => 'lil_freedom'
            ],
            [
                'name' => 'Nyusha',
                'surname' => 'Vladimirovna',
                'lastname' => 'Shurochkina',
                'birth_date' => '1990-08-15',
                'email' => 'nyusha@gmail.ru',
                'user_name' => 'nyusha'
            ]
        ]);
    }
}
