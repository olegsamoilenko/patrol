<?php

namespace App\Http\Controllers;

use App\Http\Requests\MainPageIncidentRequest;
use App\Models\Incident;
use App\Models\MainPageIncident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MainPageIncidentController extends Controller
{
    public MainPageIncident $incident;

    public function __construct()
    {
        $this->incident = new MainPageIncident();
        $this->middleware('can:Подія на головній список', ['only' => ['getIncidentsPagination']]);
        $this->middleware('can:Подія на головній створити', ['only' => ['storeIncident']]);
        $this->middleware('can:Подія на головній редагувати', ['only' => ['editIncident']]);
        $this->middleware('can:Подія на головній видалити', ['only' => ['deleteIncident']]);
    }

    /**
     * Get Incident
     */
    public function getIncidents(): JsonResponse
    {
        $incidents = MainPageIncident::orderBy('id', 'DESC')->with('media')->get();

        return response()->json([
            'incidents' => $incidents,
        ], 201);
    }

    /**
     * Store Incident.
     */
    public function storeIncident(MainPageIncidentRequest $request): JsonResponse
    {
        $data = $request->validated();

//        TODO Сжать изображения перед сохранением на диск

        DB::transaction(static function () use ($data, $request) {
            $mainPageIncident = MainPageIncident::create($data);
            if ($request->images) {
                foreach (json_decode($request->images) as $img) {
                    Media::find($img)->copy($mainPageIncident, 'mainPageIncident');
                }
            }
            if ($request->files) {
                foreach ($request->files as $img) {
                    if ('image/png' !== $img->getClientMimeType()
                        && 'image/jpg' !== $img->getClientMimeType()
                        && 'image/jpeg' !== $img->getClientMimeType()) {
                        throw ValidationException::withMessages(['Не коректний тип файлу']);
                    }
                    $mainPageIncident->addMedia($img)->toMediaCollection('mainPageIncident');
                }
            }
        });

        return response()->json([
            'message' => 'Подія успішно додана',
        ], 201);
    }

    /**
     * Update Incident.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function editIncident(int $id, MainPageIncidentRequest $request)
    {
        $data = $request->validated();

//        TODO Сжать изображения перед сохранением на диск
        DB::transaction(static function () use ($data, $request) {
            $mainPageIncident = MainPageIncident::find($request->id);
            $mainPageIncident->update($data);
            if ($request->deleteImagesIncident) {
                $mediaItems = $mainPageIncident->getMedia('mainPageIncident');
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
                    $mainPageIncident->addMedia($img)->toMediaCollection('mainPageIncident');
                }
            }
        });

        return response()->json([
            'message' => 'Подія успішно додана',
        ], 201);
    }

    /**
     * Delete Incident.
     */
    public function deleteIncident(int $id): JsonResponse
    {
        $incident = MainPageIncident::where('id', $id)->first();

        $incident->clearMediaCollection('mainPageIncident');
        $incident->delete();

        return response()->json([
            'message' => 'Користувача успішно видалено',
        ], 201);
    }
}
