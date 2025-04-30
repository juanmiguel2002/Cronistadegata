<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TopUserInfo extends Component
{

    protected $listeners = [
        'updateProfile' => '$refresh'
    ];
    public function render()
    {
        return view('livewire.admin.top-user-info',[
            'user' =>  User::findOrFail(Auth::id())
        ]);
    }
}
