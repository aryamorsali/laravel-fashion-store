<?php

namespace Database\Factories\Market;

use App\Models\Market\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = City::inRandomOrder()->first();

        $address = [
            'No. 45, Valiasr St, near Parkway',
            'No. 12, Azadi St, next to Metro Station',
            'No. 88, Shariati Ave, Zafar St',
            'No. 24, Karimkhan Blvd, Aban Alley',
            'No. 102, Saadi St, Baharestan Sq',
            'No. 15, Ferdowsi Ave, Koushk Alley',
            'No. 7, Molla Sadra St, Shiraz St',
            'No. 53, Beheshti Ave, Sabonchi St',
            'No. 19, Enghelab St, Palestine Sq',
            'No. 31, Keshavarz Blvd, 16 Azar St',
        ];
        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'province_id' => $city ? $city->province_id : 1,
            'city_id' => $city ? $city->id : 1,
            'postal_code' => rand(1000000000, 9999999999),
            'address' => $this->faker->randomElement($address),
            'no' =>  rand(1, 150),
            'unit' =>  rand(1, 20),
            'recipient_name' => $this->faker->name(),
            'mobile' => $this->faker->regexify('^09(12|19|30|35|36|37|38|39|90|21)\d{7}$'),
        ];
    }
}
