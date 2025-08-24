<?php

namespace App\View\Components;

use App\Models\User;
use App\Models\UserSocialLink;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     */
    public $links;
    public $user;
    public function __construct()
    {
        //
        $this->links = UserSocialLink::where('user_id', 2)->get();
        $this->user = User::where('id', 2)->first();
        //dd($this->links);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer');
    }
}
