<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        DB::statement('alter table users add column password varchar(255)');
    }


    public function down(): void
    {
        DB::statement('alter table users drop column password');
    }
};
