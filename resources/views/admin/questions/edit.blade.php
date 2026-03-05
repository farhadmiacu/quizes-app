@extends('admin.layout')

@section('page-title', 'Edit Question')

@section('content')
<div style="max-width:760px">
    <form method="POST" action="{{ route('admin.questions.update', $question) }}" id="question-form">
        @csrf
        @method('PUT')

        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3>Question Details</h3></div>
            <div class="card-body">

                <div class="form-group">
                    <label for="question_text">Question Text</label>
                    <textarea name="question_text" id="question_text" rows="3" placeholder="Enter your question…">{{ old('question_text', $question->question_text) }}</textarea>
                    @error('question_text')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="draft"     {{ old('status', $question->status) === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $question->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <div class="card" style="margin-bottom:20px">
            <div class="card-header">
                <h3>Answer Options</h3>
                <span style="font-size:0.75rem;color:var(--muted)">Select the correct answer with the radio button</span>
            </div>
            <div class="card-body">

                @error('options')<div class="error-msg" style="margin-bottom:12px">{{ $message }}</div>@enderror
                @error('correct_option')<div class="error-msg" style="margin-bottom:12px">{{ $message }}</div>@enderror

                <div id="options-list"></div>

                <button type="button" id="add-option-btn" class="btn btn-ghost" style="margin-top:10px">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add another option
                </button>

            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary">Update Question</button>
            <a href="{{ route('admin.questions.index') }}" class="btn btn-ghost">Cancel</a>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<style>
    .option-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 8px;
        background: var(--surface2);
        transition: border-color 0.15s;
    }
    .option-row.correct { border-color: var(--green); background: rgba(0,229,160,0.05); }
    .option-row input[type="radio"] { accent-color: var(--green); width:16px; height:16px; flex-shrink:0; cursor:pointer; }
    .option-row input[type="text"] { background: transparent; border-color: transparent; padding: 6px 10px; }
    .option-row input[type="text"]:focus { border-color: var(--accent); background: var(--surface); }
    .remove-btn {
        background: none; border: none; cursor: pointer;
        color: var(--muted); padding: 4px; border-radius:4px; flex-shrink:0;
        transition: color 0.15s;
    }
    .remove-btn:hover { color: var(--red); }
</style>
<script>
(function(){
    // DB options passed as JSON
    const dbOptions  = @json($question->options->map(fn($o) => ['text' => $o->option_text, 'is_correct' => $o->is_correct]));
    // old() override on validation failure
    const oldOptions = @json(old('options', []));
    const oldCorrect = {{ old('correct_option', 'null') }};

    const list   = document.getElementById('options-list');
    const addBtn = document.getElementById('add-option-btn');
    const MAX    = 4;
    const MIN    = 2;

    function buildRow(index, text, isCorrect) {
        const row = document.createElement('div');
        row.className = 'option-row' + (isCorrect ? ' correct' : '');
        row.innerHTML = `
            <input type="radio" name="correct_option" value="${index}" ${isCorrect ? 'checked' : ''}>
            <input type="text" name="options[${index}][text]" placeholder="Option ${index + 1}" value="${escHtml(text)}" required>
            <button type="button" class="remove-btn" title="Remove">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>`;

        row.querySelector('input[type="radio"]').addEventListener('change', function() {
            document.querySelectorAll('.option-row').forEach(r => r.classList.remove('correct'));
            row.classList.add('correct');
        });

        row.querySelector('.remove-btn').addEventListener('click', function() {
            if (list.children.length <= MIN) return;
            row.remove();
            rebuildIndexes();
            updateUI();
        });

        return row;
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function rebuildIndexes() {
        Array.from(list.children).forEach((row, i) => {
            row.querySelector('input[type="radio"]').value = i;
            const txt = row.querySelector('input[type="text"]');
            txt.name = `options[${i}][text]`;
            txt.placeholder = `Option ${i + 1}`;
        });
    }

    function updateUI() {
        const count = list.children.length;
        addBtn.style.display = count >= MAX ? 'none' : '';
        Array.from(list.querySelectorAll('.remove-btn')).forEach(btn => {
            btn.style.visibility = count <= MIN ? 'hidden' : 'visible';
        });
    }

    function addOption(text, isCorrect) {
        if (list.children.length >= MAX) return;
        const idx = list.children.length;
        list.appendChild(buildRow(idx, text || '', isCorrect));
        updateUI();
    }

    // Choose source: old() wins on validation failure, else DB values
    if (oldOptions && oldOptions.length >= 2) {
        oldOptions.forEach((opt, i) => addOption(opt.text, i === parseInt(oldCorrect)));
    } else {
        dbOptions.forEach((opt, i) => addOption(opt.text, opt.is_correct));
        if (list.children.length < MIN) {
            for (let i = list.children.length; i < MIN; i++) addOption('', false);
        }
    }

    addBtn.addEventListener('click', () => addOption('', false));

    // Validate correct_option on submit
    document.getElementById('question-form').addEventListener('submit', function(e) {
        const checked = document.querySelector('input[name="correct_option"]:checked');
        if (!checked) {
            e.preventDefault();
            alert('Please select the correct answer.');
        }
    });

    updateUI();
})();
</script>
@endpush
