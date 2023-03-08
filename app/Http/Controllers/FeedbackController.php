<?php

namespace App\Http\Controllers;

use App\Mail\AddedFeedback;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public Feedback $feedback;

    public function __construct()
    {
        $this->feedback = new Feedback();
        $this->middleware('can:Зворотній зв’язок список', ['only' => ['getFeedbacks']]);
        $this->middleware('can:Зворотній зв’язок створити', ['only' => ['storeFeedback']]);
        $this->middleware('can:Зворотній зв’язок редагувати', ['only' => ['editFeedback']]);
    }

    public function getFeedbacksPagination(Request $request): JsonResponse
    {
        $feedbacks = Feedback::when($request->status, static function ($query, $status) {
            return $query->status($status);
        })->with('user')->orderBy($request->sortBy, $request->sortDirection)->paginate(10);

        return response()->json([
            'feedbacks' => $feedbacks,
        ], 201);
    }

    /**
     * Get Feedbacks.
     */

    public function getFeedbacks(): JsonResponse
    {
        $feedbacks = Feedback::all();

        return response()->json([
            'feedbacks' => $feedbacks,
        ], 201);
    }


    /**
     * Store Feedback.
     */

    public function storeFeedback(Request $request): JsonResponse
    {

//        TODO: Add validation
        $feedback = $this->feedback->create($request->all());
        Mail::to('airincars@gmail.com')->send(new AddedFeedback($feedback));

        return response()->json([
            'feedback' => $feedback,
        ], 201);
    }

    /**
     * Edit Feedback.
     */

    public function editFeedback($id, Request $request): JsonResponse
    {
        $feedback = Feedback::find($id);
        $feedback->update($request->all());

        return response()->json([
            'feedback' => $feedback,
        ], 201);
    }

    /**
     * Delete Feedback.
     */

    public function deleteFeedback($id): JsonResponse
    {
        $feedback = Feedback::find($id);
        $feedback->delete();

        return response()->json([
            'feedback' => $feedback,
        ], 201);
    }
}
