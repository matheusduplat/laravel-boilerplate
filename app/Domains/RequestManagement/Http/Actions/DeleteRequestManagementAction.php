<?php

namespace App\Domains\RequestManagement\Http\Actions;

use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Trait\UploadFile;
use Illuminate\Support\Facades\Auth;

class DeleteRequestManagementAction
{
    use UploadFile;

    public function execute(RequestManagement $requestManagement)
    {
        $requestManagement->update([
            'status' => StatusRequestManagement::CANCELED,
            'deleted_by' => Auth::user()->name ?? null
        ]);
        // if ($requestManagement->attachment) {
        //     $this->DeleteFile($requestManagement->attachment);
        // }
        $requestManagement->delete();

        return $requestManagement;
    }
}
