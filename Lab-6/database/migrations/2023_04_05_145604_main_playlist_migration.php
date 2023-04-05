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
        DB::statement(
            "
            CREATE TRIGGER main_playlist_creator
            AFTER INSERT
            ON users
            FOR EACH ROW
            insert into playlists(user_id, title, created_at, updated_at) values(NEW.id, 'main', now(), now())
            "
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
