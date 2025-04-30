<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\UserSocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Profile extends Component
{
    public $tab;
    public $tabname = 'personal_details';
    protected $queryString = ['tab'=> ['keep' => true]];
    public $name, $email, $username, $bio;

    public $current_password, $new_password, $new_password_confirmation;

    public $youtube_url, $facebook_url, $instagram_url;

    public function selectTab($tab){
        $this->tab = $tab;
    }
    public function mount(){
        $this->tab = Request('tab') ? Request('tab') : $this->tabname;

        $user = User::with('social')->findOrFail(Auth::id());
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;

        // Social Links
        if ($user->social) {
            $this->youtube_url = $user->social->youtube_url;
            $this->facebook_url = $user->social->facebook_url;
            $this->instagram_url = $user->social->instagram_url;
        }
    }

    public function updateProfile(){
        $user = User::find(Auth::id());
        $this->validate([
           'name' => 'required',
           'username' => 'required|unique:users,username,'.$user->id,
       ]);

        $user->name = $this->name;
        $user->username = $this->username;
        $user->bio = $this->bio;
        $save = $user->save();

        sleep(0.5);

        if ($save) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Profile updated successfully']);
            $this->dispatch('updateProfile')->to(TopUserInfo::class);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Profile update failed']);
        }

    }


    public function updatePassword(){

        $user = User::findOrFail(Auth::id());

        $this->validate([
            'current_password' => [
                'required',
                'min:5',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                },
            ],
            'new_password' => 'required|min:5|confirmed'
        ]);

        $user->password = Hash::make($this->new_password);
        $save = $user->save();

        if ($save) {
            Auth::logout();
            Session::flash('info', 'Password updated successfully changed');
            return redirect()->route('admin.login');
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Password update failed']);
        }

    }

    public function updateSocialLinks(){
        $this->validate([
            'youtube_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
        ]);

        $user = User::findOrFail(Auth::id());

        $data = array(
            'youtube_url' => $this->youtube_url,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
        );

        if ($user->social) {
            $query = $user->social()->update($data);
        } else {
            $data['user_id'] = $user->id;
            $query = UserSocialLink::insert($data);
        }
        if ($query) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Social links updated successfully']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Social links error']);
        }
    }

    public function render()
    {

        return view('livewire.admin.profile', [
            'user' => User::findOrFail(Auth::id())
        ]);
    }
}
