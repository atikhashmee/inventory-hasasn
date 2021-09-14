<?php

namespace Database\Factories;

use App\Models\Challan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChallanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Challan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'shop_id' => $this->faker->randomDigitNotNull,
        'customer_id' => $this->faker->randomDigitNotNull,
        'product_type' => $this->faker->word,
        'quantity' => $this->faker->randomDigitNotNull,
        'unit_id' => $this->faker->randomDigitNotNull,
        'total_payable' => $this->faker->word,
        'challan_note' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
