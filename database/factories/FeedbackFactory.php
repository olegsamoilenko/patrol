<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'topic' => $this->faker->sentence(),
            'text' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['Новий', 'Прочитано', 'В процесі', 'Виконано', 'Відхилено',])
        ];
    }
}
