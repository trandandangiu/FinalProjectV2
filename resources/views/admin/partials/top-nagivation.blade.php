        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                    <ul class=" navbar-right">
                        <li class="nav-item dropdown open" style="padding-left: 15px;">
                            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true"
                                id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                <img src="{{ $userAdmin->avatar_url }}" alt="">Admin
                            </a>
                            <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.account') }}"> Tai khoan</a>
                                <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                        class="fa fa-sign-out pull-right"></i> Đăng xuất</a>
                            </div>
                        </li>

                        <li role="presentation" class="nav-item dropdown open">
                            <a href="javascript:void(0);" class="dropdown-toggle info-number" id="navbarDropdown1"
                                data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">{{ $messages->count() }}</span>
                            </a>
                            <ul class="dropdown-menu list-unstyled msg_list" role="menu"
                       >
                                @for ($i = 0; $i < min(3, $messages->count()); $i++)
                                    <li class="nav-item">
                                        <a class="dropdown-item">
                                            <span class="image"><img src="{{ asset('assets/admin/images/user.png') }}"
                                                    alt="Profile Image" /></span>
                                            <span>
                                                <span>{{ $messages[$i]->title }}</span>
                                                <span
                                                    class="time">{{ $messages[$i]->created_at->diffForHumans() }}</span>
                                            </span>
                                            <span class="message custom-message-top">
                                                {{ Str::limit($messages[$i]->message, 10) }}
                                            </span>
                                        </a>
                                    </li>
                                @endfor
                                <li class="nav-item">
                                    <div class="text-center">
                                        <a class="dropdown-item" href="{{ route('admin.contact.index') }}">
                                            <strong>Xem tất cả liên hệ</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>




                        <li class="nav-item dropdown open" style="margin-right: 15px;">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                <span class="badge bg-green">{{ $notifications->count()}}</span>
                            </a>
                            <ul class="dropdown-menu list-unstyled msg_list" role="menu"
                               >
                                         @for ($i = 0; $i < min(3, $notifications->count()); $i++)
                                    <li class="nav-item">
                                        <a class="dropdown-item">
                                            <span class="image"><img src="{{ asset('assets/admin/images/bell.png') }}"
                                                    alt="Profile Image" /></span>
                                            <span>
                                                <span>{{ $notifications[$i]->title }}</span>
                                                <span
                                                    class="time">{{ $notifications[$i]->created_at->diffForHumans() }}</span>
                                            </span>
                                            <span class="message custom-message-top">
                                                {{ Str::limit($notifications[$i]->message, 10) }}
                                            </span>
                                        </a>
                                    </li>
                                @endfor
                                <li class="nav-item">
                                    <div class="text-center">
                                        <a class="dropdown-item" href="{{ route('admin.notifications.index') }}">
                                            <strong>Xem tất cả thông báo</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->
