<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    //
    public function addPost(Request $request) {
        $categories_html = '';
        $pcategories = ParentCategory::whereHas('categoria')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();

        if(count($pcategories) > 0) {
            foreach($pcategories as $pcategory) {
                $categories_html .= '<optgroup label="'.$pcategory->name.'">';
                foreach($pcategory->categoria as $category) {
                    $categories_html .= '<option value="'.$category->id.'">'.$category->name.'</option>';
                }
                $categories_html .= '</optgroup>';
            }
        }
        if(count($categories) > 0) {
            $categories_html .= '<option value="">Seleccionar Categoria</option>';
            foreach($categories as $category) {
                $categories_html .= '<option value="'.$category->id.'">'.$category->name.'</option>';
            }
        }
        $data = [
            'pageTitle' => 'Nou Article',
            'categories' => $categories_html
        ];

        return view('back.pages.add_post', $data);
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'content' => 'nullable',
            'category' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp'
        ]);
        $path = 'images/posts/';
        $resized_image = $path. 'resized/';

        if ($request->hasFile('featured_image')) {
            // Procesar y almacenar la imagen
            $file = $request->file('featured_image');
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path($path), $imageName);

            // generate thumbnail image
            if(!File::isDirectory($resized_image)){
                File::makeDirectory($resized_image, 0777,true,true);
            }

            // thumbnail
            Image::make($path. $imageName)
                ->fit(250,250)
                ->save($resized_image . 'thumb_'. $imageName);

            // resized
            Image::make($path. $imageName)
                ->fit(512,320)
               ->save($resized_image . 'resized_'. $imageName);
        }

        // Crear el post
        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->category = $request->category;
        $post->content = $request->content;
        $post->visibility = $request->visibility;   
        $post->featured_image = $imageName ?? null;
        $post->slug = Str::slug($request->title);
        $save = $post->save();
        // Guardar imágenes adicionales para el carrusel
        $image_path = 'images/posts/carousel/';
        $resized_imagen = $image_path . 'resized/';
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $name = time() . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
                $image->move(public_path($path), $name);

                if(!File::isDirectory($resized_imagen)){
                    File::makeDirectory($resized_imagen, 0777,true,true);
                }

                Image::make($path . $name)
                    ->fit(250, 250)
                    ->save($resized_imagen . 'thumb_' . $name);

                Image::make($path . $name)
                    ->fit(1024, 640)
                    ->save($resized_imagen . 'resized_' . $name);

                PostImage::create([
                    'post_id' => $post->id,
                    'image_name' => $name
                ]);
            }
        }

        if ($save) {
            return redirect()->to('/admin/posts')->with('success', 'Article Publicat!');
        } else {
            return redirect()->back()->with('error', 'ERROR NO SE A GUARDADO');
        }
    }

    public function allPosts(){
        $data =[
            'pageTitle' => 'Articles'
        ];
        return view('back.pages.posts', $data);
    }

    public function editPost(Request $request, $id = null) {

        $post = Post::findOrFail($id);
        $categories_html = '';
        $pcategories = ParentCategory::whereHas('categoria')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();
        if(count($pcategories) > 0) {
            foreach($pcategories as $pcategory) {
                $categories_html .= '<optgroup label="'.$pcategory->name.'">';
                foreach($pcategory->categoria as $category) {
                    if($category->id == $post->category) {
                        $categories_html .= '<option selected value="'.$category->id.'">'.$category->name.'</option>';
                    } else {
                        $categories_html .= '<option value="'.$category->id.'">'.$category->name.'</option>';
                    }
                }
                $categories_html .= '</optgroup>';
            }
        }

        if(count($categories) > 0) {
            foreach($categories as $category) {
                if($category->id == $post->category) {
                    $categories_html .= '<option selected value="'.$category->id.'">'.$category->name.'</option>';
                } else {
                    $categories_html .= '<option value="'.$category->id.'">'.$category->name.'</option>';
                }
            }
        }

        $data = [
            'pageTitle' => 'Edit Post',
            'post' => $post,
            'categories' => $categories_html
        ];

        return view('back.pages.edit_post', $data);
    }

    public function updatePost(Request $request){

        $id = $request->post_id;
        $post = Post::findOrFail($id);
        $featured_image_name = $post->featured_image;

        $request->validate([
            'title' =>'required|max:255|unique:posts,title,'. $post->id,
            'content' =>'nullable',
            'category' =>'required|exists:categories,id',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
        ]);

        if ($request->hasFile('featured_image')) {
            $old_image = $post->featured_image;
            $path = 'images/posts/';
            $file = $request->file('featured_image');
            $new_imageName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $upload = $file->move(public_path($path), $new_imageName);

            if ($upload) {
                // Definir las rutas para las imágenes redimensionadas
                $resized_path = $path . 'resized/';

                // Verifica si ya existen las versiones redimensionadas
                $thumb_exists = File::exists(public_path($resized_path . 'thumb_' . $new_imageName));
                $resized_exists = File::exists(public_path($resized_path . 'resized_' . $new_imageName));

                // Si las versiones redimensionadas no existen, las generamos
                if (!$thumb_exists) {
                    // thumbnail
                    Image::make(public_path($path . $new_imageName))
                        ->fit(250, 250)
                        ->save(public_path($resized_path . 'thumb_' . $new_imageName));
                }

                if (!$resized_exists) {
                    // resized
                    Image::make(public_path($path . $new_imageName))
                        ->fit(512, 320)
                        ->save(public_path($resized_path . 'resized_' . $new_imageName));
                }

                // Eliminar la imagen anterior
                if ($old_image != null && File::exists(public_path($path . $old_image))) {
                    File::delete(public_path($path . $old_image));

                    // Eliminar las versiones redimensionadas de la imagen anterior si existen
                    if (File::exists(public_path($resized_path . 'resized_' . $old_image))) {
                        File::delete(public_path($resized_path . 'resized_' . $old_image));
                    }
                    if (File::exists(public_path($resized_path . 'thumb_' . $old_image))) {
                        File::delete(public_path($resized_path . 'thumb_' . $old_image));
                    }
                }

                $featured_image_name = $new_imageName;
            } else {
                return redirect()->back()->with('error', 'Something went wrong while uploading featured image.');
            }
        }

        // update post
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->category = $request->category;
        $post->content = $request->content;
        $post->featured_image = $featured_image_name;
        $post->visibility = $request->visibility;

        if ($post->save()) {
            return redirect()->back()->with('success', 'Article actualitzat!');
        } else {
            return redirect()->back()->with('error', 'ERROR NO SE A GUARDADO');
        }
    }
}
