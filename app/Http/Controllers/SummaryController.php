<?php

namespace App\Http\Controllers;

use App\Models\Summary;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Оперативна обстановка редагувати', ['only' => ['editSummary']]);
    }

    public function getSummary()
    {
        $summary = Summary::first();

        return response()->json([
            'summary' => $summary,
        ], 201);
    }

    public function editSummary(Request $request)
    {
        $summary = Summary::where('id', $request->id)->first();
        $summary->update(
            [
                'text' => $request->text,
            ]
        );

        return response()->json([
            'summary' => $summary,
        ], 201);
    }
}
