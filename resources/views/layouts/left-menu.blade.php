<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="/dashboard/assets/images/logo.png" style="height: 35px; width: auto" />
                <span class="badge bg-light-success rounded-pill ms-2 theme-version"></span>
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ auth()->user()['avatar'] }}" alt="user-image" class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ auth()->user()['name'] }}</h6>
                            <small>{{ auth()->user()['company']['name'] }}</small>
                            @if(!auth()->user()->hasRole([\App\Models\User::ROLE_USER_ADMIN,\App\Models\User::ROLE_USER_EMPLOYEE]))
                                <br>{!! auth()->user()['role_badge'] !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                @if(!auth()->user()->hasRole(\App\Models\User::ROLE_USER_EMPLOYEE))
                <li class="pc-item pc-caption">
                    <label>General</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item">
                    <a href="/home" class="pc-link">
                        <span class="pc-micon">
                          <svg class="pc-icon">
                            <use xlink:href="#custom-security-safe"></use>
                          </svg>
                        </span>
                        <span class="pc-mtext">Get Started</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="/dashboards" class="pc-link">
                        <span class="pc-micon">
                          <svg class="pc-icon">
                            <use xlink:href="#custom-status-up"></use>
                          </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="pc-item">
                    <a href="/videos" class="pc-link">
                        <span class="pc-micon">
                          <svg class="pc-icon">
                            <use xlink:href="#custom-video-play"></use>
                          </svg>
                        </span>
                        <span class="pc-mtext">Videos</span>
                    </a>
                </li>
                @endif
{{--                <li class="pc-item">--}}
{{--                    <a href="/reviews" class="pc-link">--}}
{{--                        <span class="pc-micon">--}}
{{--                          <svg class="pc-icon">--}}
{{--                            <use xlink:href="#custom-message-2"></use>--}}
{{--                          </svg>--}}
{{--                        </span>--}}
{{--                        <span class="pc-mtext">Reviews</span>--}}
{{--                    </a>--}}
{{--                </li>--}}

                @if(auth()->user()->can('manage user') || auth()->user()->can('manage employee') || auth()->user()->can('manage department') )
                <li class="pc-item pc-caption">
                    <label>Management</label>
                    <i class="ti ti-chart-arcs"></i>
                </li>
                @endif
                @can('manage user')
                <li class="pc-item">
                    <a href="/users" class="pc-link">
                        <span class="pc-micon">
                          <svg class="pc-icon">
                            <use xlink:href="#custom-user"></use>
                          </svg>
                        </span>
                        <span class="pc-mtext">Users</span>
                    </a>
                </li>
                @endcan
                @can('manage employee')
                <li class="pc-item">
                    <a href="/company/employees" class="pc-link">
                        <span class="pc-micon">
                          <svg class="pc-icon">
                            <use xlink:href="#custom-user-square"></use>
                          </svg>
                        </span>
                        <span class="pc-mtext">Employees</span>
                    </a>
                </li>
                @endcan
                @can('manage department')
                <li class="pc-item">
                    <a href="/company/departments" class="pc-link">
                        <span class="pc-micon">
                          <svg class="pc-icon">
                            <use xlink:href="#custom-add-item"></use>
                          </svg>
                        </span>
                        <span class="pc-mtext">Departments</span>
                    </a>
                </li>
                @endcan
                <li class="pc-item pc-caption">
                    <label>Training</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                @can('create course')
                    <li class="pc-item">
                        <a href="/courses" class="pc-link">
                    <span class="pc-micon">
                      <svg class="pc-icon">
                        <use xlink:href="#custom-notification-status"></use>
                      </svg>
                    </span><span class="pc-mtext">Manage Course</span></a>
                    </li>
                @endcan
                @can('do training')
                <li class="pc-item">
                    <a href="/training" class="pc-link">
                    <span class="pc-micon">
                      <svg class="pc-icon">
                        <use xlink:href="#custom-flag"></use>
                      </svg>
                    </span><span class="pc-mtext">Training</span></a>
                </li>
                @endcan
{{--                <li class="pc-item">--}}
{{--                    <a href="/monthly-videos" class="pc-link">--}}
{{--                    <span class="pc-micon">--}}
{{--                      <svg class="pc-icon">--}}
{{--                        <use xlink:href="#custom-calendar-1"></use>--}}
{{--                      </svg>--}}
{{--                    </span><span class="pc-mtext">Monthly Video</span></a>--}}
{{--                </li>--}}
            </ul>
        </div>
    </div>
</nav>
