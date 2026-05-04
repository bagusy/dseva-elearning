<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTrainingRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\CourseEnrollment;
use App\Models\CourseEnrollmentProgress;
use App\Models\Quiz;
use App\Models\QuizCompletion;
use App\Models\QuizCompletionAnswer;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentUser = $request->user()->load('company');
        $companyOwnerId = $currentUser['company']['user_id'] ?? null;
        $courses = Course::where(function ($q) use ($companyOwnerId) {
            $q->where('is_custom', 0);
            if ($companyOwnerId) {
                $q->orWhere('user_id', $companyOwnerId);
            }
        })->with(['user', 'subSections.video'])->get();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(StoreCourseRequest $request)
    {
        $course = new Course;
        $course['user_id'] = $request->user()['id'];
        $course['title'] = $request['title'];
        $course['description'] = $request['description'];
        $course['category'] = $request['category'];
        $course['level'] = $request['level'];
        $course['price_in_usd'] = $request['price_in_usd'];
        $course['status'] = $request['status'];
        $course['is_custom'] = $request['is_custom'];
        $course->save();

        return redirect('/courses/' . $course['id'])->withMessage('Course Created. Next Add Video and Quiz');
    }

    public function show(Course $course)
    {
        $this->authorize('manageContent', $course);
        $course->load('user', 'sections.subSections.video', 'sections.subSections.quiz');
        $topSection = $course->sections->sortBy('index')->first();
        $bottomSection = $course->sections->sortByDesc('index')->first();
        $hasSectionArrow = true;
        if ((is_null($topSection) || is_null($bottomSection)) || ($topSection['index'] === $bottomSection['index'])) {
            $hasSectionArrow = false;
        }
        $videos = Video::all();
        return view('courses.show', compact('course', 'topSection', 'bottomSection', 'hasSectionArrow', 'videos'));
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $course->load('user');
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $statuses = [
            Course::STATUS_PUBLISHED,
            Course::STATUS_DRAFT,
            Course::STATUS_DISABLED
        ];

        $request->validate([
            'status' => ['required', 'string', Rule::in($statuses)]
        ]);

        $course['status'] = $request['status'];
        $course->save();

        return redirect()->back()->withStatus('Status updated');
    }

    public function destroy(Course $course)
    {
        //
    }

    public function addSection(Request $request, Course $course)
    {
        $this->authorize('manageContent', $course);
        $request->validate([
            'title' => ['required', 'string']
        ]);

        $section = new Section;
        $section['title'] = $request['title'];
        $section['additional_url'] = $request['additional_url'];

        $course->sections()->save($section);

        return redirect()->back()->withMessage('Section added');
    }

    public function updateSection(Request $request, Course $course, Section $section, $position)
    {
        $this->authorize('manageContent', $course);
        if ($section['course_id'] !== $course['id']) {
            abort(404);
        }
        if (!in_array($position, ['up', 'down'])) {
            return abort(404);
        }
        $course->load('sections');
        $topSection = $course->sections->sortBy('index')->first();
        $bottomSection = $course->sections->sortByDesc('index')->first();

        if (($position === 'up' && $section['index'] === $topSection['index']) || ($position === 'down' && $section['index'] === $bottomSection['index'])) {
            return redirect()->back();
        }

        $currentIndex = $section['index'];
        $nextIndex = $position === 'up' ? $currentIndex - 1 : $currentIndex + 1;
        $nextSection = $course->sections()->whereIndex($nextIndex)->first();
        DB::transaction(function () use ($currentIndex, $nextIndex, $section, $nextSection) {
            $section['index'] = $nextIndex;
            $section->save();
            $nextSection['index'] = $currentIndex;
            $nextSection->save();
        });

        return redirect()->back();
    }

    public function deleteSection(Request $request, Course $course, Section $section)
    {
        $this->authorize('manageContent', $course);
        if ($section['course_id'] !== $course['id']) {
            abort(404);
        }

        DB::transaction(function () use ($course, $section) {
            $section->load('subSections');
            $section->subSections()->delete();
            $section->delete();

            $course->load('sections');
            $i = 1;
            foreach ($course->sections->sortBy('index') as $s) {
                $s['index'] = $i;
                $s->save();
                $i++;
            }
        });

        return redirect()->back()->withMessage('Section deleted');
    }

    public function addVideos(Request $request, Course $course, Section $section)
    {
        $this->authorize('manageContent', $course);
        if ($section['course_id'] !== $course['id']) {
            abort(404);
        }
        $request->validate([
            'video_id' => ['required', 'array'],
            'video_id.*' => ['required', 'string', 'exists:videos,id']
        ]);

        $last = $section->subSections()->orderBy('index', 'desc')->first();
        $index = is_null($last) ? 0 : $last['index'];
        foreach ($request['video_id'] as $i => $videoId) {
            $subSection = new SubSection;
            $subSection['type'] = SubSection::TYPE_VIDEO;
            $subSection['video_id'] = $videoId;
            $subSection['index'] = $index + ($i + 1);

            $section->subSections()->save($subSection);
        }

        return redirect()->back()->withMessage('Video added');
    }

    public function addQuiz(Request $request, Course $course, Section $section)
    {
        $this->authorize('manageContent', $course);
        if ($section['course_id'] !== $course['id']) {
            abort(404);
        }
        $request->validate([
            'quiz_id' => ['required', 'string', 'exists:quizzes,id']
        ]);

        $existsQuiz = $section->subSections()->whereNotNull('quiz_id')->exists();
        if ($existsQuiz) {
            return redirect()->back()->withErrors('Only one quiz allowed in same section');
        }

        $last = $section->subSections()->orderBy('index', 'desc')->first();
        $index = is_null($last) ? 0 : $last['index'];

        $subSection = new SubSection;
        $subSection['type'] = SubSection::TYPE_QUIZ;
        $subSection['quiz_id'] = $request['quiz_id'];
        $subSection['index'] = $index + 1;

        $section->subSections()->save($subSection);

        return redirect()->back()->withMessage('Quiz added');
    }

    public function training(Request $request)
    {
        $currentUser = $request->user()->load('courseEnrollments.course.subSections.video');
        if ($currentUser->hasRole(User::ROLE_USER_EMPLOYEE)) {
            $courseEnrollments = $currentUser['courseEnrollments'];
            return view('employee-training.index', compact('courseEnrollments'));
        }
        $companyOwnerId = $currentUser['company']['user_id'] ?? null;
        $courses = Course::whereStatus(Course::STATUS_PUBLISHED)
            ->where(function ($query) use ($companyOwnerId) {
                $query->where('is_custom', 0);
                if ($companyOwnerId) {
                    $query->orWhere('user_id', $companyOwnerId);
                }
            })
            ->with(['user', 'subSections.video'])
            ->get();
        return view('training.index', compact('courses'));
    }

    public function trainingProgress(Request $request, CourseEnrollment $courseEnrollment)
    {
        $this->authorize('view', $courseEnrollment);
        if ($courseEnrollment['time_start'] > now()) {
            return redirect()->back()->withErrors('Training started at ' . $courseEnrollment['time_start']);
        }
        $courseEnrollment->load('course.sections.subSections.video', 'course.sections.subSections.quiz', 'progresses', 'course.subSections', 'quizCompletions');
        $course = $courseEnrollment['course'];
        $firstSubSection = $course['subSections']->where('type', SubSection::TYPE_VIDEO)->first();
        $videoId = isset($request['video_id']) ? $request['video_id'] : (is_null($firstSubSection) ? null : $firstSubSection['video_id']);
        $quizId = isset($request['quiz_id']) ? $request['quiz_id'] : null;

        if ($videoId === null && $quizId === null) {
            abort(404);
        }

        $subSection = is_null($quizId) ? $course['subSections']->where('video_id', $videoId)->first()->load('video') : $course['subSections']->where('quiz_id', $quizId)->first()->load('quiz.items');

        $orderedSubs = collect();
        foreach ($course['sections']->sortBy('index') as $orderedSection) {
            foreach ($orderedSection['subSections']->sortBy('index') as $orderedSub) {
                $orderedSubs->push($orderedSub);
            }
        }

        $existingProgresses = $courseEnrollment['progresses'];
        $keptIds = [];
        foreach ($orderedSubs as $i => $s) {
            $progress = $existingProgresses->first(function ($p) use ($s) {
                return $s['type'] === SubSection::TYPE_VIDEO
                    ? $p['video_id'] === $s['video_id']
                    : $p['quiz_id'] === $s['quiz_id'];
            }) ?? new CourseEnrollmentProgress;
            $progress['index'] = $i + 1;
            $progress['type'] = $s['type'];
            $progress['video_id'] = $s['video_id'];
            $progress['quiz_id'] = $s['quiz_id'];
            $progress['course_enrollment_id'] = $courseEnrollment['id'];
            $progress->save();
            $keptIds[] = $progress['id'];
        }
        CourseEnrollmentProgress::where('course_enrollment_id', $courseEnrollment['id'])
            ->whereNotIn('id', $keptIds)
            ->delete();
        $courseEnrollment->load('course.sections.subSections.video', 'course.sections.subSections.quiz', 'progresses', 'course.subSections');
        $currentIndex = $courseEnrollment['progresses']->where('is_done', 1)->sortByDesc('index')->first()['index'] ?? 0;
        $currentLearn = is_null($quizId) ? $courseEnrollment['progresses']->where('video_id', $videoId)->first() : $courseEnrollment['progresses']->where('quiz_id', $quizId)->first();
        if (is_null($currentLearn)) {
            abort(404);
        }
        $pastLearn = $courseEnrollment['progresses']->where('index', ($currentLearn['index'] - 1))->first();
        $max = $courseEnrollment['progresses']->max('index');

        if ($courseEnrollment['status'] === CourseEnrollment::STATUS_NEW || $courseEnrollment['status'] === CourseEnrollment::STATUS_ON_PROGRESS) {
            if (!is_null($pastLearn) && !$pastLearn['is_done']) {
                return redirect()->back()->withErrors('Please complete previous video first');
            } else {
                DB::transaction(function () use (&$courseEnrollment, $currentLearn, $subSection, $max, &$currentIndex) {
                    if ($courseEnrollment['status'] === CourseEnrollment::STATUS_NEW) {
                        $courseEnrollment['status'] = CourseEnrollment::STATUS_ON_PROGRESS;
                    }
                    if ($currentLearn['type'] === CourseEnrollmentProgress::TYPE_VIDEO) {
                        $courseEnrollment['sub_section_id'] = $subSection['id'];
                        $courseEnrollment->save();
                        $currentLearn['is_done'] = true;
                        $currentLearn->save();
                        $currentIndex = $currentLearn['index'];
                    } else {
                        if ($currentLearn['is_done']) {
                            $courseEnrollment['sub_section_id'] = $subSection['id'];
                            $courseEnrollment->save();
                            $currentIndex = $currentLearn['index'];
                        }
                    }

                    if ($max > 0 && $currentIndex === $max) {
                        $courseEnrollment['status'] = CourseEnrollment::STATUS_COMPLETED;
                        $courseEnrollment->save();
                    }
                });
            }
        }
        $progress = $courseEnrollment['progresses'];
        $quizCompletions = $courseEnrollment['quizCompletions'];

        return view('employee-training.show', compact('courseEnrollment', 'course', 'subSection', 'currentIndex', 'progress', 'quizCompletions'));
    }

    public function trainingDetail(Request $request, Course $course)
    {
        $currentUser = $request->user()->load('company.departments');
        $companyOwnerId = $currentUser['company']['user_id'] ?? null;
        if ($course['is_custom'] && $course['user_id'] !== $companyOwnerId) {
            abort(403);
        }

        $course->load('sections.subSections.video', 'sections.subSections.quiz');
        $videoId = isset($request['video_id']) ? $request['video_id'] : null;
        $quizId = isset($request['quiz_id']) ? $request['quiz_id'] : null;
        if ($videoId !== null || $quizId !== null) {
            $subSectionId = $videoId ?? $quizId;
            $subSection = $course->subSections()->where(function ($q) use ($subSectionId) {
                $q->where('video_id', $subSectionId)->orWhere('quiz_id', $subSectionId);
            })->with(['video', 'quiz.items'])->first();
        } else {
            $subSection = $course->subSections()->where('type', SubSection::TYPE_VIDEO)->with('video')->first();
        }
        if (is_null($subSection)) {
            abort(404);
        }
        $departments = $currentUser->company->departments;
        return view('training.show', compact('course', 'subSection', 'departments'));
    }

    public function startTraining(StartTrainingRequest $request, Course $course)
    {
        $companyId = $request->user()['company_id'];
        foreach ($request['department_id'] as $departmentId) {
            $item = new CourseAssignment;
            $item['department_id'] = $departmentId;
            $item['company_id'] = $companyId;
            $item['subject'] = $request['subject'];
            $item['message'] = $request['message'];
            $item['day_completion'] = $request['day_completion'];
            $item['start_date'] = $request['start_date'];

            $course->courseAssignments()->save($item);
        }

        return redirect()->back()->withMessage('Training started');
    }

    public function downloadCertificate(CourseEnrollment $courseEnrollment)
    {
        $this->authorize('view', $courseEnrollment);
        if ($courseEnrollment['status'] === CourseEnrollment::STATUS_COMPLETED) {
            $courseEnrollment->load('course', 'user.company');
            if (is_null($courseEnrollment['certificate_no'])) {
                $courseEnrollment['certificate_no'] = strtoupper(uniqid('B-'));
                $courseEnrollment->save();
            }
            return view('employee-training.certificate', compact('courseEnrollment'));
        }

        abort(404);
    }

    public function submitQuiz(Request $request, CourseEnrollment $courseEnrollment)
    {
        $this->authorize('update', $courseEnrollment);
        $request->validate([
            'quiz_id' => ['required'],
            'answer' => ['required', 'array'],
        ]);

        $courseEnrollment->load('progresses', 'course.subSections.quiz.items');
        $progress = $courseEnrollment['progresses']->where('quiz_id', $request['quiz_id'])->first();
        $subSection = $courseEnrollment['course']['subSections']->where('quiz_id', $request['quiz_id'])->first();
        if (is_null($progress) || is_null($subSection)) {
            return redirect()->back()->withErrors('Quiz not found');
        }
        $quizItems = $subSection['quiz']['items'];
        $expectedCount = min($quizItems->count(), (int) $subSection['quiz']['show_question']);
        if (count($request['answer']) !== $expectedCount) {
            return redirect()->back()->withErrors('Please complete the answers');
        }
        $result = [];

        foreach ($request['answer'] as $itemId => $answer) {
            $item = $quizItems->where('id', $itemId)->first();
            $q = null;
            $a = null;
            $s = 0;
            if (!is_null($item)) {
                $q = $item['question'];
                $a = $answer;
                $array = collect(json_decode($item['answer'], true))->pluck('status', 'answer')->toArray();
                $s = isset($array[$answer]) && intval($array[$answer]) === 1;
            }
            if ($q !== null && $a !== null) {
                $result[] = [
                    'question' => $q,
                    'answer' => $a,
                    'status' => $s
                ];
            }
        }
        if (count($result) !== $expectedCount) {
            return redirect()->back()->withErrors('Some question is not answer properly');
        }

        $score = collect($result)->where('status', true)->count() / count($result) * 100;

        DB::transaction(function () use ($courseEnrollment, $subSection, $score, $result, $progress) {
            $qc = new QuizCompletion;
            $qc['course_enrollment_id'] = $courseEnrollment['id'];
            $qc['quiz_id'] = $subSection['quiz_id'];
            $qc['score'] = $score;
            $qc['status'] = $score >= $subSection['quiz']['min_score'] ? QuizCompletion::STATUS_PASSED : QuizCompletion::STATUS_FAILED;
            $qc->save();

            foreach ($result as $r) {
                $qca = new QuizCompletionAnswer;
                $qca['quiz_completion_id'] = $qc['id'];
                $qca['question'] = $r['question'];
                $qca['answer'] = $r['answer'];
                $qca['answer_status'] = $r['status'];
                $qca->save();
            }

            if ($qc['status'] === QuizCompletion::STATUS_PASSED) {
                $progress['is_done'] = true;
                $progress->save();
            }
        });

        return redirect('/trainings/' . $courseEnrollment['id'] . '?quiz_id=' . $subSection['quiz_id']);
    }
}
