<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChoiceRequest;
use App\Http\Resources\ChoiceResource;
use App\Models\Choice;

class ChoiceController extends Controller
{
    public function update(ChoiceRequest $request, $choiceId)
    {
        $choice = Choice::find($choiceId);
        $choice->update($request->only('description', 'correct', 'icon_url'));
        return response()->json([
            'success' => true,
            'data' => new ChoiceResource($choice)
        ]);
    }

    public function drop($choiceId)
    {
        // delete any choice with found id
        Choice::where('id', $choiceId)->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Choice Deleted Successfully'
            ]
        ]);
    }
}
