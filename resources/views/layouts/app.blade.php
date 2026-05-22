<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hoa Cloud - Advanced File Sharing')</title>

    <!-- Pre-compiled Tailwind & Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Alpine.js (Local) -->
    <script defer src="{{ asset('js/alpine.js') }}"></script>
    
    @livewireStyles
    <style>
        body {
            background-color: #0a0a0a;
            color: #f5f5f5;
            overflow-x: hidden;
        }
        
        /* Gradient Mesh Background */
        .mesh-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 20% 30%, rgba(30, 58, 138, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(88, 28, 135, 0.15) 0%, transparent 40%);
        }

        .mesh-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: drift 20s infinite alternate;
        }

        @keyframes drift {
            from { transform: translate(0, 0); }
            to { transform: translate(100px, 50px); }
        }
    </style>
</head>
<body class="antialiased">
    <div class="mesh-bg">
        <div class="mesh-circle bg-blue-900 w-[500px] h-[500px] top-[-100px] left-[-100px]"></div>
        <div class="mesh-circle bg-purple-900 w-[400px] h-[400px] bottom-[-100px] right-[-100px]" style="animation-delay: -5s;"></div>
    </div>

    {{ $slot ?? '' }}
    @yield('content')

    @include('components.media.AudioMiniPlayerComponent')

    @livewireScripts
</body>
</html>
