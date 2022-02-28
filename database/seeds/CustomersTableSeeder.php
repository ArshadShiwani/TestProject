<?php

use Illuminate\Database\Seeder;
use App\Customer;
use Faker\Factory;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Customer::truncate();

        foreach(range(10, 5000) as $i) {
            Customer::create([
                'client' => $faker->firstname,
                'email' => $faker->safeEmail,
                'nici'=>$faker->phoneNumber,
                'ntn_strn'=>$faker->bankAccountNumber,
                'phone'=>$faker->e164PhoneNumber,
                'type'=>'salestax',


            ]);
        }
    }
}
