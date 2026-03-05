<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0c12;
            --surface: #12151f;
            --surface2: #1a1e2e;
            --border: #252a3d;
            --accent: #00d4ff;
            --green: #00e5a0;
            --red: #ff4d6d;
            --text: #dde4f0;
            --muted: #5a6380;
        }

        body {
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* ── Progress bar ── */
        .progress-wrap {
            width: 100%;
            max-width: 680px;
            margin-bottom: 24px;
        }

        .progress-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .progress-track {
            height: 4px;
            background: var(--surface2);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--accent);
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        /* ── Quiz card ── */
        .quiz-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 100%;
            max-width: 680px;
            padding: 32px;
            margin-bottom: 20px;
        }

        .question-num {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--accent);
            margin-bottom: 14px;
        }

        .question-text {
            font-size: 1.15rem;
            font-weight: 600;
            line-height: 1.55;
            margin-bottom: 28px;
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
        }

        /* ── Options ── */
        .options-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .option-card {
            padding: 14px 18px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--surface2);
            cursor: pointer;
            font-size: 0.925rem;
            font-family: 'Hind Siliguri', 'Inter', sans-serif;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            user-select: none;
        }

        .option-card:hover:not(.disabled) {
            border-color: var(--accent);
            color: var(--text);
        }

        .option-card.disabled { cursor: default; }

        .option-card.correct {
            border-color: var(--green);
            background: rgba(0,229,160,0.08);
            color: var(--green);
        }

        .option-card.wrong {
            border-color: var(--red);
            background: rgba(255,77,109,0.08);
            color: var(--red);
        }

        .option-letter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--surface);
            border: 1px solid var(--border);
            font-size: 0.78rem;
            font-weight: 700;
            flex-shrink: 0;
            font-family: 'Inter', sans-serif;
            transition: all 0.15s;
        }

        .option-card.correct .option-letter { background: var(--green); color: #000; border-color: var(--green); }
        .option-card.wrong   .option-letter { background: var(--red);   color: #fff; border-color: var(--red); }

        /* ── Feedback ── */
        .feedback {
            margin-top: 16px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            display: none;
        }

        .feedback.show { display: block; }
        .feedback.correct { background: rgba(0,229,160,0.1); color: var(--green); border: 1px solid rgba(0,229,160,0.2); }
        .feedback.wrong   { background: rgba(255,77,109,0.1); color: var(--red);   border: 1px solid rgba(255,77,109,0.2); }

        /* ── Navigation ── */
        .nav-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 680px;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .btn-primary { background: var(--accent); color: #000; }
        .btn-primary:hover:not(:disabled) { background: #00b8e0; }
        .btn-primary:disabled { opacity: 0.35; cursor: not-allowed; }

        .btn-ghost {
            background: var(--surface);
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover:not(:disabled) { color: var(--text); border-color: var(--muted); }
        .btn-ghost:disabled { opacity: 0.35; cursor: not-allowed; }

        /* ── Result screen ── */
        .result-screen {
            display: none;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            padding: 48px 32px;
        }

        .result-screen.show { display: flex; }

        .score-ring {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            position: relative;
        }

        .score-ring-inner {
            position: absolute;
            inset: 10px;
            border-radius: 50%;
            background: var(--surface);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .score-fraction {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        .score-pct {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 4px;
        }

        .result-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .result-message {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 28px;
            line-height: 1.5;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        .empty-state h2 { font-size: 1.2rem; margin-bottom: 8px; color: var(--text); }

        /* ── Header ── */
        .quiz-header {
            width: 100%;
            max-width: 680px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            padding-top: 8px;
        }

        .quiz-header .logo { font-size: 1rem; font-weight: 700; color: var(--accent); }
        .quiz-header a { color: var(--muted); font-size: 0.8rem; text-decoration: none; }
        .quiz-header a:hover { color: var(--text); }
    </style>
</head>
<body>

<div class="quiz-header">
    <span class="logo">⚡ Quiz</span>
    <a href="{{ route('admin.questions.index') }}">← Admin Panel</a>
</div>

@if($questions->isEmpty())
    <div class="empty-state">
        <h2>No questions available</h2>
        <p>Check back later or <a href="{{ route('admin.questions.index') }}" style="color:var(--accent)">add some questions</a>.</p>
    </div>
@else

{{-- Progress --}}
<div class="progress-wrap" id="progress-wrap">
    <div class="progress-meta">
        <span id="q-counter">Question 1 of {{ $questions->count() }}</span>
        <span id="score-live">Score: 0</span>
    </div>
    <div class="progress-track">
        <div class="progress-fill" id="progress-fill" style="width:0%"></div>
    </div>
</div>

{{-- Question card --}}
<div class="quiz-card" id="quiz-card">
    <div class="question-num" id="q-num">Question 1</div>
    <div class="question-text" id="q-text"></div>
    <div class="options-grid" id="options-grid"></div>
    <div class="feedback" id="feedback"></div>
</div>

{{-- Navigation --}}
<div class="nav-row" id="nav-row">
    <button class="btn btn-ghost" id="prev-btn" disabled>
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Previous
    </button>
    <button class="btn btn-primary" id="next-btn" disabled>
        Next
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
</div>

{{-- Result screen --}}
<div class="result-screen" id="result-screen">
    <div class="score-ring" id="score-ring">
        <div class="score-ring-inner">
            <div class="score-fraction" id="res-fraction">0/0</div>
            <div class="score-pct" id="res-pct">0%</div>
        </div>
    </div>
    <div class="result-title" id="res-title">Quiz Complete!</div>
    <div class="result-message" id="res-message"></div>
    <button class="btn btn-primary" onclick="location.reload()">Try Again</button>
</div>

@endif

@php
    $quizData = $questions->values()->map(function($q) {
        return [
            'text'    => $q->question_text,
            'options' => $q->options->map(function($o) {
                return [
                    'text'       => $o->option_text,
                    'is_correct' => $o->is_correct,
                ];
            })->values(),
        ];
    });
@endphp

<script>
(function(){
    const questions = @json($quizData);

    if (!questions.length) return;

    const letters   = ['A','B','C','D'];
    let current     = 0;
    let score       = 0;
    const answered  = new Array(questions.length).fill(false);
    const chosen    = new Array(questions.length).fill(null);

    const qNum      = document.getElementById('q-num');
    const qText     = document.getElementById('q-text');
    const grid      = document.getElementById('options-grid');
    const feedback  = document.getElementById('feedback');
    const prevBtn   = document.getElementById('prev-btn');
    const nextBtn   = document.getElementById('next-btn');
    const counter   = document.getElementById('q-counter');
    const scoreLive = document.getElementById('score-live');
    const progFill  = document.getElementById('progress-fill');
    const quizCard  = document.getElementById('quiz-card');
    const navRow    = document.getElementById('nav-row');
    const progWrap  = document.getElementById('progress-wrap');
    const result    = document.getElementById('result-screen');

    function render(idx) {
        const q   = questions[idx];
        const ans = answered[idx];

        qNum.textContent  = `Question ${idx + 1}`;
        qText.textContent = q.text;

        counter.textContent  = `Question ${idx + 1} of ${questions.length}`;
        scoreLive.textContent = `Score: ${score}`;
        progFill.style.width = `${(idx / questions.length) * 100}%`;

        grid.innerHTML = '';
        feedback.className = 'feedback';
        feedback.textContent = '';

        q.options.forEach((opt, i) => {
            const card = document.createElement('div');
            card.className = 'option-card';
            card.innerHTML = `<span class="option-letter">${letters[i] || i+1}</span><span>${escHtml(opt.text)}</span>`;

            if (ans) {
                card.classList.add('disabled');
                if (opt.is_correct) {
                    card.classList.add('correct');
                } else if (chosen[idx] === i && !opt.is_correct) {
                    card.classList.add('wrong');
                }
            } else {
                card.addEventListener('click', () => handleAnswer(idx, i, opt.is_correct));
            }

            grid.appendChild(card);
        });

        if (ans) {
            const wasCorrect = questions[idx].options[chosen[idx]]?.is_correct;
            feedback.textContent  = wasCorrect ? '✓ Correct!' : `✗ Wrong. Correct answer: ${questions[idx].options.find(o => o.is_correct)?.text}`;
            feedback.className    = `feedback show ${wasCorrect ? 'correct' : 'wrong'}`;
        }

        prevBtn.disabled = idx === 0;
        nextBtn.disabled = !ans;

        const isLast = idx === questions.length - 1;
        nextBtn.innerHTML = isLast
            ? `Finish <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>`
            : `Next <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>`;
    }

    function handleAnswer(qIdx, optIdx, isCorrect) {
        if (answered[qIdx]) return;
        answered[qIdx] = true;
        chosen[qIdx]   = optIdx;
        if (isCorrect) score++;
        render(qIdx);
    }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function showResult() {
        quizCard.style.display  = 'none';
        navRow.style.display    = 'none';
        progWrap.style.display  = 'none';
        result.classList.add('show');

        const total = questions.length;
        const pct   = Math.round((score / total) * 100);

        document.getElementById('res-fraction').textContent = `${score}/${total}`;
        document.getElementById('res-pct').textContent      = `${pct}%`;

        const ring = document.getElementById('score-ring');
        const color = pct >= 70 ? 'var(--green)' : pct >= 40 ? 'var(--accent)' : 'var(--red)';
        ring.style.background = `conic-gradient(${color} ${pct * 3.6}deg, var(--surface2) 0deg)`;

        let title, msg;
        if (pct === 100) { title = '🏆 Perfect!';       msg = 'Outstanding! You got every question right.'; }
        else if (pct >= 80) { title = '🎉 Excellent!';   msg = `Great job! You scored ${pct}%. Almost perfect!`; }
        else if (pct >= 60) { title = '👍 Good job!';    msg = `Well done! You scored ${pct}%. Keep it up.`; }
        else if (pct >= 40) { title = '😐 Almost there'; msg = `You scored ${pct}%. A bit more practice will help.`; }
        else                 { title = '😬 Keep trying'; msg = `You scored ${pct}%. Don't give up — try again!`; }

        document.getElementById('res-title').textContent   = title;
        document.getElementById('res-message').textContent = msg;
    }

    nextBtn.addEventListener('click', () => {
        if (current < questions.length - 1) {
            current++;
            render(current);
        } else {
            showResult();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (current > 0) {
            current--;
            render(current);
        }
    });

    render(0);
})();
</script>
</body>
</html>
