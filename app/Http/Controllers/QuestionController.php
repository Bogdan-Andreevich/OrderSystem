<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function create($parentId = null)
    {
        $questions = Question::whereNull('parent_id')->with('answers')->get();

        return response()->json($questions);
    }

    public function getQuestionById($id)
    {
        $questions = Question::findOrFail($id)->load('answers');
        return response()->json($questions);

    }

    public function store(Request $request)
    {

//        $validator = Validator::make($request->all(),[
//            'question' => 'required|string',
//            'question_description' => 'nullable|string',
//            'is_add_description' => 'nullable|boolean',
//            'comment' => 'nullable|string',
//            'price' => 'nullable|integer',
//            'parent_id' => 'nullable|integer|exists:questions,id'
//        ]);
//
//        if($validator->fails()) {
//            return redirect()->back()->withInput()->withErrors(['text' => 'Вы ввели неправильные данные']);
//        }


        $validated = $request->validate([
            'question' => 'required|string',
            'question_description' => 'nullable|string',
            'is_add_description' => 'nullable|boolean',
            'comment' => 'nullable|string',
            'price' => 'nullable|integer',
            'parent_id' => 'nullable|integer|exists:questions,id'
        ]);

        $question = Question::create($validated);

        return response()->json($question);
    }

    public function answers(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'question_description' => 'nullable|string',
            'is_add_description' => 'nullable|boolean',
            'comment' => 'nullable|string',
            'price' => 'nullable|integer',
            'parent_id' => 'nullable|integer|exists:questions,id'
        ]);

        $question = Question::findOrFail($validated['parent_id']);

        $reply = new Question($validated);
        $reply->parent_id = $question->id;
        $reply->save();

        return response()->json($reply);
    }

//    public function show(Question $question)
//    {
//        return response()->json($question->load('answers'));
//    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'question_description' => 'nullable|string',
            'is_add_description' => 'nullable|boolean',
            'comment' => 'nullable|string',
            'price' => 'nullable|integer',
            'parent_id' => 'nullable|integer|exists:questions,id'
        ]);

        $question = Question::findOrFail($id);
        $question->update($validated);
        return response()->json($question);
    }

// destroy
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return response()->json($question);
    }
}
