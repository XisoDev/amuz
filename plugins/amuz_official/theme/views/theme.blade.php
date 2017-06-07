{{-- meta(viewport) --}}
{{ app('xe.frontend')->meta()->name('viewport')->content(
    'width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no'
)->load() }}

{{-- stylesheet --}}
{{ app('xe.frontend')->css([
    'assets/core/xe-ui-component/xe-ui-component.css',
    $theme::asset('css/layout.css'),
    asset('//cdn.jsdelivr.net/xeicon/2.0.0/xeicon.min.css'),
])->load() }}

{{-- script --}}
{{ app('xe.frontend')->js([
$theme::asset('js/layout.js'),
$theme::asset('js/smoothscroll.js'),
])->load() }}


@if($config->get('layout') == "particle")
{{ app('xe.frontend')->js($theme::asset('js/particles.min.js'))->load() }}
@endif

{{-- inline style --}}
{{ app('xe.frontend')->html('theme.style')->content("
<style>
    html, body {
        height:100%;
    }
</style>
")->load() }}

{{--<header>
    <div class="xe-container">
        <button class="btn-toggle" type="button">
            <span class="blind">Toggle nav-listigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar v2"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="plugin-area">
            <ul>
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('settings') }}" class="plugin"><i class="xi-cog"></i></a></li>
                @endif
                <li>
                    <a href="#" class="plugin auth-toggle"><i class="xi-user"></i></a>
                    <ul class="toggle-menu">
                        @if(Auth::check())
                            <li><a href="{{ route('user.profile', ['user' => auth()->id()]) }}">{{ xe_trans('xe::myProfile') }}</a></li>
                            <li><a href="{{ route('user.settings') }}">{{ xe_trans('xe::mySettings') }}</a></li>
                            <li><a href="{{ route('logout') }}">{{ xe_trans('xe::logout') }}</a></li>
                        @else
                            <li><a href="{{ route('login') }}">{{ xe_trans('xe::login') }}</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
        <nav>
            @include($theme::view('gnb'))
        </nav>
    </div>
</header>--}}
<header id="sidemenu" class="scrolllink" style="@if($config->get('headerBgColor')) background-color: {{ $config->get('headerBgColor') }} @endif">
    <ul>
        @foreach(menu_list($config->get('mainMenu')) as $menu)
        <li>
            @if(Request::path() == '/')
                <a class="menuLink" href="{{ url($menu['url']) }}" target="{{ $menu['target'] }}">{{ $menu['link'] }}</a>
            @else
                @if(str_contains( $menu['url'], '#'))
                <a class="menuLink" href="/{{ url($menu['url']) }}" target="{{ $menu['target'] }}">{{ $menu['link'] }}</a>
                @else
                <a class="menuLink" href="{{ url($menu['url']) }}" target="{{ $menu['target'] }}">{{ $menu['link'] }}</a>
                @endif
            @endif
        </li>
        @endforeach
    </ul>
</header>


<style>
@if($config->get('bgTxtColor'))
    #intro h1, #intro p {
        color: {{ $config->get('bgTxtColor') }}
    }
@endif
@if($config->get('bgColor'))
    body {
        background-color: {{ $config->get('bgColor') }};
    }
@endif
</style>



@if($config->get('layout') != 'default')
<section id="intro" @if($config->get('layout') != "particle") @endif style="background-color: @if($config->get('bgColor')) {{ $config->get('bgColor') }} @else #000 @endif; @if($config->get('bgImage.path')) background-image: url({{ $config->get('bgImage.path') }}); @endif">

    @if($config->get('layout') == "particle")
    <div id="particles-js" style="height:100%; width:100%; position:fixed; z-index:0;">
    </div>
    @endif

    <div style="position:fixed; z-index:-1; top: 50%; left: 50%; transform: translate3d(-50%, -50%, 0); text-align: center;">
    @if($config->get('logoImage.path'))
        <img src="{{ $config->get('logoImage.path') }}" alt="{{ xe_trans($config->get('logoText', '')) }}" width="200"/>
    @endif
    @if($config->get('bgTitle'))
        <h1>{{ $config->get('bgTitle') }}</h1>
    @endif
    @if($config->get('bgContent'))
        <p class="subtitle">{!! nl2br($config->get('bgContent')) !!}</p>
    @endif
    </div>

</section>
@endif


<div id="content" @if($config->get('headerPosition') != 'top-fixed') class="hasLeft" @endif>
{!! $content !!}

    <div class="footer">
        <div class="xe-container">
            <div class="xe-row">
                <div class="xe-col-sm-3">
                    <div class="brand-area">
                        <a href="{{ url('/') }}" class="link-brand">
                            @if($config->get('footerLogoImage.path'))
                                <img src="{{ $config->get('footerLogoImage.path') }}" alt="{{ xe_trans($config->get('footerLogoText', 'Amuz')) }}"/>
                            @else
                                {!! xe_trans($config->get('footerLogoText', 'Alice')) !!}
                            @endif
                        </a>
                    </div>
                    <p class="footer-text">
                        {!! xe_trans($config->get('footerContents', '')) !!}
                    </p>
                </div>

                @if($config->get('useFooterMenu', 'N') === 'Y')
                    @include($theme::view('fnb'))
                @endif

                <div class="xe-col-sm-2 xe-col-xs-offset-1">
                    <div class="link-area float-right">
                        @if($links = $config->get('footerLinkUrl'))
                            @foreach ($links as $index => $url)
                                <a href="{{$url}}"><i class="{{ $config->get("footerLinkIcon.$index") }}"></i></a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copy">
            <div class="xe-container">
                <div class="xe-row">
                    <div class="xe-col-sm-6">
                        <p>{{$config->get('copyright', '')}}</p>
                    </div>
                    <div class="xe-col-sm-6">

                        @if($config->get('useMultiLang', 'Y') === 'Y')
                            <div class="xe-form-group">
                                <div class="xe-dropdown ">
                                    <button class="xe-btn" type="button" data-toggle="xe-dropdown"><i class="{{XeLang::getLocale()}} xe-flag"></i> {{ XeLang::getLocaleText(XeLang::getLocale()) }}</button>
                                    <ul class="xe-dropdown-menu">
                                        @foreach ( XeLang::getLocales() as $locale )
                                            <li><a href="{{ locale_url($locale) }}"><i class="{{ $locale }} xe-flag" data-locale="{{ $locale }}"></i> {{ XeLang::getLocaleText($locale) }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <p class="float-right">Made by <a href="http://xpressengine.io">XE</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    @if($config->get('layout') == "particle")
    particlesJS('particles-js',

            {
                "particles": {
                    "number": {
                        "value": 50,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#aaaaaa"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        },
                        "image": {
                            "src": "img/github.svg",
                            "width": 100,
                            "height": 100
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 2,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#cccccc",
                        "opacity": 0.3,
                        "width": 0.5
                    },
                    "move": {
                        "enable": true,
                        "speed": 0.5,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 400,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true,
                "config_demo": {
                    "hide_card": false,
                    "background_color": "#444444",
                    "background_image": "",
                    "background_position": "50% 50%",
                    "background_repeat": "no-repeat",
                    "background_size": "cover"
                }
            }
    );
    @endif
});
</script>