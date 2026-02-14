    <nav>
        <div class="app-logo">
            <a class="logo d-inline-block" href="index.html">
                <img alt="#" src="{{ asset('assets') }}/images/logo/1.png">
            </a>

            <span class="bg-light-primary toggle-semi-nav">
                <i class="ti ti-chevrons-right f-s-20"></i>
            </span>
        </div>
        <div class="app-nav" id="app-simple-bar">
            <ul class="main-nav p-0 mt-2">
                <li class="menu-title">
                    <span>Menu</span>
                </li>
                <li class="no-sub">
                    <a class="" href="{{ route('home') }}">
                        <i class="iconoir-home-alt"></i> Home
                    </a>
                </li>

                <li class="no-sub">
                    <a class="" href="{{ url('file-manager') }}">
                        <i class="iconoir-apple-shortcuts"></i> File Manager
                    </a>
                </li>


                {{-- 
                <li>
                    <a aria-expanded="true" class="show" data-bs-toggle="collapse" href="#file-manager">
                        <i class="iconoir-apple-shortcuts"></i>
                        File Manager
                    </a>
                    <ul class="collapse show" id="file-manager" aria-expanded="true">

                        <li><a href="to_do.html">To-Do</a></li>
                        <li><a href="team.html">Team</a></li>

                    </ul>
                </li> --}}

            </ul>
        </div>

        <div class="menu-navs">
            <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
            <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
        </div>

    </nav>
