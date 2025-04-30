<?php

use App\Models\Category;
use App\Models\GeneralSettings;
use App\Models\ParentCategory;
use App\Models\Post;
use Illuminate\Support\Str;

if (!function_exists('settings')) {
    function settings(){
        $settings = GeneralSettings::take(1)->first();

        if (!is_null($settings)){
            return $settings;
        }
    }
}

// Dynamic Navigation menu
if (!function_exists('navigations')) {
    function navigations() {
        $navegations_html = '';
        $pcategories = ParentCategory::whereHas('categoria', function($q){
            $q->whereHas('posts');
        })->orderBy('name', 'asc')->get();
    }
}

// date format
if (!function_exists('date_format')) {
    function date_format($date, $format = 'd/m/Y') {
        $date = new DateTime($date);
        return $date->format($format);
    }
}

// Strip Words
if (!function_exists('words')) {
    function words($text, $words = 15) {
       return Str::words(strip_tags($text), $words, '...');
    }
}

// Calculate post Reading duration
if (!function_exists('readDuration')) {
    function readDuration(...$text) {
       Str::macro('timeCounter', function($text){
            $totalWords = str_word_count(implode(" ", $text));
            $minToRead = round($totalWords/200);
            return (int)max(1,$minToRead);
       });
       return Str::timeCounter($text);
    }
}
// if (!function_exists('lastest_post')) {
//     function lastest_post($skip = 0, $limit = 5) {
//         return Post::skip($skip)->limit($limit)
//                     ->where('visibility', 1)
//                     ->orderBy('created_at', 'desc')
//                     ->get();
//     }
// }

// Category
if (!function_exists('category')) {
    function category() {
        return Category::withCount('posts')
                    ->having('posts_count','>', 0)
                    ->orderBy('posts_count', 'desc')
                    ->get();
    }
}



?>
