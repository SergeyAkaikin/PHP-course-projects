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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('lastname');
            $table->date('birth_date');
            $table->string('email');
            $table->string('user_name')->unique();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('lastname');
            $table->date('birth_date');
            $table->string('email');
            $table->string('user_name')->unique();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'artist_id')
                ->references('id')
                ->on('artists')
                ->onDelete('cascade');
            $table->string('title');
            $table->string('genre')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')
                ->references('id')
                ->on('artists')
                ->onDelete('cascade');
            $table->string('title');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('album_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')
                ->references('id')
                ->on('albums')
                ->onDelete('cascade');
            $table->foreignId('song_id')
                ->references('id')
                ->on('songs')
                ->onDelete('cascade');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('title');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
        Schema::create('playlist_songs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')
                ->references('id')
                ->on('playlists')
                ->onDelete('cascade');
            $table->foreignId('song_id')
                ->references('id')
                ->on('songs');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('artists');
        Schema::dropIfExists('songs');
        Schema::dropIfExists('albums');
        Schema::dropIfExists('album_songs');
        Schema::dropIfExists('playlists');
        Schema::dropIfExists('playlist_songs');
    }
};
