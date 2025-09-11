<?php

namespace App\Domains\RequestManagement\Http\Actions;

use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Trait\UploadFile;
use Illuminate\Support\Facades\Auth;

class StoreRequestManagementAction
{
    use UploadFile;

    public function execute(array $data)
    {
        $data['created_by'] = Auth::user()->name ?? null;
        $data['status'] = isset($data['status']) ? $data['status'] : StatusRequestManagement::WAITING;


        if ($data['type'] != TypeRequestManagement::CONSULTATION->name) {
            $path = "{$data['customer_id']}/request_management/attachment";
            $data['attachment'] = $this->UploadFile($data['attachment'], $path);
        }

        $requestManagement = RequestManagement::create([
            ...$data,
            "code" => "SO"
        ]);

        $requestManagement->auditAttach('responsible', $data['responsible'] ?? []);

        $requestManagement->updateQuietly([
            "code" => "SO{$requestManagement->created_at->format('Y.m')}.{$requestManagement->id}"
        ]);

        return $requestManagement;
    }
}
