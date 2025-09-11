<?php

namespace App\Domains\Copaticipation\Http\Controller;

use App\Domains\Copaticipation\Http\Actions\CopaticipationAction;
use App\Domains\Copaticipation\Http\Actions\DeleteCopaticipationAction;
use App\Domains\Copaticipation\Http\Actions\RestoreCopaticipationAction;
use App\Domains\Copaticipation\Http\Actions\StoreCopaticipationAction;
use App\Domains\Copaticipation\Http\Actions\UpdateCopaticipationAction;
use App\Domains\Copaticipation\Http\Requests\CopaticipationRequest;
use App\Domains\Copaticipation\Http\Requests\CopaticipationToCustomerRequest;
use App\Domains\Copaticipation\Http\Requests\DeleteCopaticipationRequest;
use App\Domains\Copaticipation\Http\Requests\RestoreCopaticipationRequest;
use App\Domains\Copaticipation\Http\Requests\ShowCopaticipationRequest;
use App\Domains\Copaticipation\Http\Requests\StoreCopaticipationRequest;
use App\Domains\Copaticipation\Http\Requests\UpdateCopaticipationRequest;
use App\Domains\Copaticipation\Http\Resources\ShowCopaticipationResource;
use App\Domains\Copaticipation\Model\Copaticipation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CopaticipationController extends Controller
{
    public function store(StoreCopaticipationRequest $request, StoreCopaticipationAction $storeCopaticipationAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $storeCopaticipationAction) {
            $storeCopaticipationAction->execute($data);
        });
        return response()->json(['message' => __('Copaticipation created successfully.')], 201);
    }
    public function update(UpdateCopaticipationRequest $request, Copaticipation $copaticipation, UpdateCopaticipationAction $updateCopaticipationAction)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $copaticipation, $updateCopaticipationAction) {
            $updateCopaticipationAction->execute($data, $copaticipation);
        });
        return response()->json(['message' => __('Copaticipation updated successfully.')], 201);
    }
    public function destroy(DeleteCopaticipationRequest $request, Copaticipation $copaticipation, DeleteCopaticipationAction $deleteCopaticipationAction)
    {
        DB::transaction(function () use ($copaticipation, $deleteCopaticipationAction) {
            $deleteCopaticipationAction->execute($copaticipation);
        });
        return response()->json(['message' => __('Copaticipation deleted successfully.')], 201);
    }
    public function restore(RestoreCopaticipationRequest $request, Copaticipation $copaticipation, RestoreCopaticipationAction $restoreCopaticipationAction)
    {
        DB::transaction(function () use ($copaticipation, $restoreCopaticipationAction) {
            $restoreCopaticipationAction->execute($copaticipation);
        });
        return response()->json(['message' => __('Copaticipation restored successfully.')], 201);
    }
    public function index(CopaticipationRequest $request, CopaticipationAction $copaticipationAction)
    {
        $data = $request->validated();
        $copaticipations = $copaticipationAction->execute($data, false);
        return response()->json($copaticipations);
    }
    public function withPagination(CopaticipationRequest $request, CopaticipationAction $copaticipationAction)
    {
        $data = $request->validated();
        $copaticipations = $copaticipationAction->execute($data, true);
        return response()->json($copaticipations);
    }
    public function toCustomer(CopaticipationToCustomerRequest $request, CopaticipationAction $copaticipationAction)
    {
        $data = $request->validated();
        $copaticipations = $copaticipationAction->execute($data, true);
        return response()->json($copaticipations);
    }
    public function show(ShowCopaticipationRequest $request, Copaticipation $copaticipation)
    {
        $copaticipation = new ShowCopaticipationResource($copaticipation);
        return response()->json($copaticipation);
    }
    public function download(ShowCopaticipationRequest $request, Copaticipation $copaticipation)
    {
        $pdfBinary = base64_decode($copaticipation->base64);

        return response($pdfBinary, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="copaticipation-' . $copaticipation->base_month . '.pdf"');
    }
}
