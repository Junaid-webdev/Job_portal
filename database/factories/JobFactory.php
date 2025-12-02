<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'user_id' => rand(1,3),
            'job_type_id' => rand(1,5),
            'category_id' => rand(1,5),  // FIXED
            'vacancy' => rand(1,5),
            'salary' => rand(20000,90000),
            'location' => $this->faker->city(),
            'description' => $this->faker->paragraph(),
            'experience' => rand(1,10),
            'company_name' => $this->faker->company(),
            'company_location' => $this->faker->city(),   // REQUIRED FIELD ADDED
            'company_website' => $this->faker->url(),     // REQUIRED FIELD ADDED
        ];
    }
}
