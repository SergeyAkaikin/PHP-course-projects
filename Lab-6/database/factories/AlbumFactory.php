<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'artist_id' => rand(5, 8),
            'title' => fake()->title(),
            'created_at' => fake()->date(),
            'updated_at' => Carbon::now(),
            'folder_id' => uniqid(more_entropy: true),
            'rating' => rand(0, 100),
        ];
    }
}
