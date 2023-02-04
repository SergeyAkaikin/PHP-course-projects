<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('lastname');
            $table->date('birth_date');
            $table->string('email');
            $table->string('user_name')->unique();
        });
        Schema::create('artist', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('lastname');
            $table->date('birth_date');
            $table->string('email');
            $table->string('user_name')->unique();
        });
        Schema::create('song', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'artist_id')
                ->references('id')
                ->on('artist')
                ->onDelete('cascade');
            $table->string('title');
            $table->string('genre')->nullable();
        });
        Schema::create('album', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')
                ->references('id')
                ->on('artist')
                ->onDelete('cascade');
            $table->string('title');
        });
        Schema::create('album_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')
                ->references('id')
                ->on('album')
                ->onDelete('cascade');
            $table->foreignId('song_id')
                ->references('id')
                ->on('song')
                ->onDelete('cascade');
        });
        Schema::create('playlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->string('title');
        });
        Schema::create('playlist_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')
                ->references('id')
                ->on('playlist')
                ->onDelete('cascade');
            $table->foreignId('song_id')
                ->references('id')
                ->on('song');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
        Schema::dropIfExists('artist');
        Schema::dropIfExists('song');
        Schema::dropIfExists('album');
        Schema::dropIfExists('album_songs');
        Schema::dropIfExists('playlist');
        Schema::dropIfExists('playlist_songs');
    }
};
