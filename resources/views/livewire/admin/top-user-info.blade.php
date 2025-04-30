<div>
    <div class="user-info-dropdown">
        <div class="dropdown">
            <a
                class="dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
            >
                <span class="user-icon">
                    <img src="{{$user->picture}}" alt="" />
                </span>
                <span class="user-name">{{$user->name}}</span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
            >
                <a class="dropdown-item" href="{{ route('admin.profile') }}"
                    ><i class="dw dw-user1"></i> Perfil</a
                >
                <a class="dropdown-item" href="{{ route('admin.settings') }}"
                    ><i class="dw dw-settings2"></i> Ajustes</a
                >
                {{-- <a class="dropdown-item" href="faq.html"
                    ><i class="dw dw-help"></i> Help</a
                > --}}
                <a class="dropdown-item" href="{{ route('admin.logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <i class="dw dw-logout"></i> Eixir</a>
                <form action="{{ route('admin.logout') }}" method="post" id="logout-form">@csrf</form>
            </div>
        </div>
    </div>
</div>
