<?php

namespace App\Providers;

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use App\Domains\Address\Model\Address;
use App\Domains\Bill\Model\Bill;
use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\Contact\Model\Contact;
use App\Domains\ContactResponse\Model\ContactResponse;
use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\Customer\Model\Customer;
use App\Domains\DigitalWallet\Model\DigitalWallet;
use App\Domains\Employee\Model\Employee;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\Permission\Model\Permission;
use App\Domains\Phone\Model\Phone;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Domains\Role\Model\Role;
use App\Domains\TrustedDevice\Model\TrustedDevice;
use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class MorphMapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'User' => User::class,
            "Employee" => Employee::class,
            "Phone" => Phone::class,
            "TrustedDevice" => TrustedDevice::class,
            "Role" => Role::class,
            "Customer" => Customer::class,
            "CodeVerification" => CodeVerification::class,
            "Permission" => Permission::class,
            "Address" => Address::class,
            "Bill" => Bill::class,
            "IncomeReport" => IncomeReport::class,
            "Copaticipation" => Copaticipation::class,
            'DigitalWallet' => DigitalWallet::class,
            "AccreditedNetworks" => AccreditedNetworks::class,
            "RequestManagement" => RequestManagement::class,
            "RequestManagementResponse" => RequestManagementResponse::class,
            "Contact" => Contact::class,
            "ContactResponse" => ContactResponse::class
        ]);
    }
}
