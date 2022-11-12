<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Models\Incident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IncidentController extends Controller
{
    public Incident $incident;

    public function __construct()
    {
        $this->incident = new Incident();
        $this->middleware('can:incident list', ['only' => ['getIncidentsPagination']]);
        $this->middleware('can:incident create', ['only' => ['storeIncident']]);
//        $this->middleware('can:incident edit', ['only' => ['edit', 'update']]);
//        $this->middleware('can:incident delete', ['only' => ['destroy']]);
    }

    /**
     * Get Incident Statistics.
     */
    public function getIncidentStatistics(): JsonResponse
    {
        return response()->json([
            'incidentCount' => $this->incident->getCount(),
            'todayIncidentCount' => $this->incident->getTodayIncidentCount(),
            'lastWeekIncidentCount' => $this->incident->getLastWeekIncidentCount(),
            'lastMonthIncidentCount' => $this->incident->getLastMonthIncidentCount(),
        ], 201);
    }

    /**
     * Get Incident Pagination.
     */
    public function getIncidentsPagination(Request $request): JsonResponse
    {
        $incidents = Incident::when($request->patrol, static function ($query, $patrol) {
            if ('Всі патрулі' === $patrol) {
                return $query;
            }
            $query->patrol($patrol);
        })
            ->when($request->search, static function ($query, $search) {
                return $query->search($search);
            })
            ->with('media')->orderBy($request->sortBy, $request->sortDirection)->paginate(10);

        return response()->json([
            'incidents' => $incidents,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeIncident(StoreIncidentRequest $request): JsonResponse
    {
        $data = $request->validated();
        DB::transaction(static function () use ($data, $request) {
            $incident = Incident::create($data);
            if ($request->files) {
                foreach ($request->files as $file) {
                    if ('image/png' !== $file->getClientMimeType()
                        && 'image/jpg' !== $file->getClientMimeType()
                        && 'image/jpeg' !== $file->getClientMimeType()) {
                        throw ValidationException::withMessages(['Не коректний тип файлу']);
                    }
                    $incident->addMedia($file)->toMediaCollection('incident');
                }
            }
        });

        return response()->json([
            'message' => 'Подія успішно додана',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
    }
}
