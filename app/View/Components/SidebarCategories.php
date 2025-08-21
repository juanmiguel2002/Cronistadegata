<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\ParentCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarCategories extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $pcategories = ParentCategory::query()
            ->with([
                'categoria' => function ($q) {
                    $q->withCount('posts')->orderBy('name');
                },
            ])
            ->orderBy('name')
            ->get();

        // Categorías sin padre
        $categories = Category::whereHas('posts')->where('parent', 0)->orderBy('name', 'asc')->get();

        return view('components.sidebar-categories', compact('pcategories', 'categories'));
    }
}
