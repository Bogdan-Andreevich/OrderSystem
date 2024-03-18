<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function create()
    {
        try {
            $questions = Question::whereNull('parent_id')->get();
            if ($questions->count() === 0) {
                return response()->json(['message' => 'Масив питань порожній']);
            }

            foreach ($questions as &$question) {
                $question->answers = json_decode($question->answers, true); // Convert to array
                $question->subQuestions = json_decode($question->subQuestions, true); // Convert to array
            }


            return response()->json($questions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Питаннь не знайдено'], 404);
        }
    }

    public function getQuestionById($id)
    {
        try {
            $question = Question::findOrFail($id);

            $question->answers = json_decode($question->answers, true); // Convert to array
            $question->subQuestions = json_decode($question->subQuestions, true); // Convert to array


            return response()->json($question);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Питання не знайдено'], 404);
        }
    }

    public function getQuestionByTypeDoc(string $id)
    {
        try {
            $questions = Question::where('typeDocId', '=', $id)->get();

            foreach ($questions as &$question) {
                $question->is_add_description = boolval($question->is_add_description); // Convert to bool
                $question->answers = json_decode($question->answers, true); // Convert to array
                $question->subQuestions = json_decode($question->subQuestions, true); // Convert to array
            }


            return response()->json($questions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Питання не знайдено'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'question_description' => 'nullable|string',
            'is_add_description' => 'nullable|boolean',
            'comment' => 'nullable|string',
            'typeDocId' => 'nullable|string',
            'subQuestions' => 'nullable|json',
            'answers' => 'nullable|json',
            'index' => 'nullable|integer',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $validatedData = $validator->validated();
        $validatedData['parent_id'] = null;
        try {
            $question = Question::create($validatedData);
            return response()->json($question);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Помилка створення питання: " . $e->getMessage()
            ], 500);
        }
    }

    public function answers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'question_description' => 'nullable|string',
            'is_add_description' => 'nullable|boolean',
            'comment' => 'nullable|string',
            'price' => 'nullable|integer',
            'parent_id' => 'required|integer|exists:questions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $question = Question::findOrFail($request->parent_id);

            $reply = Question::create([
                'question' => $request->question,
                'question_description' => $request->question_description,
                'is_add_description' => $request->is_add_description,
                'comment' => $request->comment,
                'price' => $request->price,
                'parent_id' => $question->id
            ]);

            return response()->json($reply);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Головне питання не знайдено'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'question_description' => 'nullable|string',
            'is_add_description' => 'nullable|boolean',
            'comment' => 'nullable|string',
            'typeDocId' => 'nullable|string',
            'subQuestions' => 'nullable|json',
            'answers' => 'nullable|json',
            'index' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $question = Question::findOrFail($id);
            $question->update($validator->validated());
            return response()->json($question);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Помилка оновлення питання: " . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->delete();
            return response()->json(['message' => 'Питання видалено']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Питання не знайдено: " . $e->getMessage()
            ], 500);
        }
    }
}
