<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyVideoRequest;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\SubSection;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $category = $request['category'];
        if ($category && !in_array($category, Video::CATEGORY_LIST, true)) {
            abort(404);
        }
        if ($category) {
            $latestVideos = [];
            $videoPerCategory = [];
            $allList = Video::where('category', 'like', '%' . $this->escapeLike($category) . '%')->where('category', '<>', Video::CATEGORY_PRIVATE)->orderByDesc('created_at')->get();
        } else {
            $latestVideos = Video::where('category', '<>', Video::CATEGORY_PRIVATE)->orderByDesc('created_at')->limit(4)->get();
            $videoPerCategory = [];
            foreach (Video::CATEGORY_LIST as $cat) {
                $videoPerCategory[$cat] = Video::where('category', 'like', '%' . $this->escapeLike($cat) . '%')->where('category', '<>', Video::CATEGORY_PRIVATE)->orderByDesc('created_at')->limit(4)->get();
            }
            $allList = [];
        }
        return view('videos.index', compact('latestVideos', 'videoPerCategory', 'allList'));
    }

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '%_\\');
    }

    public function list(Request $request)
    {
        if (auth()->user()->hasRole(User::ROLE_ADMIN)) {
            $videos = Video::with('user')->orderByDesc('created_at')->paginate(10);
        } else if (auth()->user()->hasRole(User::ROLE_CONTENT_CREATOR)) {
            $videos = Video::where('category', '<>', Video::CATEGORY_PRIVATE)->with('user')->orderByDesc('created_at')->paginate(10);
        } else {
            $currentUser = $request->user()->load('company.users');
            $userIds = $currentUser['company']->users()->pluck('id')->toArray();
            $videos = Video::where('category', Video::CATEGORY_PRIVATE)->whereIn('user_id', $userIds)->with('user')->orderByDesc('created_at')->paginate(10);
        }
        return view('videos.list', compact('videos'));
    }


    public function store(StoreVideoRequest $request)
    {
        $currentUser = $request->user();
        $newVideo = new Video;
        $newVideo = $this->setVideo($request, $newVideo);
        $currentUser->videos()->save($newVideo);

        return redirect()->back()->withMessage('Video added');
    }


    public function update(UpdateVideoRequest $request, Video $video)
    {
        $this->authorize('update', $video);
        $video = $this->setVideo($request, $video);

        $video->save();

        return redirect()->back()->withMessage('Video Updated');
    }


    public function destroy(DestroyVideoRequest $request, Video $video)
    {
        $this->authorize('delete', $video);
        SubSection::where('video_id',$video['id'])->delete();
        $video->delete();

        return redirect()->back()->withMessage('Video Deleted');
    }

    private function setVideo(StoreVideoRequest|UpdateVideoRequest $request, Video $newVideo): Video
    {
        $newVideo['title'] = $request['title'];
        $newVideo['link'] = $request['link'];
        $newVideo['source'] = $request['source'];
        $newVideo['category'] = implode(',', $request['category']);
        $newVideo['tag'] = count($request['tag']) == 0 ? null : implode(',', $request['tag']);

        if ($newVideo['source'] === Video::SOURCE_WISTIA && empty($newVideo['images'])) {
            $thumbnail = Video::fetchWistiaThumbnail($newVideo['link']);
            if (!empty($thumbnail)) {
                $newVideo['images'] = $thumbnail;
            }
        }

        return $newVideo;
    }
}
