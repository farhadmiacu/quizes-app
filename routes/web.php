<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\ProfileController;
use App\Models\Question;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.questions.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('questions', QuestionController::class);
    Route::post('questions/{question}/toggle-status', [QuestionController::class, 'toggleStatus'])
         ->name('questions.toggle-status');
});

Route::get('/quiz', fn() => view('quiz.index', [
    'questions' => Question::where('status', 'published')
                    ->with('options')->orderBy('sort_order')->get()
]))->name('quiz.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
