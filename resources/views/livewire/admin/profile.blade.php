<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault();document.getElementById('profilePicture').click();" class="edit-avatar"><i class="fa fa-pencil"></i></a>
                <img src="{{ $user->picture }}" alt="" class="avatar-photo" id="profilePicturePreview">
                <input type="file" name="profilePicture" id="profilePicture" class="d-none" style="opacity: 0">
            </div>
            <h5 class="text-center h5 mb-0">{{$user->name}}</h5>
            <p class="text-center text-muted font-14">
                {{ $user->email }}
            </p>
            <div class="profile-social">
                <h5 class="mb-20 h5 text-blue">Social Links</h5>
                <ul class="clearfix">
                    <li>
                        <a href="{{$facebook_url}}" target="_black" class="btn" data-bgcolor="#3b5998" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="{{ $instagram_url }}" target="_black" class="btn" data-bgcolor="#f46f30" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(255, 0, 128);"><i class="fa fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a wire:click="selectTab('personal_details')" class="nav-link {{ $tab == "personal_details" ? 'active' : ""}}" data-toggle="tab" href="#personal_details" role="tab">Datos Personales</a>
                        </li>
                        <li class="nav-item">
                            <a wire:click="selectTab('update_password')" class="nav-link {{ $tab == "update_password" ? 'active' : ""}}" data-toggle="tab" href="#update_password" role="tab">Actualizar contraseña</a>
                        </li>
                        <li class="nav-item">
                            <a wire:click="selectTab('social_links')"  class="nav-link {{ $tab == "social_links" ? 'active' : ""}}" data-toggle="tab" href="#social_links" role="tab">Social Links</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal_details Tab start -->
                        <div class="tab-pane fade {{ $tab == "personal_details" ? 'show active' : ""}}" id="personal_details" role="tabpanel">
                            <div class="pd-20">
                                <form wire:submit="updateProfile()">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input  wire:model='name' type="text" class="form-control" placeholder="Full Name">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input  wire:model='email' disabled type="email" class="form-control" placeholder="Email">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input  wire:model='username'  type="username" class="form-control" placeholder="Username">
                                                @error('username')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Bio</label>
                                                <textarea wire:model='bio' cols="4" rows="4" class="form-control" placeholder="Bio"></textarea>
                                                @error('bio')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- Update password --}}
                        <div class="tab-pane fade {{ $tab == "update_password" ? 'show active' : ""}}" id="update_password" role="tabpanel">
                            <div class="pd-20 profile-task-wrap">
                                <form wire:submit='updatePassword()'>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Current Password</label>
                                                <input wire:model='current_password' type="password" class="form-control" placeholder="Current Password">
                                                @error('current_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input wire:model='new_password' type="password" class="form-control" placeholder="New Password">
                                                @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Confirm new Password</label>
                                                <input wire:model='new_password_confirmation' type="password" class="form-control" placeholder="Confirm new Password">
                                                @error('new_password_confirmation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update password</button>
                                </form>
                            </div>
                        </div>
                        {{-- Social links --}}
                        <div class="tab-pane fade {{ $tab == "social_links" ? 'show active' : ""}}" id="social_links" role="tabpanel">
                            <div class="pd-20 profile-setting">
                                <form method="post" wire:submit='updateSocialLinks()'>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for=""><b>Facebook</b></label>
                                                <input wire:model='facebook_url' type="text" class="form-control" placeholder="Facebook URL">
                                                @error('facebook_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for=""><b>Instagram</b></label>
                                                <input wire:model='instagram_url' type="text" class="form-control" placeholder="Instagram URL">
                                                @error('instagram_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for=""><b>Youtube</b></label>
                                                <input wire:model='youtube_url' type="text" class="form-control" placeholder="Youtube URL">
                                                @error('youtube_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update </button>
                                </form>
                            </div>
                        </div>
                        <!-- Setting Tab End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
