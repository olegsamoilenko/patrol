<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\IncidentRequest;
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
        $this->middleware('can:Подія список', ['only' => ['getIncidentsPagination']]);
        $this->middleware('can:Подія створити', ['only' => ['storeIncident']]);
        $this->middleware('can:Подія редагувати', ['only' => ['editIncident']]);
        $this->middleware('can:Подія видалити тимчасово', ['only' => ['softDeleteIncident']]);
        $this->middleware('can:Подія видалити', ['only' => ['deleteIncident']]);
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
        $incidents = Incident::when($request->onlyTrashed === 'true', static function ($query) {
            return $query->onlyTrashed();
        })->when($request->onlyTheirOwn === 'true', static function ($query) {
            return $query->where('user_id', auth()->user()->id);
        })->when($request->district, static function ($query, $district) {
            $query->district($district);
        })
            ->when($request->search, static function ($query, $search) {
                return $query->search($search);
            })
            ->with('media')->with('user')->orderBy($request->sortBy, $request->sortDirection)->paginate(10);

        return response()->json([
            'incidents' => $incidents,
        ], 201);
    }

    /**
     * Store incident.
     */
    public function storeIncident(IncidentRequest $request): JsonResponse
    {
        $data = $request->validated();

//        TODO Сжать изображения перед сохранением на диск

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
     * Update incident.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function editIncident(int $id, IncidentRequest $request)
    {
        $data = $request->validated();

//        TODO Сжать изображения перед сохранением на диск
        DB::transaction(static function () use ($data, $id, $request) {
            $incident = Incident::find($id);
            $incident->update($data);
            if ($request->deleteImagesIncident) {
                $mediaItems = $incident->getMedia('incident');
                $delImg = json_decode($request->deleteImagesIncident);
                for ($i = 0; $i < count($mediaItems); $i++) {
                    for ($j = 0; $j < count($delImg); $j++) {
                        if ($mediaItems[$i]['id'] === $delImg[$j]) {
                            $mediaItems[$i]->delete();
                        }
                    }
                }
            }
            if ($request->files) {
                foreach ($request->files as $img) {
                    if ('image/png' !== $img->getClientMimeType()
                        && 'image/jpg' !== $img->getClientMimeType()
                        && 'image/jpeg' !== $img->getClientMimeType()) {
                        throw ValidationException::withMessages(['Не коректний тип файлу']);
                    }
                    $incident->addMedia($img)->toMediaCollection('incident');
                }
            }
        });

        return response()->json([
            'message' => 'Подія успішно додана',
        ], 201);
    }

    /**
     * Soft Delete Incident.
     */
    public function softDeleteIncident(int $id): JsonResponse
    {
        $incident = Incident::where('id', $id)->first();
        $incident->deleted_by = [
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'user_surname' => auth()->user()->surname,
            'user_phone' => auth()->user()->phone,
        ];
        $incident->save();
        $incident->delete();

        return response()->json([
            'message' => 'Користувача успішно видалено',
        ], 201);
    }

    /**
     * Delete Incident.
     */
    public function deleteIncident(int $id): JsonResponse
    {
        $incident = Incident::withTrashed()->where('id', $id)->first();
        $incident->clearMediaCollection('incident');
        $incident->forceDelete();

        return response()->json([
            'message' => 'Користувача успішно видалено',
        ], 201);
    }
}
