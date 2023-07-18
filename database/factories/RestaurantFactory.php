<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    private static $vendor_id = 10192;

    public function definition()
    {
        return [
            'name' => $this->faker->firstname(),
            'email' => $this->faker->unique()->safeEmail().self::$vendor_id++,
            'phone' => $this->faker->unique()->phoneNumber().self::$vendor_id++,
            'logo' => $this->faker->lastname(),
            'cover_photo' => $this->faker->lastname(),
            'address' => $this->faker->lastname(),
            'latitude' =>  '20.323554',
            'longitude' =>  '80.323554',
            'vendor_id' =>  self::$vendor_id++,
            'zone_id' =>  1,
            'tax' =>  1,
            'restaurant_model' =>  'none',
            'delivery_time' =>  '10-34',

        ];
    }
}
