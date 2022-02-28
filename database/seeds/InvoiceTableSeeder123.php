<?php

use Illuminate\Database\Seeder;
use App\Invoice;
use App\InvoiceAmount;
use Faker\Factory;
class InvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Invoice::truncate();

        foreach(range(1, 4) as $i) {
            $amount= mt_rand(1000, 2000);

            Invoice::create([
                'number' => 'INV-2000'.$i,
                'customer_id' => $i,
                'date' => '2017-12-'.$i,
                'sub_total' =>$amount,
                'month' => mt_rand(1, 12)
            ]);

            InvoiceAmount::create([
                'invoice_number' => 'INV-2000'.$i,
                'amount_pay' => 0,
                'amount_left' =>$amount,
                'date' => '2017-12-'.$i
            ]);
            

        }
    }
}
