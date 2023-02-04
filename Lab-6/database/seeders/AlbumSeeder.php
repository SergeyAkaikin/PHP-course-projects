<?php

namespace Database\Seeders;

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
        DB::statement("insert into album(artist_id, title)
                                select id, 'Choose a miracle'
                                from artist
                                where user_name = 'nyusha'");
        DB::statement("insert into album_songs(album_id, song_id)
                                select  (select album.id as album_id from album where title = 'Choose a miracle') as album_id,
                                        (select song.id as song_id from song where title = 'Do not interrupt') as song_id");
        DB::statement("insert into album_songs(album_id, song_id)
                                select  (select album.id as album_id from album where title = 'Choose a miracle') as album_id,
                                        (select song.id as song_id from song where title = 'Choose a miracle')    as song_id");

    }
}
