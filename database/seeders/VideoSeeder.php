<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VideoSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment('local', 'testing')) {
            Video::truncate();
        }
        $user = User::role(User::ROLE_ADMIN)->first();
        if (is_null($user)) {
            return;
        }
        if (Video::where('user_id', $user['id'])->where('tag', 'intro')->exists()) {
            return;
        }

        DB::table('videos')->insert([
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_WISTIA,
                    'link' => 'https://fast.wistia.net/embed/iframe/ociaqio4af',
                    'title' => 'Why Is Security Awareness So Important',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://embed-ssl.wistia.com/deliveries/92d575b93fc9aa85c28b768ccb476bb7fa901f4f.jpg',
                    'created_at' => now()->addSeconds(1),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_WISTIA,
                    'link' => 'https://fast.wistia.net/embed/iframe/r7mcqfcohv',
                    'title' => 'How I was Hacked on LinkedIn',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://embed-ssl.wistia.com/deliveries/2bf2716f150e4e4e4607f8cef0e7399e5a54abde.jpg',
                    'created_at' => now()->addSeconds(2),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_WISTIA,
                    'link' => 'https://fast.wistia.net/embed/iframe/l9jhrjm4w6',
                    'title' => 'New Salary Adjustments Email Scam',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://embed-ssl.wistia.com/deliveries/a112226ce665b84efb27f68f7e75ddc586bc6fc0.jpg',
                    'created_at' => now()->addSeconds(3),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_WISTIA,
                    'link' => 'https://fast.wistia.net/embed/iframe/m513ocg0zz',
                    'title' => 'My Linkedin Post Cost My Company A Fortune',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://embed-ssl.wistia.com/deliveries/0cb8814755d3bd38144b41d67aff5db140808917.jpg',
                    'created_at' => now()->addSeconds(4),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_YOUTUBE,
                    'link' => 'https://www.youtube.com/embed/UhuNd9--nGc',
                    'title' => 'Why did we hack you!',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=600&q=80',
                    'created_at' => now()->addSeconds(5),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_YOUTUBE,
                    'link' => 'https://www.youtube.com/embed/dxH7JOK23Ik',
                    'title' => 'Phishing Attack',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://images.unsplash.com/photo-1591808216268-ce0b82787efe?auto=format&fit=crop&w=600&q=80',
                    'created_at' => now()->addSeconds(6),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_YOUTUBE,
                    'link' => 'https://www.youtube.com/embed/08RQdUxUcds',
                    'title' => 'Here is how I hack you',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=600&q=80',
                    'created_at' => now()->addSeconds(7),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_YOUTUBE,
                    'link' => 'https://www.youtube.com/embed/ktZnLyoRq1Y',
                    'title' => 'Sensitive Data Exposure',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://images.unsplash.com/photo-1605379399642-870262d3d051?auto=format&fit=crop&w=600&q=80',
                    'created_at' => now()->addSeconds(8),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_YOUTUBE,
                    'link' => 'https://www.youtube.com/embed/w7P5cDKR8QE',
                    'title' => 'Injection Attacks',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=600&q=80',
                    'created_at' => now()->addSeconds(8),
                ],
                [
                    'id' => Str::orderedUuid()->toString(),
                    'user_id' => $user['id'],
                    'source' => Video::SOURCE_YOUTUBE,
                    'link' => 'https://www.youtube.com/embed/KSEVYlNLlbM',
                    'title' => 'Security Misconfigurations',
                    'category' => 'Security Awareness',
                    'tag' => 'intro',
                    'images' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80',
                    'created_at' => now()->addSeconds(8),
                ],
        ]);
    }
}
