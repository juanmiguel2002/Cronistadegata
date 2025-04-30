<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;

class AdminController extends Controller
{
    //
    public function adminDashboard(Request $request){
        $data = [
            'pageTitle' => 'Artícles',
        ];

        return view('back.pages.posts', $data);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('admin.login')->with('fail', 'Se ha cerrado la sesión');
    }

    public function profileView(Request $request){
        $data = [
            'pageTitle' => 'Profile',
        ];

        return view('back.pages.profile', $data);
    }

    public function updateProfilePicture(Request $request){
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
