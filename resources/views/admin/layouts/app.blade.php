<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin') | Ippeo Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root{--g:#226b2c;--gd:#1a5422;--bg:#f3f5f4;--card:#fff;--bd:#e5e8eb;--tx:#1a1a1a;--mu:#5c6670}
    *{box-sizing:border-box} body{margin:0;font-family:Outfit,sans-serif;background:var(--bg);color:var(--tx)}
    a{color:inherit;text-decoration:none}
    .shell{display:grid;grid-template-columns:240px 1fr;min-height:100vh}
    .side{background:#12351a;color:#fff;padding:1.25rem 1rem;position:sticky;top:0;height:100vh;overflow:auto}
    .side .logo{font-weight:700;font-size:1.2rem;margin-bottom:1.25rem;display:block;color:#fff}
    .side a{display:block;padding:.65rem .8rem;border-radius:8px;color:rgba(255,255,255,.85);margin-bottom:.2rem}
    .side a:hover,.side a.active{background:rgba(255,255,255,.12);color:#fff}
    .main{padding:1.25rem 1.5rem 2rem}
    .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;gap:1rem;flex-wrap:wrap}
    .card{background:var(--card);border:1px solid var(--bd);border-radius:12px;padding:1.1rem;box-shadow:0 6px 18px rgba(0,0,0,.04)}
    .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.25rem}
    .stat h3{margin:0;font-size:1.6rem;color:var(--g)} .stat p{margin:.25rem 0 0;color:var(--mu);font-size:.9rem}
    table{width:100%;border-collapse:collapse} th,td{padding:.75rem;border-bottom:1px solid var(--bd);text-align:left;font-size:.92rem;vertical-align:top}
    th{color:var(--mu);font-weight:600}
    .btn{display:inline-flex;align-items:center;gap:.35rem;border:0;border-radius:8px;padding:.55rem .9rem;background:var(--g);color:#fff;font-weight:600;cursor:pointer}
    .btn:hover{background:var(--gd)} .btn.sec{background:#e8f0ea;color:var(--g)} .btn.danger{background:#c0392b}
    .flash{padding:.75rem 1rem;border-radius:8px;margin-bottom:1rem}
    .flash.ok{background:#e8f5e9;color:#1b5e20} .flash.err{background:#fdecea;color:#c0392b}
    label{display:block;font-size:.88rem;font-weight:600;margin:.7rem 0 .3rem}
    input,select,textarea{width:100%;border:1px solid var(--bd);border-radius:8px;padding:.65rem .8rem;font:inherit}
    .row2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}
    .checks{display:flex;flex-wrap:wrap;gap:.85rem;margin:.8rem 0}
    .checks label{display:inline-flex;align-items:center;gap:.4rem;font-weight:500;margin:0}
    .media-grid{display:flex;flex-wrap:wrap;gap:.65rem;margin:.5rem 0 1rem}
    .media-grid figure{margin:0;width:110px;background:#f7f7f7;border:1px solid var(--bd);border-radius:8px;overflow:hidden;position:relative}
    .media-grid img,.media-grid video{width:100%;height:80px;object-fit:cover;display:block}
    .media-grid form{padding:.25rem}
    .login-wrap{min-height:100vh;display:grid;place-items:center;padding:1rem}
    .login-card{width:min(420px,100%)}
    @media(max-width:900px){.shell{grid-template-columns:1fr}.side{height:auto;position:relative}.grid,.row2{grid-template-columns:1fr 1fr}}
    @media(max-width:600px){.grid,.row2{grid-template-columns:1fr}}
  </style>
</head>
<body>
@unless(request()->routeIs('admin.login'))
<div class="shell">
  <aside class="side">
    <a class="logo" href="{{ route('admin.dashboard') }}">Ippeo Admin</a>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Products</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
    <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">Banners</a>
    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
    <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">Customers</a>
    <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">CMS Pages</a>
    <a href="{{ route('admin.enquiries.index') }}" class="{{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">Enquiries</a>
    <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Header / Footer / Home</a>
    <a href="{{ route('admin.integrations.edit') }}" class="{{ request()->routeIs('admin.integrations.*') ? 'active' : '' }}">Payments &amp; Email</a>
    <a href="{{ route('home') }}" target="_blank">View Website</a>
    <form action="{{ route('admin.logout') }}" method="post" style="margin-top:1rem">@csrf<button class="btn sec" style="width:100%">Logout</button></form>
  </aside>
  <div class="main">
    <div class="top">
      <h1 style="margin:0;font-size:1.4rem">@yield('heading', 'Dashboard')</h1>
      <div>@yield('actions')</div>
    </div>
    @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="flash err"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    @yield('content')
  </div>
</div>
@else
  @yield('content')
@endunless
</body>
</html>
