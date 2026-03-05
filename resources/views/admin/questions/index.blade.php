@extends('admin.layout')

@section('page-title', 'All Questions')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Questions</div>
        <div class="stat-value accent">{{ $questions->total() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Published</div>
        <div class="stat-value green">{{ \App\Models\Question::where('status','published')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Draft</div>
        <div class="stat-value muted">{{ \App\Models\Question::where('status','draft')->count() }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Questions</h3>
        <span style="font-size:0.78rem;color:var(--muted)">{{ $questions->total() }} total</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Question &amp; Options</th>
                    <th style="width:80px;text-align:center">Options</th>
                    <th style="width:100px">Status</th>
                    <th style="width:180px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $question)
                <tr>
                    <td style="color:var(--muted);font-size:0.8rem">{{ $question->sort_order }}</td>
                    <td>
                        <div style="font-size:0.875rem;font-weight:500;margin-bottom:6px;line-height:1.4">
                            {{ Str::limit($question->question_text, 80) }}
                        </div>
                        <div>
                            @foreach($question->options as $opt)
                                <span class="pill {{ $opt->is_correct ? 'pill-correct' : 'pill-wrong' }}" title="{{ $opt->option_text }}">
                                    {{ Str::limit($opt->option_text, 18) }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td style="text-align:center;color:var(--muted)">{{ $question->options_count }}</td>
                    <td>
                        <span class="badge badge-{{ $question->status }}">
                            {{ ucfirst($question->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            {{-- Toggle status --}}
                            <form method="POST" action="{{ route('admin.questions.toggle-status', $question) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $question->isPublished() ? 'btn-warning' : 'btn-success' }}" title="{{ $question->isPublished() ? 'Set to Draft' : 'Publish' }}">
                                    {{ $question->isPublished() ? '⏸' : '▶' }}
                                </button>
                            </form>

                            {{-- Edit --}}
                            <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-ghost">Edit</a>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.questions.destroy', $question) }}"
                                  onsubmit="return confirm('Delete this question and all its options?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--muted);padding:40px">
                        No questions yet. <a href="{{ route('admin.questions.create') }}" style="color:var(--accent)">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:20px">
    {{ $questions->links() }}
</div>
@endsection
