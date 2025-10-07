<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
    <div class="aside-logo flex-column-auto pt-10 pt-lg-20" id="kt_aside_logo">
        <a href="#">
            <img alt="Logo" src="{{ asset('images/city_logo.webp') }}" class="h-100px" />
        </a>
    </div>
    <div class="aside-menu flex-column-fluid pt-0 pb-7 py-lg-10" id="kt_aside_menu">
        <div id="kt_aside_menu_wrapper" class="w-100 hover-scroll-y scroll-ms d-flex" data-kt-scroll="true"
            data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
            data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="0">
            <div id="kt_aside_menu"
                class="menu menu-column menu-title-gray-600 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-icon-gray-400 menu-arrow-gray-400 fw-semibold fs-6 my-auto"
                data-kt-menu="true">
                <div class="menu-item py-2">
                    <a href="{{ route(Auth::user()->getRoleNames()->first() . '.dashboard.index') }}"
                        class="menu-link menu-center {{ request()->routeIs(Auth::user()->getRoleNames()->first() . '.dashboard.index') ? 'active' : '' }}">
                        <span class="menu-icon me-0">
                            <i class="ki-duotone ki-home-2 fs-2x">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </a>
                </div>
                @role('superadmin')


                @php
                $role = Auth::user()->getRoleNames()->first();
                $person = [
                'participant.index',
                ];

                $open = collect($person)
                ->map(fn($r) => "$role.$r")
                ->contains(fn($route) => request()->routeIs($route));
                @endphp

                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item py-2 {{ $open ? 'show here' : '' }}">

                    <span class="menu-link menu-center {{ $open ? 'active' : '' }}">
                        <span class="menu-icon me-0">
                            <i class="ki-duotone ki-user fs-2x">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </span>

                    <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">Users</span>
                            </div>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs($role . '.participant.index') ? 'active' : '' }}"
                                href="{{ route($role . '.participant.index') }}">
                                <span class="menu-bullet">
                                    <i class="ki-duotone ki-check-square fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Participants</span>
                            </a>
                            <a class="menu-link {{ request()->routeIs($role . '.account.index') ? 'active' : '' }}"
                                href="{{ route($role . '.account.index') }}">
                                <span class="menu-bullet">
                                    <i class="ki-duotone ki-check-square fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Accounts</span>
                            </a>
                        </div>

                    </div>
                </div>
                @php
                $role = Auth::user()->getRoleNames()->first();
                $children = [
                'criteria.index',
                ];

                $open = collect($children)
                ->map(fn($r) => "$role.$r")
                ->contains(fn($route) => request()->routeIs($route));
                @endphp

                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start"
                    class="menu-item py-2 {{ $open ? 'show here' : '' }}">

                    <span class="menu-link menu-center {{ $open ? 'active' : '' }}">
                        <span class="menu-icon me-0">
                            <i class="ki-duotone ki-verify fs-2x">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                    </span>

                    <div class="menu-sub menu-sub-dropdown px-2 py-4 w-250px mh-75 overflow-auto">
                        <div class="menu-item">
                            <div class="menu-content">
                                <span class="menu-section fs-5 fw-bolder ps-1 py-1">CMS</span>
                            </div>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs($role . '.criteria.index') ? 'active' : '' }}"
                                href="{{ route($role . '.criteria.index') }}">
                                <span class="menu-bullet">
                                    <i class="ki-duotone ki-check-square fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Criteria</span>
                            </a>
                        </div>

                    </div>
                </div>
                @endrole
            </div>
        </div>
    </div>
    <div class="aside-footer flex-column-auto pb-5 pb-lg-10" id="kt_aside_footer">
        <div class="d-flex flex-center w-100 scroll-px" data-bs-toggle="tooltip" data-bs-placement="right"
            data-bs-dismiss="click" title="Quick actions">
            <button type="button" class="btn btn-custom" data-kt-menu-trigger="click" data-kt-menu-overflow="true"
                data-kt-menu-placement="top-start">
                <i class="ki-duotone ki-entrance-left fs-2x">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </button>
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                data-kt-menu="true">
                <div class="menu-item px-3">
                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
                </div>
                <div class="separator mb-3 opacity-75"></div>
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3">Update Account</a>
                </div>
                <div class="separator mt-3 opacity-75"></div>
                <div class="menu-item px-3">
                    <div class="menu-content px-3 py-3">
                        <a class="btn btn-primary btn-sm px-4" href="#">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>