<?php

namespace App\Domains\Bill\Http\Controller;

use App\Domains\Bill\Http\Actions\BillAction;
use App\Domains\Bill\Http\Actions\DeleteBillAction;
use App\Domains\Bill\Http\Actions\RestoreBillAction;
use App\Domains\Bill\Http\Actions\StoreBillAction;
use App\Domains\Bill\Http\Actions\UpdateBillAction;
use App\Domains\Bill\Http\Requests\BillRequest;
use App\Domains\Bill\Http\Requests\BillToCustomerRequest;
use App\Domains\Bill\Http\Requests\DeleteBillRequest;
use App\Domains\Bill\Http\Requests\RestoreBillRequest;
use App\Domains\Bill\Http\Requests\ShowBillRequest;
use App\Domains\Bill\Http\Requests\StoreBillRequest;
use App\Domains\Bill\Http\Requests\UpdateBillRequest;
use App\Domains\Bill\Http\Resources\BillResource;
use App\Domains\Bill\Http\Resources\ShowBillResource;
use App\Domains\Bill\Model\Bill;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function store(StoreBillRequest $request, StoreBillAction $storeBillAction)
    {
        $data = $request->validated();
        $storeBillAction->execute($data);
        return response()->json(['message' => __('Bill created successfully.')], 201);
    }
    public function update(UpdateBillRequest $request, Bill $bill, UpdateBillAction $updateBillAction)
    {
        $data = $request->validated();
        $updateBillAction->execute($data, $bill);
        return response()->json(['message' => __('Bill updated successfully.')], 201);
    }
    public function destroy(DeleteBillRequest $request, Bill $bill, DeleteBillAction $deleteBillAction)
    {
        $deleteBillAction->execute($bill);
        return response()->json(['message' => __('Bill deleted successfully.')], 201);
    }
    public function restore(RestoreBillRequest $request, Bill $bill, RestoreBillAction $restoreBillAction)
    {
        $restoreBillAction->execute($bill);
        return response()->json(['message' => __('Bill restored successfully.')], 201);
    }
    public function show(ShowBillRequest $request, Bill $bill)
    {
        $bill = new ShowBillResource($bill);
        return response()->json($bill);
    }
    public function index(BillRequest $request, BillAction $billAction)
    {
        $data = $request->validated();
        $bills = $billAction->execute($data, false);
        return response()->json($bills);
    }
    public function withPagination(BillRequest $request, BillAction $billAction)
    {
        $data = $request->validated();
        $bills = $billAction->execute($data, true);
        return response()->json($bills);
    }
    public function toCustomer(BillToCustomerRequest $request, BillAction $billAction)
    {
        $data = $request->validated();
        $bills = $billAction->execute($data, true);
        return response()->json($bills);
    }
    public function download(ShowBillRequest $request, Bill $bill)
    {
        $pdfBinary = base64_decode($bill->base64);

        return response($pdfBinary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="boleto-{$bill->base_month}.pdf"');
    }
}
