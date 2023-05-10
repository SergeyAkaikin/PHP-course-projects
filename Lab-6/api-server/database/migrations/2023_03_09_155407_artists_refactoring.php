<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('alter table users add column is_artist boolean default false');
        DB::statement(
            'insert into users (name, surname, lastname, birth_date, email, user_name, created_at, updated_at, deleted_at, is_artist)
select name, surname, lastname, birth_date, email, user_name, created_at, updated_at, deleted_at, true from artists'
        );
        DB::statement('alter table songs drop foreign key songs_artist_id_foreign');
        DB::statement('alter table albums drop foreign key albums_artist_id_foreign');
        DB::statement('alter table songs add foreign key (artist_id) references users (id) on delete cascade');
        DB::statement('alter table albums add foreign key (artist_id) references users (id) on delete cascade');
        DB::statement('drop table artists');

    }


    public function down(): void
    {

    }
};
