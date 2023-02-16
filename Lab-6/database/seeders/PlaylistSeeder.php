<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Carbon\Carbon;
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
        $created = Carbon::now()->subYear();
        $updated = Carbon::now();
        Playlist::factory()->create(['title' => 'my_playlist', 'user_id' => 1, 'created_at' => $created, 'updated_at' => $updated]);
        DB::statement("insert into playlist_songs(playlist_id, song_id, created_at, updated_at) VALUES (1, 3, '{$created}', '{$updated}')");
    }
}
