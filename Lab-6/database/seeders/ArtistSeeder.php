<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ArtistSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Maksim',
                'surname' => 'Tuchin',
                'lastname' => 'Valerievich',
                'birth_date' => '1998-09-20',
                'email' => 'tuchin12@gmail.com',
                'user_name' => 'tucha',
                'created_at' => Carbon::now()->subYears(10),
                'updated_at' => Carbon::now(),
                'is_artist' => true,
                'password' => Hash::make('secret'),
                'roles' => 3
            ],
            [
                'name' => 'Danil',
                'surname' => 'Rodkin',
                'lastname' => 'Vladimirovich',
                'birth_date' => '2000-12-01',
                'email' => 'spirit@mail.ru',
                'user_name' => 'spirit',
                'created_at' => Carbon::now()->subYears(6),
                'updated_at' => Carbon::now(),
                'is_artist' => true,
                'password' => Hash::make('secret'),
                'roles' => 3
            ],
            [
                'name' => 'Denis',
                'surname' => 'Saveliev',
                'lastname' => 'Anatolievich',
                'birth_date' => '1999-02-09',
                'email' => 'freedom@gmail.com',
                'user_name' => 'lil_freedom',
                'created_at' => Carbon::now()->subYears(4),
                'updated_at' => Carbon::now(),
                'is_artist' => true,
                'password' => Hash::make('secret'),
                'roles' => 3
            ],
            [
                'name' => 'Nyusha',
                'surname' => 'Vladimirovna',
                'lastname' => 'Shurochkina',
                'birth_date' => '1990-08-15',
                'email' => 'nyusha@gmail.ru',
                'user_name' => 'nyusha',
                'created_at' => Carbon::now()->subYears(2),
                'updated_at' => Carbon::now(),
                'is_artist' => true,
                'password' => Hash::make('secret'),
                'roles' => 3
            ]
        ]);
        for ($i = 5; $i <= 8; $i++) {
            DB::table('playlists')->insert([
                [
                    'user_id' => $i,
                    'title' => 'main',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
        }
    }
}
