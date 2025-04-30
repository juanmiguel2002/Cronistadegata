<?php

namespace App\View\Components;

use App\Models\Carousel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderCarrousel extends Component
{
    /**
     * Create a new component instance.
     */
    public $images = [];
    
    public function __construct()
    {
        //
        $this->images = Carousel::orderBy('orden')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header-carrousel');
    }
}
