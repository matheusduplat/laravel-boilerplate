<?php

namespace Database\Seeders;

use App\Domains\Address\Model\Address;
use App\Domains\Bill\Model\Bill;
use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\Customer\Model\Customer;
use App\Domains\DigitalWallet\Model\DigitalWallet;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\Phone\Model\Phone;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Domains\User\Model\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateCustomerMocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            $base64 = file_get_contents(public_path('fake/pdf_base64.txt'));
            $customers =  Customer::factory()
                ->count(200)
                ->has(Phone::factory()->count(2), 'phones')
                ->has(Address::factory(), 'address')
                ->customerRole()
                ->create([
                    'password' => '12345678',
                ]);
            $user = User::query()->first();
            // $customers->random(5)->each(function ($customer) use ($base64, $user) {

            // });
        });
    }
}
