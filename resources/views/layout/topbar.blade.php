<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->

                </div>

                <div class="d-flex align-items-center">

                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/image.png') }}"
                                    alt="Header Avatar">
                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name }} <i class="ri-arrow-down-s-line"></i></span>
                                    <!-- <span class="d-none d-xl-block ms-1 fs-sm user-name-sub-text">Admin User</span> -->
                                </span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                            <!-- <a class="dropdown-item" href="#"><span
                                    class="align-middle">Update Password</span></a>
                                    <div class="dropdown-divider"></div> -->
                                    <a class="dropdown-item" href="{{ '/zones-ip'}}"><span
                                    class="align-middle">Zone & ip</span></a>
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" id="filter" href="{{ '/filter' }}"><span
                                    class="align-middle">Filter</span></a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" id="update_password" href="#"><span
                                    class="align-middle">Update Password</span></a>
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="/logout"><span
                                    class="align-middle" data-key="t-logout">Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>