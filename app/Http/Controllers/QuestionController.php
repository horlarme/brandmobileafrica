<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChoiceRequest;
use App\Http\Requests\QuestionUpdate;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    public function upload(UploadRequest $request)
    {
        $file = $request->file('file');
        $fileHandler = fopen($file->getRealPath(), 'r');
        // determines if we have read the header row or not
        $readHeader = false;
        $total = 0;
        while ($row = fgetcsv($fileHandler, filesize($file) + 1)) {
            //skipping header.
            if (!$readHeader) {
                $readHeader = true;
                continue;
            }
            //create question
            $question = Question::create([
                'question' => trim($row[0]),
                'is_general' => (boolean)$row[1],
                'category' => trim($row[2]),
                'points' => (int)$row[3] ?: 0,
                'icon_url' => $row[4],
                'duration' => (int)$row[5] ?: 0
            ]);
            //create choices (4 but only if it's upto that)
            $choices = [[$row[6], $row[7], $row[8]], [$row[9], $row[10], $row[11]],
                [$row[12], $row[13], $row[14]], [$row[15], $row[16], $row[17]]];

            foreach ($choices as $choice) {
                //only run if the value for it is not null
                if ($choice[0]) $question->choices()->create([
                    'description' => $choice[0],
                    'correct' => strtolower($choice[1]) === 'true',
                    'icon_url' => $choice[2]
                ]);
            }
            $total = $total + 1;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'totalQuestions' => $total
            ]
        ]);
    }

    public function get(Request $request)
    {
        $questions = Question::when($request->get('category'), fn(Builder $query, $value) => $query->where('category', $value));

        return response()->json([
            'success' => true,
            'data' => new PaginatedResource($questions->paginate(10), QuestionResource::class)
        ]);
    }

    public function single(Request $request)
    {
        $question = Question::where('id', $request->route('question'))->with('choices')->first();

        if (!$question) return response()->json([
            'success' => false,
            'message' => 'Not Found: Question Not Found'
        ], Response::HTTP_NOT_FOUND);

        return response()->json([
            'success' => true,
            'data' => new QuestionResource($question, true)
        ]);
    }

    public function drop($question)
    {
        $question = Question::find($question);

        if (!$question) return response()->json([
            'success' => false,
            'message' => 'Not Found: Question Not Found'
        ], Response::HTTP_NOT_FOUND);

        $question->delete();
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Question Deleted Successfully'
            ]
        ]);
    }

    public function update(QuestionUpdate $request)
    {
        $question = Question::find($request->route('question'));

        if (!$question) return response()->json([
            'success' => false,
            'message' => 'Not Found: Question Not Found'
        ], Response::HTTP_NOT_FOUND);
        $question->update($request->only('question', 'is_general', 'category', 'points', 'icon_url', 'duration'));
        return response()->json([
            'success' => true,
            'data' => new QuestionResource($question, true)
        ]);
    }

    public function addChoice(ChoiceRequest $request)
    {
        $question = Question::find($request->route('question'));

        if (!$question) return response()->json([
            'success' => false,
            'message' => 'Not Found: Question Not Found'
        ], Response::HTTP_NOT_FOUND);
        $question->choices()->create($request->only('description', 'correct', 'icon_url'));

        return response()->json([
            'success' => true,
            'data' => new QuestionResource($question->refresh(), true)
        ]);
    }
}
