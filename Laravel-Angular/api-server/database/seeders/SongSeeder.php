<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{

    public function run(): void
    {
        $created = Carbon::now();
        $updated = Carbon::now();
        Song::factory()->create(['artist_id' => 5, 'title' => 'rain', 'genre' => 'rock', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 6, 'title' => 'fire', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 7, 'title' => 'maybe', 'genre' => 'rap', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 8, 'title' => 'Do not interrupt', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 8, 'title' => 'Choose a miracle', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);
        Song::factory()->create(['artist_id' => 8, 'title' => 'I melt', 'genre' => 'pop', 'created_at' => $created, 'updated_at' => $updated]);

    }
}
