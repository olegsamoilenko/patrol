<?php

namespace Database\Factories;

use App\Models\Incident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $document = $this->faker->randomLetter().
            $this->faker->randomLetter().
            $this->faker->randomNumber(6, true);

        $carNumber = $this->faker->randomLetter().
            $this->faker->randomLetter().
            $this->faker->randomNumber(4, true).
            $this->faker->randomLetter().
            $this->faker->randomLetter();

        $documentType = $this->faker->randomElement(['Паспорт', 'ID Картка', 'Посвідчення водія', 'Інше']);

        return [
            'patrol' => fake()->numberBetween(1, 10),
            'address' => fake()->address(),
            'name' => fake()->name($gender = null),
            'document_type' => $documentType,
            'document_type_other' => $documentTypeOther = 'Інше' === $documentType ? fake()->word() : null,
            'document' => $document,
            'car_number' => $carNumber,
            'car_type' => fake()->randomElement(
                ['Купе',
                    'Спортивна', 'Седан', 'Хетчбек', 'Універсал', 'Кроссовер', 'Позашляховик',
                    'Пікап', 'Мікроавтобус вантажний', 'Мікроавтобус пассажирский',
                    'Автобус', 'Вантажівка', 'Фура', 'Таксі', 'Мотоцикл', 'Мікро', 'Кабріолет', ]
            ),
            'car_brand' => fake()->randomElement(
                ['Acura',
                    'Alfa Romeo',
                    'Audi',
                    'BMW',
                    'Cadillac',
                    'Chery',
                    'Chevrolet',
                    'Chrysler',
                    'Citroen',
                    'Dacia',
                    'Daewoo',
                    'DAF',
                    'Dodge',
                    'Fiat',
                    'Ford',
                    'Geely',
                    'Honda',
                    'Hyundai',
                    'Infinity',
                    'Iveco',
                    'Jaguar',
                    'Jeep',
                    'Kia',
                    'Lada',
                    'Land Rover',
                    'Lexus',
                    'MAN',
                    'Mazda',
                    'Mercedes',
                    'Mini',
                    'Mitsubishi',
                    'Nissan',
                    'Opel',
                    'Peugeot',
                    'Porsche',
                    'Renault',
                    'Rover',
                    'Saab',
                    'Seat',
                    'Skoda',
                    'Smart',
                    'SsangYong',
                    'Subaru',
                    'Suzuki',
                    'Tesla',
                    'Toyota',
                    'Volkswagen',
                    'Volvo',
                    'ВАЗ',
                    'ГАЗ',
                    'ЗАЗ',
                    'УАЗ',
                    'Нива',
                    'Москвич',
                    'ЗИЛ',
                    'Інша',]
            ),
            'car_model' => fake()->word(),
            'car_color' => fake()->randomElement(
                ['#000000', '#ffffff', '#6464F6', '#F63F3C', '#4DF627', '#006262', '#F4CA16']
            ),
            'comment' => fake()->text(100),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
