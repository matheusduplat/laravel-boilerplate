<?php

namespace App\Domains\RequestManagement\Http\Actions;

use App\Domains\Customer\Model\Customer;
use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagement\Notifications\RequestManagementNotification;
use App\Domains\User\Model\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationRequestManagementAction
{
    public function execute(array $data, RequestManagement $requestManagement)
    {
        $changer = $requestManagement->getChanges();
        $isResponse = Arr::has($data, 'response');
        if (empty($changer) && !$isResponse) {
            return;
        }
        $message = "A Solicitação {$requestManagement->id} teve uma atualização.";

        if (Arr::hasAll($changer, ['status'])) {
            $status = StatusRequestManagement::from($changer['status']);
            $statusTranslation = $status->label();
            $message = "A Solicitação {$requestManagement->id} teve seu status alterado para {$statusTranslation}.";
        }
        if ($isResponse) {
            $message = "{$message} E tem uma observação nova.";
        }

        if (Auth::user() instanceof Customer) {
            $responsibles = $requestManagement->responsible()->pluck('user_id')->toArray();
            $users = User::whereIn('id', $responsibles)->get();
            return Notification::send($users, new RequestManagementNotification($message, $requestManagement));
        }

        return $requestManagement->customer->notify(new RequestManagementNotification($message, $requestManagement));
    }
}
