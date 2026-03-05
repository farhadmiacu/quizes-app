<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::withCount('options')
            ->with('options')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string|max:1000',
            'status'         => 'required|in:published,draft',
            'options'        => 'required|array|min:2|max:4',
            'options.*.text' => 'required|string|max:500',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question = Question::create([
            'question_text' => $validated['question_text'],
            'status'        => $validated['status'],
            'sort_order'    => Question::max('sort_order') + 1,
        ]);

        foreach ($validated['options'] as $index => $optionData) {
            $question->options()->create([
                'option_text' => $optionData['text'],
                'is_correct'  => ($index === (int) $validated['correct_option']),
                'sort_order'  => $index,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_text'  => 'required|string|max:1000',
            'status'         => 'required|in:published,draft',
            'options'        => 'required|array|min:2|max:4',
            'options.*.text' => 'required|string|max:500',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'status'        => $validated['status'],
        ]);

        $question->options()->delete();

        foreach ($validated['options'] as $index => $optionData) {
            $question->options()->create([
                'option_text' => $optionData['text'],
                'is_correct'  => ($index === (int) $validated['correct_option']),
                'sort_order'  => $index,
            ]);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully.');
    }

    public function toggleStatus(Question $question)
    {
        $question->update([
            'status' => $question->isPublished() ? 'draft' : 'published',
        ]);

        return back()->with('success', 'Status updated.');
    }
}
