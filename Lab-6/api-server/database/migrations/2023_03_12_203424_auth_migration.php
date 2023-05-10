<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        DB::statement('alter table users add column password varchar(255)');
    }


    public function down(): void
    {
        DB::statement('alter table users drop column password');
    }
};
