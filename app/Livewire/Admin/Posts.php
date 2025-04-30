<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $categories_html;

    public $search = '';
    public $user, $category, $visibility, $sort = 'desc';
    public $post_visibility;

    protected $queryString = [
        'search' => ['except' => ''],
        'user' => ['except' => ''],
        'category' => ['except' => ''],
        'visibility' => ['except' => ''],
        'sort' => ['except' => ''],
    ];

    protected $listeners = ['deletePostAction'];

    public function updatedSearch() {
        $this->resetPage();
    }
    public function updatedUser() {
        $this->resetPage();
    }
    public function updatedCategory() {
        $this->resetPage();
    }
    public function updatedVisibility() {
        $this->resetPage();
        $this->post_visibility = $this->visibility == 'public' ? 1 : 0;
    }

    public function updatedSort() {
        $this->resetPage();
    }

    public function mount() {
        $this->user = Auth::user()->type == "superAdmin" ? Auth::user()->id : '';
        $this->post_visibility = $this->visibility == 'public' ? 1 : 0;
        $categories_html = '';

        $pcategories = ParentCategory::whereHas('categoria', function($q){
            $q->whereHas('posts');
        })->orderBy('name', 'asc')->get();

        $categories = Category::whereHas('posts')->where('parent', 0)->orderBy('name', 'asc')->get();

        if(count($pcategories) > 0){
            foreach($pcategories as $pc){
                $categories_html.= '<optgroup label="'.$pc->name.'">';
                foreach($pc->categoria as $c){
                    $categories_html.= '<option value="'.$c->id.'">'.$c->name.'</option>';
                }
                $categories_html.= '</optgroup>';
            }
        }

        if (count($categories) > 0) {
            foreach ($categories as $c) {
                $categories_html.= '<option value="'.$c->id.'">'.$c->name.'</option>';
            }
        }
        $this->categories_html = $categories_html;

    }

    public function deletePost($id){
        $this->dispatch('deletePost', ['id' => $id]);
        // Post::find($id)->delete();
        // $this->resetPage();
        // session()->flash('message', 'Post deleted successfully!');
    }
    public function deletePostAction($id) {
        $post = Post::find($id);
        $path = 'images/posts/';
        $resized_path = $path.'resized/';
        $old_featured_image = $post->featured_image;

        if($old_featured_image != "" && File::exists(public_path($path.$old_featured_image))) {
            File::delete(public_path($path.$old_featured_image));

            if(File::exists(public_path($resized_path.'resized_'.$old_featured_image))) {
                File::delete(public_path($resized_path.'resized_'.$old_featured_image));
            }
            if(File::exists(public_path($resized_path.'thumb_'.$old_featured_image))) {
                File::delete(public_path($resized_path.'thumb_'.$old_featured_image));
            }
        }
        $delete = $post->delete();
        if($delete) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Post has been deleted successfully']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Error deleting post!']);
        }
    }

    public function render()
    {
        $posts = Auth::user()->type == "superAdmin" ?
        Post::search(trim($this->search))
            ->when($this->user, function ($query){
                $query->where('user_id', $this->user);
            })
            ->when($this->category, function ($query){
                $query->where('category', $this->category);
            })
            ->when($this->visibility, function ($query){
                $query->where('visibility', $this->post_visibility);
            })->when($this->sort, function ($query){
                $query->orderBy('id', $this->sort);
            })->paginate($this->perPage) :
        Post::search(trim($this->search))
            ->when($this->user, function ($query){
                $query->where('user_id', $this->user);
            })
            ->when($this->visibility, function ($query){
                $query->where('visibility', $this->post_visibility);
            })
            ->when($this->category, function ($query){
                $query->where('category', $this->category);
            })->when($this->sort, function ($query){
                $query->orderBy('id', $this->sort);
            })
            ->paginate($this->perPage);
        return view('livewire.admin.posts',[
            'posts' =>  $posts// Fetching all posts with pagination
        ]);
    }
}
