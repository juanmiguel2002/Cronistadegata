<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\ParentCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $isUpdatePCategory = false;
    public $pcategory_id, $pcategory_name;
    public $isUpdateCategory = false;
    public $category_id, $category_name, $parent = 0;

    public $pcategoryPage = 5;
    public $categoryPage = 10;

    protected $listeners = [
        'updatePCategoryOrder',
        'confirmDeleteParentCategory',
        'confirmDeleteCategory',
        'updateCategoryOrder'
    ];

    public function addParentCategory() {
        $this->pcategory_id = null;
        $this->pcategory_name = null;
        $this->isUpdatePCategory = false;
        $this->showParentCategoryModalForm();
    }

    public function createParentCategory() {
        $this->validate([
            'pcategory_name' =>'required|unique:parent_categories,name',
        ],[
            'pcategory_name.required' => 'Parent category name is required',
            'pcategory_name.unique' => 'Parent category name already exists',
        ]);

        $pcategory = new ParentCategory();
        $pcategory->name = $this->pcategory_name;
        $save = $pcategory->save();

        if ($save) {
            $this->closePcategoria();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Parent category added successfully']);
        }else{
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Ha ocurrido un error']);
        }

    }

    public function updatePCategoryOrder($positions) {
        foreach ($positions as $position) {
            $index = $position[0];
            $newPosition = $position[1];
            ParentCategory::where('id', $index)->update(['order' => $newPosition]);
        }
        $this->dispatch('showToastr', ['type' =>'success', 'message' => 'Categorías ordenadas correctamente']);
    }

    public function showParentCategoryModalForm() {
        $this->resetErrorBag();
        $this->dispatch('showParentCategoryModalForm');
    }

    public function editParentCategory($id) {
        $pcategory = ParentCategory::find($id);
        $this->pcategory_id = $pcategory->id;
        $this->pcategory_name = $pcategory->name;
        $this->isUpdatePCategory = true;
        $this->showParentCategoryModalForm();
    }

    public function updateParentCategory() {
        $pcategory = ParentCategory::find($this->pcategory_id);

        $this->validate([
            'pcategory_name' =>'required|unique:parent_categories,name,'.$this->pcategory_id,
        ],[
            'pcategory_name.required' => 'Parent category name is required',
            'pcategory_name.unique' => 'Parent category name already exists',
        ]);

        $pcategory->name = $this->pcategory_name;
        $pcategory->slug = null;
        $save = $pcategory->save();

        if ($save) {
            $this->closePcategoria();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Parent category updated successfully']);
        }else{
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Ha ocurrido un error']);
        }
    }

    public function deleteParentCategory($id) {
        $this->dispatch('deleteParentCategory', ['id' =>$id]);
    }

    public function confirmDeleteParentCategory($id) {
        $pcategory = ParentCategory::find($id);

        if ($pcategory->categoria->count() > 0) {
            foreach ($pcategory->categoria as $category) {
                $category::where('id', $category->id)->update(['parent' => 0]);
            }
        }

        $pcategory->delete();

        $this->dispatch('showToastr', ['type' =>'success', 'message' => 'Categorías eliminada correctamente']);
    }
    // CATEGORIAS

    public function addCategory(){
        $this->category_id = null;
        $this->category_name = null;
        $this->parent = 0;
        $this->isUpdateCategory = false;
        $this->showCategoryForm();
    }

    public function showCategoryForm() {
        $this->resetErrorBag();
        $this->dispatch('showCategoryForm');
    }

    public function createCategory() {
        $this->validate([
            'category_name' => 'required|unique:categories,name',
        ],[
            'category_name.required' => 'Category name is required',
            'category_name.unique' => 'Category name already exists',
        ]);

        $category = new Category();
        $category->name = $this->category_name;
        $category->parent = $this->parent;
        $save = $category->save();

        if ($save) {
            $this->closeCategoria();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Category added successfully']);
        }else{
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Ha ocurrido un error']);
        }
    }

    public function updateCategoryOrder($positions) {
        foreach ($positions as $position) {
            $index = $position[0];
            $newPosition = $position[1];
            Category::where('id', $index)->update(['order' => $newPosition]);
        }
        $this->dispatch('showToastr', ['type' =>'success', 'message' => 'Categorías ordenadas correctamente']);
    }

    public function editCategory($id) {
        $category = Category::find($id);
        $this->category_id = $category->id;
        $this->parent = $category->parent;
        $this->category_name = $category->name;
        $this->isUpdateCategory = true;
        $this->showCategoryForm();
    }

    public function updateCategory() {
        $category = Category::find($this->category_id);

        $this->validate([
            'category_name' =>'required|unique:categories,name,'.$this->category_id,
        ],[
            'category_name.required' => 'Category is required',
            'category_name.unique' => 'Category name already exists',
        ]);

        $category->name = $this->category_name;
        $category->parent = $this->parent;
        $category->slug = null;
        $save = $category->save();

        if ($save) {
            $this->closeCategoria();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Category updated successfully']);
        }else{
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Ha ocurrido un error']);
        }
    }

    public function deleteCategory($id) {
        $this->dispatch('deleteCategory', ['id' => $id]);
    }

    public function confirmDeleteCategory($id) {
        $category = Category::findOrFail($id);
        $category->delete();

        $this->dispatch('showToastr', ['type' =>'success', 'message' => 'Subcategoria eliminada correctamente']);
    }

    public function closePcategoria() {
        $this->dispatch('closePcategoria');
        $this->isUpdatePCategory = false;
        $this->pcategory_id = $this->pcategory_name = null;
    }

    public function closeCategoria() {
        $this->dispatch('closeCategoria');
        $this->isUpdateCategory = false;
        $this->category_id = $this->category_name = null;
        $this->parent = 0;
    }

    public function render()
    {
        return view('livewire.admin.categories',[
            'parentCategories' => ParentCategory::orderBy('order', 'asc')->paginate($this->pcategoryPage, ['*'], 'pcat_page'),
            'categories' => Category::orderBy('order', 'asc')->paginate($this->categoryPage, ['*'], 'cat_page'),
        ]);
    }
}
