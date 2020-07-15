<nav class="navbar fixed-top" >
    <div class="d-flex align-items-center navbar-left"  style="margin-left: 5%">
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
                <button class="header-icon btn btn-empty" type="button" id="keranjang-btn"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="iconsmind-Shopping-Cart"></i>
                    <span id="keranjang-count" class="count badge-count"></span>
                </button>
            </div>
            <div class="position-relative d-inline-block">
                <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="simple-icon-bell"></i>
                    <span class="count"></span>
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
                <p style="cursor: pointer" id="profile-btn" class="dropdown-item" href="#">Profile</p>
                @if($user['role'] == 'pedagang')
                <p style="cursor: pointer" id="toko-btn" class="dropdown-item" href="#">Toko saya</p>
                @endif
                <p style="cursor: pointer" id="pesan-btn" class="dropdown-item" href="#">Pesan</p>
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
