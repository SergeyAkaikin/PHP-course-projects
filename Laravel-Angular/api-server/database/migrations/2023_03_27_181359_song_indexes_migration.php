<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('create index songs_id on songs (id)');
        DB::statement('create index songs_artists_id_index on songs (artist_id)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('drop index songs_id on albums');
        DB::statement('drop index songs_artists_id_index on albums');
    }
};
