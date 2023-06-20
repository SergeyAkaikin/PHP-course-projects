<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Album;
use Carbon\Carbon;
use Database\Factories\AlbumSongFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumSeeder extends Seeder
{

    public function run(): void
    {
        $created = Carbon::now()->subYears(1);
        $updated = Carbon::now();
        Album::factory()->create(['artist_id' => 8, 'title' => 'Choose a miracle', 'created_at' => $created, 'updated_at' => $updated, 'rating' => 74]);

        DB::statement("insert into album_songs(album_id, song_id, created_at, updated_at)
                                select  (select albums.id as album_id from albums where title = 'Choose a miracle') as album_id,
                                        (select songs.id as song_id from songs where title = 'Do not interrupt') as song_id,
                                        '{$created}', '{$updated}'");
        DB::statement("insert into album_songs(album_id, song_id, created_at, updated_at)
                                select  (select albums.id as album_id from albums where title = 'Choose a miracle') as album_id,
                                        (select songs.id as song_id from songs where title = 'Choose a miracle')    as song_id,
                                        '{$created}', '{$updated}'");

        Album::factory()->create(['rating' => 65]);
        DB::table('album_songs')->insert(
            [
                [
                    'song_id' => 3,
                    'album_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'song_id' => 4,
                    'album_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]
        );

    }
}
