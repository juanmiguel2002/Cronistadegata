<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public $monthsValencian = [
        1 => 'gener',
        2 => 'febrer',
        3 => 'març',
        4 => 'abril',
        5 => 'maig',
        6 => 'juny',
        7 => 'juliol',
        8 => 'agost',
        9 => 'setembre',
        10 => 'octubre',
        11 => 'novembre',
        12 => 'desembre',
    ];

    public function index() {
        $title = isset(settings()->site_name) ? settings()->site_name : '';
        $description = isset(settings()->site_description)? settings()->site_description : '';
        $imageURL = isset(settings()->site_logo)? asset('storage/'.settings()->site_logo) : '';
        $currentUrl = url()->current();

        // Meta SEO
        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOMeta::setKeywords($currentUrl);

        // Open Graph
        SEOTools::opengraph()->setUrl($currentUrl);
        SEOTools::opengraph()->addImage($imageURL);
        SEOTools::opengraph()->addProperty('type', 'articles');

        // Obtener el límite de paginación desde la configuración
        $paginationLimit = Setting::where('key', 'pagination_limit')->value('value');

        $posts = Post::where('visibility', 1)->orderBy('created_at','desc')->paginate((int) $paginationLimit);

        $data = [
            'pageTitle' => $title,
            'posts' => $posts,
        ];
        return view('front.pages.index', $data);
    }

    public function showCategory(Request $request, $slug = null) {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::where('category', $category->id)->orderBy('created_at','desc')->paginate(8);
        $title = $category->name;
        $description = 'Browse the latest articles in the'. $category->name.
            ' category. Stay wpdated with articles, insights and tutorials';

        // Meta SEO
        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOTools::opengraph()->setUrl(url()->current());

        $data = [
            'pageTitle' => $category->name,
            'posts' => $posts
        ];
        return view('front.pages.category_posts', $data);
    }

    public function showSearch(Request $request) {
        $query = $request->input('query');

        if ($query) {
            $keywords = explode(' ', $query);
            $postsQuery = Post::query();

            foreach ($keywords as $keyword) {
                $postsQuery->orWhere('title', 'LIKE', '%'. $keyword. '%');
            }
            $posts = $postsQuery->where('visibility', 1)
                                ->orderBy('created_at','desc')
                                ->paginate(10);
            $title = 'Resultas de la búsqueda';
            $description = 'Resultados de la búsqueda para "'. $query. '"';

        } else {
            $posts = collect();

            $title = 'Search'. $query;
            $description = 'Search results for blog posts on our website.';
        }
        // Meta SEO
        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);

        $data = [
            'pageTitle' => $title,
            'query' => $query,
            'posts' => $posts
        ];
        return view('front.pages.search_posts', $data);

    }

    public function filterPosts(Request $request)
    {
        // Validar que al menos uno de los filtros esté presente
        if (!$request->filled('year') && !$request->filled('month')) {
            return redirect()->back()->with('error', 'Ha de seleccionar almenys un filtre (any o mes).');
        }
        $query = Post::query();

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }


        // Obtener el nombre del mes en valenciano
        $monthName = $request->filled('month') ? $this->monthsValencian[(int) $request->month] : 'Tots els mesos';

        // Construir el título
        $title = 'Filtrat per ' .
            ($monthName ? ucfirst($monthName) . " del "
            . ($request->year ?? 'Tots els anys') : '');

        SEOTools::setTitle($title, false);

        $posts = $query->paginate(20);
        $data = [
            'pageTitle' => $title,
            'posts' => $posts,
        ];
        return view('front.pages.filter_posts', $data);
    }

    public function showPost(Request $request, $slug) {

        $post = Post::where('slug', $slug)->firstOrFail();

        // Incrementar el contador de visitas
        $post->increment('visitas');
        // Related Posts
        $relatedPosts = Post::where('category',$post->category)
                    ->where('id', '!=', $post->id)
                    ->orderBy('id', 'asc')
                    ->take(3)->get();

        // Next Post
        $nextPost = Post::where('id', '>', $post->id)
                    ->where('category', $post->category)
                    ->orderBy('id', 'asc')
                    ->first();

        // Previous Post
        $prevPost = Post::where('id', '<', $post->id)
                    ->where('category', $post->category)
                    ->where('visibility', 1)
                    ->first();

        $title = $post->title;
        $description = ($post->meta_description != '') ? $post->meta_description : words($post->content,35);

        // Meta SEO
        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::opengraph()->addProperty('type', 'article');
        SEOTools::opengraph()->addImage(asset('images/posts'. $post->featured_image));


        $data = [
            'pageTitle' => $title,
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'nextPost' => $nextPost,
            'previousPost' => $prevPost
        ];

        return view('front.pages.single_post', $data);
    }

    public function showDestacats()
    {
        $title = 'Destacats';
        $description = 'Artícles més visitats del Cronista de Gata de Gorgos';

        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOTools::opengraph()->setUrl(url()->current());

        $paginationLimit = Setting::where('key', 'pagination_destacados')->value('value');

        // Obtener los posts con más visitas (puedes ajustar el límite)
        $posts = Post::where('visibility', 1)->orderBy('visitas', 'desc')->paginate((int) $paginationLimit);

        $data = [
            'pageTitle' => $title,
            'posts' => $posts,
        ];

        return view('front.pages.destacats', $data);
    }
}
