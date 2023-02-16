<?php

namespace Database\Seeders;

use App\Models\Album;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created = Carbon::now()->subYears(1);
        $updated = Carbon::now();
        Album::factory()->create(['artist_id' => 4, 'title' => 'Choose a miracle', 'created_at' => $created, 'updated_at' => $updated]);

        DB::statement("insert into album_songs(album_id, song_id, created_at, updated_at)
                                select  (select albums.id as album_id from albums where title = 'Choose a miracle') as album_id,
                                        (select songs.id as song_id from songs where title = 'Do not interrupt') as song_id,
                                        '{$created}', '{$updated}'");
        DB::statement("insert into album_songs(album_id, song_id, created_at, updated_at)
                                select  (select albums.id as album_id from albums where title = 'Choose a miracle') as album_id,
                                        (select songs.id as song_id from songs where title = 'Choose a miracle')    as song_id,
                                        '{$created}', '{$updated}'");

    }
}
