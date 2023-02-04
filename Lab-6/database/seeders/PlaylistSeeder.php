<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("insert into playlist(title, user_id)
                                select 'my_playlist', id from user where user_name='ivan12'");
        DB::statement("insert into playlist_songs(playlist_id, song_id) VALUES (1, 3)");
    }
}
