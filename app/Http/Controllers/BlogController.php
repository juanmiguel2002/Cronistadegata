<?php

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\Setting;
use App\Models\UserSocialLink;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'pageTitle' => $title . ' - Cronista de Gata de Gorgos',
            'posts' => $posts,
        ];
        return view('front.pages.index', $data);
    }

    public function showCategory(Request $request, $slug = null) {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::where('category', $category->id)->orderBy('created_at','desc')->paginate(8);
        $title = $category->name . ' - Cronista de Gata de Gorgos';
        $description = 'Articuls de la categoria '. $category->name;

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
            $title = 'Resultas de la búsqueda: "'. $query. '"';
            $description = 'Resultados de la búsqueda para "'. $query. '"';

        } else {
            $posts = collect();

            $title = 'Search'. $query;
            $description = 'Resultados de búsqueda de publicaciones de blog en nuestro sitio web.';
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

        // Anterior Post
        $prevPost = Post::where('id', '>', $post->id)
                    // ->where('category', $post->category)
                    ->where('visibility', 1)
                    ->orderBy('id', 'asc')
                    ->first();

                    // Siguiente Post
        $nextPost = Post::where('id', '<', $post->id)
                    // ->where('category', $post->category)
                    ->orderBy('id', 'desc')
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
            'pageTitle' => $title . ' - Cronista de Gata de Gorgos',
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'nextPost' => $nextPost,
            'prevPost' => $prevPost
        ];

        return view('front.pages.single_post', $data);
    }

    public function showDestacats()
    {
        $title = 'Destacats - Cronista de Gata de Gorgos';
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

    public function contactPage() {
        $title = 'Contacte - Cronista de Gata de Gorgos';
        $description = 'Contacta amb nosaltres per a qualsevol dubte o consulta.';

        // Meta SEO
        SEOTools::setTitle($title, false);
        SEOTools::setDescription($description);
        SEOTools::opengraph()->setUrl(url()->current());
        $user = UserSocialLink::where('user_id', 2)->get();
       
        $data = [
            'pageTitle' => $title . ' - Cronista de Gata de Gorgos',
            'user' => $user
        ];
        return view('front.pages.contact', $data);
    }

    public function sendEmail(Request $request) {
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:5000',
        ]);

        Mail::to('gatadegorgos@cronista.blog')->send(new ContactoMail($datos));

        return redirect()->back()->with('success', 'El teu missatge s\'ha enviat correctament.');
    }
}
