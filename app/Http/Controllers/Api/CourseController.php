<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Video;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function authorizeCourse(Request $request, Course $course): void
    {
        $this->authorize('manageContent', $course);
    }

    public function videoList(Request $request, Course $course)
    {
        $this->authorizeCourse($request, $course);

        $currentUser = $request->user()->load('company.users');
        $creatorId = $currentUser->company->users()->pluck('id')->toArray();
        $course->load('subSections');
        $videoId = $course->subSections()->pluck('video_id')->toArray();

        $videos = Video::where(function ($q) use ($creatorId) {
            $q->whereIn('user_id', $creatorId)
                ->orWhere('category', '<>', Video::CATEGORY_PRIVATE);
        })->get();
        $htmlResponse = '<thead>
                        <tr>
                            <th>#</th>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Categories</th>
                        </tr>
                        </thead>
                        <tbody>';
        foreach ($videos as $video) {
            if (!in_array($video['id'], $videoId)) {
                $htmlResponse .= '<tr>
                                <td><input type="checkbox" name="video_id[]" value="' . e($video['id']) . '" class="form-check-input"></td>
                                <td><img src="' . e($video['thumbnail_img']) . '"
                                         style="height: 60px; width: auto"></td>
                                <td>
                                    ' . e($video['title']) . '<br>
                                    <img src="/dashboard/assets/images/icons/' . e($video['source']) . '.png"
                                         style="height: 15px; width: auto">
                                    <small>' . e($video['created_at']->format('j F Y, H:i')) . '</small>
                                </td>
                                <td>' . e($video['category']) . '</td>
                            </tr>';
            }
        }
        $htmlResponse .= '</tbody>';

        return $htmlResponse;
    }

    public function quizList(Request $request, Course $course)
    {
        $this->authorizeCourse($request, $course);

        $currentUser = $request->user()->load('company.users');
        $creatorId = $currentUser->company->users()->pluck('id')->toArray();
        $course->load('subSections');
        $quizId = $course->subSections()->pluck('quiz_id')->toArray();

        $quizzes = Quiz::where(function ($q) use ($creatorId) {
            $q->whereIn('user_id', $creatorId)
                ->orWhere('is_custom', 0);
        })->get();
        $htmlResponse = '<thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Min. Score</th>
                        </tr>
                        </thead>
                        <tbody>';
        foreach ($quizzes as $quiz) {
            if (!in_array($quiz['id'], $quizId)) {
                $htmlResponse .= '<tr>
                                <td><input type="radio" name="quiz_id" value="' . e($quiz['id']) . '" class="form-check-input"></td>
                                <td>' . e($quiz['title']) . '</td>
                                <td>' . e($quiz['min_score']) . ' %</td>
                            </tr>';
            }
        }
        $htmlResponse .= '</tbody>';

        return $htmlResponse;
    }
}
