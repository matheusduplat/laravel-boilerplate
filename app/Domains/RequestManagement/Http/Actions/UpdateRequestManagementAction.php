<?php

namespace App\Domains\RequestManagement\Http\Actions;

use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Trait\UploadFile;
use Illuminate\Support\Facades\Auth;

class UpdateRequestManagementAction
{
    use UploadFile;

    public function execute(array $data, RequestManagement $requestManagement)
    {
        $data['updated_by'] = Auth::user()->name ?? null;

        if ($data['type'] != TypeRequestManagement::CONSULTATION->name && isset($data['attachment']) && gettype($data['attachment']) != 'string') {
            $path = "{$requestManagement['customer_id']}/request_management/attachment";

            if ($requestManagement->attachment) {
                $this->DeleteFile($requestManagement->attachment);
            }

            $data['attachment'] = $this->UploadFile($data['attachment'], $path);
        }

        if ($data['status'] == StatusRequestManagement::APPROVED->name || $data['status'] == StatusRequestManagement::REJECTED->name) {
            $data['date_close'] = now();
        }

        if ($data['status'] == StatusRequestManagement::CANCELED->name) {
            $data['deleted_at'] = now();
            $data['deleted_by'] = Auth::user()->name ?? null;
        }

        if (isset($data['responsible'])) {
            $requestManagement->auditSync('responsible', $data['responsible']);
        }

        $requestManagement->update($data);


        return $requestManagement;
    }
}
