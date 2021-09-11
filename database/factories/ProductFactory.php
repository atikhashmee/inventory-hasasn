<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        'description' => $this->faker->text,
        'product_cost' => $this->faker->word,
        'selling_price' => $this->faker->word,
        'category_id' => $this->faker->randomDigitNotNull,
        'origin' => $this->faker->randomDigitNotNull,
        'brand_id' => $this->faker->randomDigitNotNull,
        'menufacture_id' => $this->faker->randomDigitNotNull,
        'feature_image' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
