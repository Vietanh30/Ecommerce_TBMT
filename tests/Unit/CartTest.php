<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'title' => $this->faker->word(), // Faker tạo ra một từ ngẫu nhiên cho title
            'stock' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            
        ];
    }
}
