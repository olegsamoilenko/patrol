<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncidentRequest;
use App\Models\Incident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IncidentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:incident list', ['only' => ['index', 'show']]);
        $this->middleware('can:incident create', ['only' => ['storeIncident']]);
//        $this->middleware('can:incident edit', ['only' => ['edit', 'update']]);
//        $this->middleware('can:incident delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $incidents = (new Incident())->newQuery();
        $incidents->latest();
        $incidents->with('media');
//        $carbondate = Carbon::parse($incidents->created_at);
        $incidents = $incidents->paginate(10)->onEachSide(2)->appends(request()->query());

        return response()->json([
            'incidents' => $incidents,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storeIncident(StoreIncidentRequest $request)
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


//        $getIncident = Incident::find($incident->id);
//        dd($incident->getMedia());
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
