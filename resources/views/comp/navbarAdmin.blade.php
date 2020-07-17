<nav class="navbar fixed-top" >
    <div class="d-flex align-items-center navbar-left">
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1" />
                <rect x="0.48" y="7.5" width="7" height="1" />
                <rect x="0.48" y="15.5" width="7" height="1" />
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1" />
                <rect x="1.56" y="7.5" width="16" height="1" />
                <rect x="1.56" y="15.5" width="16" height="1" />
            </svg>
        </a>

        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1" />
                <rect x="0.5" y="7.5" width="25" height="1" />
                <rect x="0.5" y="15.5" width="25" height="1" />
            </svg>
        </a>
        <div class="search" data-search-path="Layouts.Search.html?q=">
            <input placeholder="Search...">
            <span class="search-icon">
                <i class="simple-icon-magnifier"></i>
            </span>
        </div>
    </div>

    <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="position-relative d-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-bell"></i>
                    <span class="count" id="notif-count"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-3 scroll position-absolute" id="notificationDropdown">
                </div>
            </div>
        </div>
        @if (!empty($user))
        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="name">{{$user['nama_lengkap'] }}</span>
                <span>
                    <img alt="Profile Picture" src={!! $storage_path ."/images/user/" . $user['image'] !!} />
                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-right mt-3">
                <p style="cursor: pointer" id="profile-btn" class="dropdown-item">Profile</p>
                <p style="cursor: pointer" id="riwayat-btn" class="dropdown-item" href="/riwayat">Riwayat belanja</p>
                @if($user['role'] == 'pedagang')
                <p style="cursor: pointer" id="toko-btn" class="dropdown-item">Toko saya</p>
                @endif
                <p style="cursor: pointer" id="pesan-btn" class="dropdown-item">Pesan</p>
                <p style="cursor: pointer" class="dropdown-item" id="logout">Sign out</p>
            </div>
        </div>
        @else
        <div class="user d-inline-block">
            <button class="header-icon btn btn-empty p-0" type="button" id="masuk">
                Masuk
            </button>
        </div>
        @endif
    </div>
</nav>
