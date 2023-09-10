<header>
    <div class="navigation red_linear_bg">
        <div class="panel__nav">
            <div class="top-menu">
                <!-- <div class="logo__sec">
                    <a href="{{ route('dashboard.dashboard') }}" class="logo">
                        @if (auth()->user()->branch)
                            @if (auth()->user()->branch->logo != 'default.png')
                                <img style="height: 40px; width:110px;"
                                src="{{ asset('/uploads/branch_logo/' . auth()->user()->branch->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;letter-spacing:1px;padding-top:15px;display:inline-block;">{{ auth()->user()->branch->name }}</span>
                            @endif
                        @else
                            @if (json_decode($generalSettings->business, true)['business_logo'] != null)
                                <img style="height: 40px; width:110px;"
                                src="{{ asset('/uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}"
                                alt="logo" class="logo__img">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;letter-spacing:1px;padding-top:15px;display:inline-block;">{{
                                json_decode($generalSettings->business, true)['shop_name'] }}</span>
                            @endif
                        @endif
                    </a>
                </div> -->
                <div class="notify-menu">
                    <div class="head__content__sec">
                        <ul class="head__cn">
                            <!-- <li class="top-icon d-none d-md-block" id="hard_reload"><a href="#" title="@lang('Reload')"><b><span class="fas fa-redo-alt"></span></b></a></li> -->
                            @if ($addons->e_commerce == 1)
                                <li class="top-icon d-none d-md-block"><a href="#" target="_blank"><b><span class="fas fa-redo-alt"></span></b></a></li>
                            @endif

                            {{-- @if (auth()->user()->permission->others['communication'] == '1')
                                <li class="top-icon d-none d-md-block" id="get_mail" title="@lang('Communicate')"><a href="#"><b><i
                                                class="fas fa-th-large"></i></b></a>
                                    <ul class="lists">
                                        <li><a href="#"><i class="fas fa-bell"></i>
                                            <span class="title">@lang('Notice Board')</span></a> </li>
                                        <li><a href="#"><i class="fas fa-envelope-open"></i><span class="title">Send
                                                    Email</span></a></li>
                                        <li><a href="#"><i class="fas fa-comment-alt"></i><span class="title">Send
                                                    SMS</span></a></li>
                                        <li><a href="#"><i class="fas fa-download"></i><span class="title">Download
                                                    Center</span></a></li>
                                    </ul>
                                </li>
                            @endif --}}

                            @if (auth()->user()->permission->others['today_summery'] == '1')
                                <li class="top-icon"><a href="#" id="today_summery" title="@lang('Day')"><i class="fa fa-calendar" aria-hidden="true"></i>
</a></li>
                            @endif

                            <li class="top-icon"><a href="" title="@lang('Notifications')"><i class="far fa-bell"></i></a></li>
                            @if (json_decode($generalSettings->modules, true)['pos'] == '1')
                                @if (auth()->user()->permission->sale['pos_add'] == '1')
                                    <li class="top-icon"><a href="{{ route('sales.pos.create') }}" title="@lang('Invoice')"><i class="fa fa-book" aria-hidden="true"></i>
</a></li>
                                @endif
                            @endif

                            <!-- <li class="top-icon">
                                <a href="" class="pos-btn" data-bs-toggle="modal" data-bs-target="#calculatorModal" title="@lang('Calculator')">
                                    <span class="fas fa-calculator"></span>
                                </a>
                                <div class="modal" id="calculatorModal" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modail-body" id="calculator">
                                                <div class="calculator-bg" dir="ltr">
                                                    <div class="calculator-bg__main">
                                                        <div class="calculator-bg__main__screen">
                                                            <div class="calculator-bg__main__screen__first"></div>
                                                            <div class="calculator-bg__main__screen__second">0</div>
                                                        </div>
                                                        <button class="calculator-bg__main__ac">AC</button>
                                                        <button class="calculator-bg__main__del">@lang('DEL')</button>
                                                        <button class="calculator-bg__main__operator">/</button>
                                                        <button class="calculator-bg__main__num">7</button>
                                                        <button class="calculator-bg__main__num">8</button>
                                                        <button class="calculator-bg__main__num">9</button>
                                                        <button class="calculator-bg__main__operator">x</button>
                                                        <button class="calculator-bg__main__num">4</button>
                                                        <button class="calculator-bg__main__num">5</button>
                                                        <button class="calculator-bg__main__num">6</button>
                                                        <button class="calculator-bg__main__operator">+</button>
                                                        <button class="calculator-bg__main__num">1</button>
                                                        <button class="calculator-bg__main__num">2</button>
                                                        <button class="calculator-bg__main__num">3</button>
                                                        <button class="calculator-bg__main__operator">-</button>
                                                        <button class="calculator-bg__main__num decimal">.</button>
                                                        <button class="calculator-bg__main__num">0</button>
                                                        <button class="calculator-bg__main__result">=</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li> -->
                            <li class="dropdown dp__top">
                                <a href="" class="top-icon" id="dropdownMenuButton1" data-bs-toggle="dropdown" title="@lang('Languages')">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                </a>

                                <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <a style="display:inline;" class="dropdown-item @if(app()->isLocale('en')) text-primary @endif" href="{{ route('change.lang', 'en') }}">
                                            English
                                        </a>
                                    </li>
                                    <li>
                                        <a style="display:inline;" class="dropdown-item @if(app()->isLocale('ar')) text-primary @endif" href="{{ route('change.lang', 'ar') }}">
                                            Arabic
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="top-icon d-none d-md-block"><a href="https://help.genuinepos.com/"
                                    target="_blank" title="@lang('Help')" ><b><span class="far fa-question-circle"></span></b></a></li>
                            <li class="dropdown dp__top top-icon">
                                <a href="" class="" id="dropdownMenuButton1" data-bs-toggle="dropdown" title="@lang('User')">
                                    <span class="fas fa-user"></span>
                                </a>

                                <ul class="dropdown-menu dropdown__main__menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <span class="user_name text-primary">
                                            {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' .
                                            auth()->user()->last_name }}
                                            @if (auth()->user()->role_type == 1)
                                                (Super Admin)
                                            @elseif(auth()->user()->role_type == 2)
                                                (Admin)
                                            @else
                                                {{ auth()->user()->role->name }}
                                            @endif
                                        </span>
                                    </li>

                                    <li>
                                        <i class="fas fa-eye text-primary"></i><a class="dropdown-item d-block"
                                            href="{{ route('users.profile.view', auth()->user()->id) }}">
                                        @lang('View Profile')</a>
                                    </li>

                                    <li>
                                        <i class="fas fa-edit text-primary"></i></span><a class="dropdown-item d-block"
                                            href="{{ route('users.profile.index') }}">@lang('Edit Profile')</a>
                                    </li>
                                </ul>
                            </li>
                            </li>
                            <li class="top-icon">
                                <a href="" id="logout_option"><span class="fas fa-power-off" title="@lang('Logout')"></span></a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div id="left_bar_toggle"><span class="fas fa-bars"></span></div>
            </div>
        </div>
    </div>
</header>
{{-- <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script> --}}
