<?php

namespace App\Domains\IncomeReport\Http\Controller;

use App\Domains\IncomeReport\Http\Actions\DeleteIncomeReportAction;
use App\Domains\IncomeReport\Http\Actions\IncomeReportAction;
use App\Domains\IncomeReport\Http\Actions\RestoreIncomeReportAction;
use App\Domains\IncomeReport\Http\Actions\StoreIncomeReportAction;
use App\Domains\IncomeReport\Http\Actions\UpdateIncomeReportAction;
use App\Domains\IncomeReport\Http\Requests\DeleteIncomeReportRequest;
use App\Domains\IncomeReport\Http\Requests\IncomeReportRequest;
use App\Domains\IncomeReport\Http\Requests\IncomeReportToCustomerRequest;
use App\Domains\IncomeReport\Http\Requests\RestoreIncomeReportRequest;
use App\Domains\IncomeReport\Http\Requests\ShowIncomeReportRequest;
use App\Domains\IncomeReport\Http\Requests\StoreIncomeReportRequest;
use App\Domains\IncomeReport\Http\Requests\UpdateIncomeReportRequest;
use App\Domains\IncomeReport\Http\Resources\IncomeReportResource;
use App\Domains\IncomeReport\Http\Resources\ShowIncomeReportResource;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IncomeReportController extends Controller
{
    public function index(IncomeReportRequest $request, IncomeReportAction $incomeReportAction)
    {
        $data = $request->validated();
        $incomeReport = $incomeReportAction->execute($data, false);
        return response()->json($incomeReport);
    }

    public function withPagination(IncomeReportRequest $request, IncomeReportAction $incomeReportAction)
    {
        $data = $request->validated();
        $incomeReport = $incomeReportAction->execute($data, true);
        return response()->json($incomeReport);
    }
    public function store(StoreIncomeReportRequest $request, StoreIncomeReportAction $storeIncomeReportAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeIncomeReportAction) {
            $storeIncomeReportAction->execute($data);
        });
        return response()->json(['message' => __('Income Report created successfully.')], 201);
    }

    public function update(UpdateIncomeReportRequest $request, IncomeReport $incomeReport, UpdateIncomeReportAction $updateIncomeReportAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $incomeReport, $updateIncomeReportAction) {
            $updateIncomeReportAction->execute($data, $incomeReport);
        });
        return response()->json(['message' => __('Income Report updated successfully.')], 201);
    }

    public function destroy(DeleteIncomeReportRequest $request, IncomeReport $incomeReport, DeleteIncomeReportAction $deleteIncomeReportAction)
    {
        DB::transaction(function () use ($incomeReport, $deleteIncomeReportAction) {
            $deleteIncomeReportAction->execute($incomeReport);
        });
        return response()->json(['message' => __('Income Report deleted successfully.')], 201);
    }

    public function restore(RestoreIncomeReportRequest $request, IncomeReport $incomeReport, RestoreIncomeReportAction $restoreIncomeReportAction)
    {
        DB::transaction(function () use ($incomeReport, $restoreIncomeReportAction) {
            $restoreIncomeReportAction->execute($incomeReport);
        });
        return response()->json(['message' => __('Income Report restored successfully.')], 201);
    }

    public function show(ShowIncomeReportRequest $request, IncomeReport $incomeReport)
    {
        $incomeReport = new ShowIncomeReportResource($incomeReport);
        return response()->json($incomeReport);
    }

    public function toCustomer(IncomeReportToCustomerRequest $request, IncomeReportAction $incomeReportAction)
    {
        $data = $request->validated();
        $incomeReports = $incomeReportAction->execute($data, true);
        return response()->json($incomeReports);
    }

    public function download(ShowIncomeReportRequest $request, IncomeReport $incomeReport)
    {
        $pdfBinary = base64_decode($incomeReport->attachment);

        return response($pdfBinary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="irrf-' . $incomeReport->year . '.pdf"');
    }
}
