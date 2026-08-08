<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;

class AdminController extends Controller
{
    //
    public function adminDashboard(Request $request){
        // Artícles creats en els darrers 6 mesos (agrupat en PHP per ser agnòstic de BD)
        $start = now()->startOfMonth()->subMonths(5);
        $postsByMonthRaw = Post::where('created_at', '>=', $start)
            ->get(['created_at'])
            ->groupBy(fn ($post) => $post->created_at->format('Y-m'))
            ->map->count();

        $monthlyPosts = collect(range(0, 5))->map(function ($i) use ($start, $postsByMonthRaw) {
            $month = $start->copy()->addMonths($i);
            return [
                'label' => ucfirst($month->translatedFormat('M')),
                'count' => (int) ($postsByMonthRaw[$month->format('Y-m')] ?? 0),
            ];
        });

        $data = [
            'pageTitle' => 'Dashboard',
            'postsCount' => Post::count(),
            'categoriesCount' => Category::count(),
            'parentCategoriesCount' => ParentCategory::count(),
            'carouselCount' => Carousel::count(),
            'totalVisits' => (int) Post::sum('visitas'),
            'monthlyPosts' => $monthlyPosts,
            'recentPosts' => Post::with('post_category')->latest()->take(5)->get(),
            'mostVisitedPosts' => Post::with('post_category')->orderByDesc('visitas')->take(5)->get(),
        ];

        return view('back.pages.dashboard', $data);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('admin.login')->with('fail', 'Se ha cerrado la sesión');
    }

    public function profileView(Request $request){
        $data = [
            'pageTitle' => 'Perfil',
        ];

        return view('back.pages.profile', $data);
    }

    public function updateProfilePicture(Request $request){
        $request->validate([
            'profilePicture' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'profilePicture.required' => 'Debe seleccionar una imagen.',
            'profilePicture.image' => 'El archivo debe ser una imagen.',
            'profilePicture.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, webp.',
            'profilePicture.max' => 'La imagen no puede superar los 2MB.',
        ]);

        $user = User::findOrFail(Auth::id());
        $path = 'images/users/';
        $file = $request->file('profilePicture');
        $old = $user->getAttributes()['picture'];
        $file_name = 'IMG_' . $user->name . '.' . $file->getClientOriginalExtension();

        $upload = Kropify::getFile($file, $file_name)->maxWoH(255)->save($path);
        if($upload){
            if($old != null && File::exists(public_path($path.$old))){
               File::delete(public_path($path. $old));
            }
            $user->update(['picture' => $file_name]);

            return response()->json(['status' => 'success', 'message' => 'La foto de perfil se actualizó correctamente']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Error en la actualización de la foto de perfil']);
        }
    }

    public function generalSettings(Request $request){
        $data = [
            'pageTitle' => 'Ajustes Generales',
        ];

        return view('back.pages.general-settings', $data);
    }

    public function personalizar(Request $request){
        $data = [
            'pageTitle' => 'Personalizar',
        ];

        return view('back.pages.personalizar', $data);
    }
    public function carrusel() {
        $data = [
            'pageTitle' => 'Carrusel',
        ];

        return view('back.pages.carrusel', $data);
    }

    public function categoriesPage(Request $request){
        $data = [
            'pageTitle' => 'Categoríes',
        ];

        return view('back.pages.categories', $data);
    }

}
