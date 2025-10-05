<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>City of Taguig - Scoring system</title>

    {{-- Fonts + Metronic core CSS --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="stylesheet" href="{{ asset('assets/css/maroon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.css') }}" type="text/css">

    <style>
    [x-cloak] {
        display: none !important;
    }

    .auth-container {
        min-height: 100vh;
    }
    </style>

    <script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            themeMode = localStorage.getItem("data-bs-theme") ?? defaultThemeMode;
        }
        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }
        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
    </script>
</head>

<body x-data="{ pageLoading: true }"
    x-init="pageLoading = false; window.addEventListener('beforeunload', () => pageLoading = true)" id="kt_body"
    class="auth-bg bgi-size-cover bgi-attachment-fixed bgi-position-center">

    <style>
    body {
        background-image: url('{{ asset('images/bg.webp') }}');
    }

    [data-bs-theme="dark"] body {
        background-image: url('{{ asset('images/bg.webp') }}');
    }
    </style>

    <div class="d-flex flex-column flex-root auth-container">
        @yield('content')
    </div>

    <script>
    var hostUrl = "{{ asset('assets') }}/";
    </script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('modals')
    @stack('scripts')
    @include('components.loading')
</body>

</html>