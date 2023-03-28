<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement('create index albums_id_index on albums (id)');
        DB::statement('create index albums_artists_id_index on albums (artist_id)');
        DB::statement('create index album_songs_id on album_songs (album_id)');
    }


    public function down()
    {
        DB::statement('drop index albums_id_index on albums');
        DB::statement('drop index albums_artists_id_index on albums');
        DB::statement('drop index album_songs_id on albums');
    }
};
