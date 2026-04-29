<?php

namespace Database\Factories;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subtask>
 */
class SubtaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task' => $this->faker->sentence(5),
            'done' => $this->faker->randomElement([true, false]),
            'task_id' =>$this->faker->randomElement(Task::pluck('id')->toArray()),
        ];
    }
}
