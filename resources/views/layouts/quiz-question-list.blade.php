@php
    $myAttempts = $quizCompletions->where('quiz_id', $quizId)->sortByDesc('created_at');
    $latestAttempt = $myAttempts->first();
    $hasPassed = $myAttempts->where('status', \App\Models\QuizCompletion::STATUS_PASSED)->isNotEmpty();
    $questionCount = min($quizItems->count(), (int) $quiz['show_question']);
@endphp
<div class="table-responsive" id="result-quiz">
    @if($latestAttempt)
        <div class="alert alert-{{ $hasPassed ? 'success' : 'warning' }} mb-3">
            <strong>
                {{ $hasPassed ? 'Anda telah lulus quiz ini.' : 'Anda belum lulus quiz ini.' }}
            </strong>
            Skor terakhir: {{ $latestAttempt['score'] }} ({{ ucfirst($latestAttempt['status']) }}).
            Anda tetap dapat mengulang quiz untuk meninjau materi.
        </div>
    @endif
    <h4>Rules:</h4>
    <p>This quiz aims to test your knowledge of {{ $course['title'] }} material.</p>
    <p>There are {{ $questionCount }} questions to answer in this quiz. Minimum score to pass this quiz
        is {{ $quiz['min_score'] }}%.</p>
    <p>If you do not meet the graduation requirements, then you repeat the quiz again. </p>
    <p>Take advantage of that waiting time to review previous material, OK?</p>
    <br>
    <p>Have a great time doing it!</p>
    <div class="text-end">
        <button class="btn btn-success"
                onclick="document.getElementById('result-quiz').style.display = 'none';document.getElementById('do-quiz').style.display = '';">
            {{ $latestAttempt ? 'Ulangi Quiz' : 'Mulai Quiz' }}
        </button>
    </div>
    <hr>
    <h4>History</h4>
    <table class="table">
        <thead>
        <tr>
            <th>Exam time</th>
            <th>Score</th>
            <th>Status</th>
        </tr>
        @foreach($quizCompletions->where('quiz_id', $quizId) as $r)
            <tr>
                <td>{{ $r['created_at'] }}</td>
                <td>{{ $r['score'] }}</td>
                <td>{!! $r['status_badge'] !!}</td>
            </tr>
        @endforeach
        </thead>
    </table>
</div>

<div id="do-quiz" class="table-responsive" style="display: none; max-height: 600px">
    <form action="/trainings/{{ $courseEnrollmentId }}" method="POST">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quizId }}">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quizItems->shuffle()->take($questionCount) as $i => $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item['question'] }}</td>
                </tr>
                @foreach(collect(json_decode($item['answer'], true))->shuffle() as $answer)
                    <tr>
                        <td></td>
                        <td>
                            <input type="radio" name="answer[{{ $item['id'] }}]" required
                                   class="form-check-input" value="{{ $answer['answer'] }}"> {{ $answer['answer'] }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
        <hr>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Submit Answer</button>
        </div>
    </form>
</div>
