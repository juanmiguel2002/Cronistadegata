<?php

use App\Models\Category;
use App\Models\GeneralSettings;
use App\Models\Setting;
use App\Models\UserSocialLink;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

if (!function_exists('settings')) {
    function settings(){
        return Cache::rememberForever('general_settings', function () {
            return GeneralSettings::take(1)->first();
        });
    }
}

// Cached lookup for key/value settings (e.g. pagination_limit, pagination_destacados)
if (!function_exists('setting_value')) {
    function setting_value(string $key, $default = null) {
        return Cache::rememberForever("setting_$key", function () use ($key, $default) {
            return Setting::where('key', $key)->value('value') ?? $default;
        });
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

// Genera una meta description neta a partir del contingut HTML del post.
// - Insereix un espai davant de cada etiqueta perquè els blocs (</p><p>, </h2>...)
//   no fusionin l'última paraula d'un bloc amb la primera del següent.
// - Decodifica les entitats HTML (&nbsp;, &egrave;, &amp;...).
// - Normalitza els espais en blanc i retalla a la longitud indicada sense partir paraules.
if (!function_exists('meta_description')) {
    function meta_description($html, $length = 160) {
        $text = strip_tags(str_replace('<', ' <', (string) $html));
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        $text = mb_substr($text, 0, $length);
        $lastSpace = mb_strrpos($text, ' ');
        if ($lastSpace !== false) {
            $text = mb_substr($text, 0, $lastSpace);
        }

        return rtrim($text, " .,;:") . '…';
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

// URL d'imatge de post amb variant lleugera i fallback a l'original.
// $subdir: '' per a la destacada, 'carousel/' per a la galeria.
if (!function_exists('post_image_url')) {
    function post_image_url(?string $name, ?string $variant = 'resized', string $subdir = '') {
        if (!$name) {
            return asset('storage/images/posts/sin_imagen.jpg');
        }

        if ($variant) {
            $base = pathinfo($name, PATHINFO_FILENAME);
            // El $subdir només afecta la carpeta de la variant (p. ex. carousel/resized/).
            $variantDir = 'images/posts/' . $subdir . 'resized/';

            // 1) Variant WebP nova
            $webp = $variantDir . $variant . '_' . $base . '.webp';
            if (is_file(public_path('storage/' . $webp))) {
                return asset('storage/' . $webp);
            }

            // 2) Variant antiga (mateixa extensió que l'original)
            $legacy = $variantDir . $variant . '_' . $name;
            if (is_file(public_path('storage/' . $legacy))) {
                return asset('storage/' . $legacy);
            }
        }

        // 3) Original: sempre a images/posts/ (destacada i galeria s'hi mouen)
        return asset('storage/images/posts/' . $name);
    }
}

// Category
if (!function_exists('category')) {
    function category() {
        return Category::withCount('posts')
                    ->having('posts_count','>', 0)
                    ->orderBy('posts_count', 'desc')
                    ->get();
    }
}
