<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function index()
    {
        $news = collect([]);
        
        try {
            $news = $this->fetchCyberNews();
        } catch (\Exception $e) {
            $news = collect([]);
        }

        return view('notifications.index', compact('news'));
    }

    private function fetchCyberNews()
    {
        $newsItems = [];
        
        // جميع مصادر الأخبار
        $sources = [
            [
                'url' => 'https://feeds.feedburner.com/TheHackersNews',
                'name' => 'The Hacker News',
                'icon' => 'security',
                'color' => 'text-[#4ADE80]',
            ],
            [
                'url' => 'https://krebsonsecurity.com/feed/',
                'name' => 'Krebs on Security',
                'icon' => 'shield',
                'color' => 'text-[#60A5FA]',
            ],
            [
                'url' => 'https://www.schneier.com/feed/atom/',
                'name' => 'Schneier on Security',
                'icon' => 'article',
                'color' => 'text-purple-400',
            ],
            [
                'url' => 'https://www.darkreading.com/rss.xml',
                'name' => 'Dark Reading',
                'icon' => 'visibility',
                'color' => 'text-red-400',
            ],
            [
                'url' => 'https://threatpost.com/feed/',
                'name' => 'Threatpost',
                'icon' => 'warning',
                'color' => 'text-yellow-400',
            ],
            [
                'url' => 'https://www.csoonline.com/feed/',
                'name' => 'CSO Online',
                'icon' => 'business',
                'color' => 'text-[#4ADE80]',
            ],
            [
                'url' => 'https://www.securityweek.com/feed',
                'name' => 'SecurityWeek',
                'icon' => 'lock',
                'color' => 'text-[#60A5FA]',
            ],
            [
                'url' => 'https://www.bleepingcomputer.com/feed/',
                'name' => 'Bleeping Computer',
                'icon' => 'computer',
                'color' => 'text-orange-400',
            ],
        ];
        
        foreach ($sources as $source) {
            try {
                $response = Http::timeout(10)->get($source['url']);
                
                if ($response->successful()) {
                    $xml = simplexml_load_string($response->body());
                    
                    if ($xml) {
                        $items = $xml->channel->item ?? [];
                        
                        foreach ($items as $item) {
                            $title = (string) $item->title;
                            $link = (string) $item->link;
                            $description = strip_tags((string) $item->description);
                            $pubDate = (string) $item->pubDate;
                            
                            $newsItems[] = [
                                'title' => $title,
                                'link' => $link,
                                'description' => substr($description, 0, 200) . '...',
                                'source' => $source['name'],
                                'icon' => $source['icon'],
                                'color' => $source['color'],
                                'pubDate' => $pubDate,
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                // تجاهل فشل المصدر
            }
        }
        
        // ترتيب حسب التاريخ
        usort($newsItems, function($a, $b) {
            return strtotime($b['pubDate']) - strtotime($a['pubDate']);
        });
        
        return array_slice($newsItems, 0, 30);
    }

    public function markRead($id)
    {
        try {
            DB::table('notifications')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->update(['is_read' => true]);
                
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}