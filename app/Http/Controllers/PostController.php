<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPostImage;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use App\Models\PostImage;
use App\Services\ImageProcessor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    //
    public function addPost(Request $request) {
        $pcategories = ParentCategory::whereHas('categoria')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();

        $data = [
            'pageTitle' => 'Nou Article',
            'pcategories' => $pcategories,
            'categories' => $categories,
            'selectedCategory' => null,
        ];

        return view('back.pages.add_post', $data);
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts,title|max:255',
            'content' => 'nullable',
            'category' => 'required|exists:categories,id',
            'visibility' => 'required|in:0,1',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192|dimensions:max_width=6000,max_height=6000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192|dimensions:max_width=6000,max_height=6000'
        ], [
            'title.required' => 'El títol és obligatori.',
            'title.unique' => 'El títol ja existeix. Si us plau, trieu un altre títol.',
            'title.max' => 'El títol no pot tenir més de 255 caràcters.',
            'category.required' => 'La categoria és obligatòria.',
            'category.exists' => 'La categoria seleccionada no és vàlida.',
            'visibility.required' => 'La visibilitat és obligatòria.',
            'visibility.in' => 'La visibilitat seleccionada no és vàlida.',
            'featured_image.image' => 'El fitxer de la imatge destacada ha de ser una imatge.',
            'featured_image.mimes' => 'La imatge destacada ha de ser un fitxer de tipus: jpeg, png, jpg, gif, svg, webp.',
            'featured_image.max' => 'La imatge destacada no pot superar els 8 MB.',
            'featured_image.dimensions' => 'La imatge destacada és massa gran (màxim 6000×6000 px).',
            'images.*.image' => 'Tots els fitxers de la galeria han de ser imatges.',
            'images.*.mimes' => 'Les imatges de la galeria han de ser fitxers de tipus: jpeg, png, jpg, gif, svg, webp.',
            'images.*.max' => 'Cada imatge de la galeria no pot superar els 8 MB.',
            'images.*.dimensions' => 'Alguna imatge de la galeria és massa gran (màxim 6000×6000 px).'
            ]
        );
        $postsDir = public_path('storage/images/posts/');

        if ($request->hasFile('featured_image')) {
            // Desa l'original ràpidament; el processat pesat va a la cua.
            $file = $request->file('featured_image');
            $imageName = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $file->move($postsDir, $imageName);

            ProcessPostImage::dispatch($imageName, 'featured');
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

        // Imatges addicionals del carrusel
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $name = Str::uuid() . '.' . strtolower($image->getClientOriginalExtension() ?: 'jpg');
                $image->move($postsDir, $name);

                ProcessPostImage::dispatch($name, 'gallery');

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

    /**
     * Esborra l'original de la imatge destacada i totes les seves variants,
     * cobrint tant l'esquema WebP nou com el d'extensió original antic.
     */
    private function deleteFeaturedImageFiles(?string $name): void
    {
        if (!$name) {
            return;
        }

        $postsDir = public_path('storage/images/posts/');
        $base = pathinfo($name, PATHINFO_FILENAME);

        $candidates = [
            $postsDir . $name,
            $postsDir . 'resized/resized_' . $base . '.webp',
            $postsDir . 'resized/thumb_' . $base . '.webp',
            $postsDir . 'resized/resized_' . $name,
            $postsDir . 'resized/thumb_' . $name,
        ];

        foreach ($candidates as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    /**
     * Endpoint de pujada d'imatges del contingut (CKEditor SimpleUploadAdapter).
     * Desa la imatge com a fitxer WebP optimitzat i retorna la seva URL,
     * evitant que s'incrusti com a base64 dins del contingut.
     */
    public function uploadContentImage(Request $request, ImageProcessor $processor)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:8192|dimensions:max_width=6000,max_height=6000',
        ]);

        $dir = public_path('storage/images/posts/content/');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0775, true, true);
        }

        $file = $request->file('upload');
        $base = (string) Str::uuid();
        $tmpName = $base . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $file->move($dir, $tmpName);

        $webpName = $base . '.webp';
        $processor->convertToWebp($dir . $tmpName, $dir . $webpName, 1280, 82);

        // Elimina l'original no optimitzat un cop generat el WebP.
        if (is_file($dir . $tmpName)) {
            @unlink($dir . $tmpName);
        }

        return response()->json([
            'url' => asset('storage/images/posts/content/' . $webpName),
        ]);
    }

    public function allPosts(){
        $data =[
            'pageTitle' => 'Articles'
        ];
        return view('back.pages.posts', $data);
    }

    public function editPost(Request $request, $id = null) {

        $post = Post::findOrFail($id);
        $pcategories = ParentCategory::whereHas('categoria')->orderBy('name', 'asc')->get();
        $categories = Category::where('parent', 0)->orderBy('name', 'asc')->get();

        $data = [
            'pageTitle' => 'Edit Post',
            'post' => $post,
            'pcategories' => $pcategories,
            'categories' => $categories,
            'selectedCategory' => $post->category,
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
            'visibility' => 'required|in:0,1',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192|dimensions:max_width=6000,max_height=6000',
        ], [
            'visibility.required' => 'La visibilitat és obligatòria.',
            'visibility.in' => 'La visibilitat seleccionada no és vàlida.',
            'featured_image.max' => 'La imatge destacada no pot superar els 8 MB.',
            'featured_image.dimensions' => 'La imatge destacada és massa gran (màxim 6000×6000 px).',
        ]);

        if ($request->hasFile('featured_image')) {
            $old_image = $post->featured_image;
            $file = $request->file('featured_image');
            $new_imageName = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
            $upload = $file->move(public_path('storage/images/posts/'), $new_imageName);

            if ($upload) {
                // Processat pesat (optimitzar + variants) a la cua.
                ProcessPostImage::dispatch($new_imageName, 'featured');

                // Esborra l'anterior i les seves variants.
                $this->deleteFeaturedImageFiles($old_image);

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
