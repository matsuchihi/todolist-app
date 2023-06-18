<?php

namespace Database\Factories;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=> fake()->title(),
            'folder_id'=> function () {
                return Folder::factory()->create()->id;
            },
            'due_date'=> fake()->date(),
            'status'=>fake()->numberBetween(1,3),

            //
        ];
    }
}
