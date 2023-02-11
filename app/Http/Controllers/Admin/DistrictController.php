<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:Район список', ['only' => ['getDistrictsPagination', 'getAllDistricts']]);
        $this->middleware('can:Район створити', ['only' => ['addDistrict']]);
        $this->middleware('can:Район редагувати', ['only' => ['editDistrict']]);
        $this->middleware('can:Район видалити', ['only' => ['deleteDistrict']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getDistrictsPagination(): JsonResponse
    {
        $districts = (new District())->newQuery();

        $districts = $districts->orderBy('order')->paginate(10)->onEachSide(2)->appends(request()->query());

        return response()->json([
            'districts' => $districts,
        ], 201);
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllDistricts()
    {
        $districts = (new District())->newQuery();
        $districts = $districts->orderBy('order')->get();

        return response()->json([
            'districts' => $districts,
        ], 201);
    }

    /**
     * Add district.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addDistrict(Request $request): JsonResponse
    {
        $request->validate(
            [
                'title' => ['required', 'string', 'max:255', 'unique:districts'],
                'streets' => ['nullable', 'string'],
                'order' => ['integer', 'required'],
            ],
            [
                'title.required' => 'Поле не може бути порожнім',
                'title.max:255' => 'Максимально допустима кількість символів 255',
                'title.unique' => 'Такий район вже існує',
                'order.required' => 'Виберіть розміщення району в списку'
            ]
        );

        $districts = District::where('order', '>=', $request->order)->get();
        foreach ($districts as $d) {
            $d->update([
                'order' => $d->order + 1,
            ]);
        }

        $district = District::create([
            'title' => $request->title,
            'streets' => $request->streets,
            'order' => $request->order,
        ]);



        return response()->json([
            'message' => 'Район успішно створениі',
            'district' => $district,
        ], 201);
    }

    /**
     * Edit district.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editDistrict(Request $request): JsonResponse
    {
        $district = District::where('id', $request->id)->first();

        $district->update([
            'title' => $request->title,
            'streets' => $request->streets,
            'order' => $request->order,
        ]);

        return response()->json([
            'message' => 'Район успішно змінено',
        ], 201);
    }

    /**
     * Delete district.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteDistrict(int $id): JsonResponse
    {
        $district = District::where('id', $id)->first();
        $district->delete();

        return response()->json([
            'message' => 'Район успішно видалено',
        ], 201);
    }
}
