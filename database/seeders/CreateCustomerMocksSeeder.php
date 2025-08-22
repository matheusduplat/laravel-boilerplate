<?php

namespace Database\Seeders;

use App\Domains\Address\Model\Address;
use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Model\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateCustomerMocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $base64 = file_get_contents(public_path('fake/pdf_base64.txt'));
        $customers =  Customer::factory()
            ->count(200)
            ->has(Phone::factory()->count(2), 'phones')
            ->has(Address::factory(), 'address')
            ->customer()
            ->create([
                'password' => '12345678',
            ]);
        $customers->random(5)->each(function ($customer) use ($base64) {
            Bill::factory()
                ->count(2)
                ->for($customer, 'customer') // garante que customer_id seja preenchido
                ->create([
                    'base64'     => $base64,
                ]);
        });
    }
}
