<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', $site['site_name'] ?? 'Ippeo Essential Products')</title>
  <meta name="description" content="@yield('meta', 'Nature-inspired skincare by Ippeo Essential Products')" />
  <link rel="icon" href="{{ media_url($site['logo'] ?? 'images/logo.png') }}" type="image/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Great+Vibes&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/style.css') . '?v=6' }}" />
  @stack('head')
</head>
<body>
  @include('partials.header')
  <main>
    @if(session('success'))
      <div class="flash success container">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="flash error container">{{ session('error') }}</div>
    @endif
    @yield('content')
  </main>
  @include('partials.footer')
  <div class="toast" id="toast" role="status" aria-live="polite"></div>
  <script src="{{ asset('js/store.js') . '?v=7' }}"></script>
  @stack('scripts')
</body>
</html>
