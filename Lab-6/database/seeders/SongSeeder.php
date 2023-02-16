<?php

namespace Database\Seeders;

use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created = Carbon::now();
        $updated = Carbon::now();
        Song::factory()->create(['artist_id' => 1, 'title' => 'rain', 'genre' => 'rock', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 2, 'title' => 'fire', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 3, 'title' => 'maybe', 'genre' => 'rap', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 4, 'title' => 'Do not interrupt', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 4, 'title' => 'Choose a miracle', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 4, 'title' => 'I melt', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);

    }
}
