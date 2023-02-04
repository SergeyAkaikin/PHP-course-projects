<?php

namespace Database\Seeders;

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
        DB::statement("insert into song(artist_id, title, genre)
                        select id, 'rain', 'rock'
                        from artist
                        where user_name = 'tucha'");
        DB::statement("insert into song(artist_id, title, genre)
                                select id, 'fire', 'pop'
                                from artist
                                where user_name = 'spirit'");
        DB::statement("insert into song(artist_id, title, genre)
                                select id, 'maybe', 'rap'
                                from artist
                                where user_name = 'lil_freedom'");
        DB::statement("insert into song(artist_id, title, genre)
                                select id, 'Do not interrupt', 'pop'
                                from artist
                                where user_name = 'nyusha'");
        DB::statement("insert into song(artist_id, title, genre)
                                select id, 'Choose a miracle', 'pop'
                                from artist
                                where user_name = 'nyusha'");
        DB::statement("insert into song(artist_id, title, genre)
                                select id, 'I melt', 'pop'
                                from artist
                                where user_name = 'nyusha'");

    }
}
