<header class="site-header" id="siteHeader">
  <div class="header-inner">
    <div class="header-top">
      <div class="header-left">
        <button class="icon-btn menu-toggle" aria-label="Open menu" type="button" id="menuToggle" aria-expanded="false" aria-controls="mobileDrawer">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <a href="{{ route('home') }}" class="brand" aria-label="{{ $site['site_name'] ?? 'Ippeo' }} home">
          <img src="{{ media_url($site['logo'] ?? 'images/logo.png') }}" alt="{{ $site['site_name'] ?? 'Ippeo' }}" class="brand-logo" />
        </a>
      </div>

      <form class="search-form" role="search" action="{{ route('shop') }}" method="get">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search for Products" aria-label="Search products" />
        <button type="submit" aria-label="Search">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        </button>
      </form>

      <div class="header-actions">
        <a class="icon-btn cart-btn" href="{{ route('cart') }}" aria-label="Cart">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M3 4h2l2.4 11.2a1 1 0 001 .8h9.6a1 1 0 001-.8L21 8H7"/></svg>
          <span class="cart-count">0</span>
        </a>
      </div>
    </div>

    <nav class="category-nav" id="categoryNav" aria-label="Product categories">
      <div class="category-track">
        @foreach($navCategories as $cat)
          <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="category-item {{ request('category') === $cat->slug ? 'is-active' : '' }}">
            <span class="cat-icon">{{ $cat->icon ?: strtoupper(substr($cat->name,0,2)) }}</span>
            {{ $cat->name }}
          </a>
        @endforeach
      </div>
    </nav>
  </div>
</header>

<div class="mobile-drawer-overlay" id="drawerOverlay" hidden></div>
<aside class="mobile-drawer" id="mobileDrawer" aria-hidden="true" aria-label="Site menu">
  <div class="mobile-drawer-head">
    <img src="{{ media_url($site['logo'] ?? 'images/logo.png') }}" alt="{{ $site['site_name'] ?? 'Ippeo' }}" class="mobile-drawer-logo" />
    <button type="button" class="icon-btn" id="menuClose" aria-label="Close menu">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
  </div>

  <form class="mobile-search" action="{{ route('shop') }}" method="get" role="search">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search for Products" aria-label="Search products" />
    <button type="submit" aria-label="Search">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
    </button>
  </form>

  <div class="mobile-drawer-section">
    <h3>Shop by Category</h3>
    <ul class="mobile-menu-list">
      <li><a href="{{ route('shop') }}">All Products</a></li>
      @foreach($navCategories as $cat)
        <li>
          <a href="{{ route('shop', ['category' => $cat->slug]) }}">
            <span class="cat-icon">{{ $cat->icon ?: strtoupper(substr($cat->name,0,2)) }}</span>
            {{ $cat->name }}
          </a>
        </li>
      @endforeach
      <li><a href="{{ route('categories') }}">More Categories</a></li>
      <li><a href="{{ route('new-launches') }}">New Launches</a></li>
    </ul>
  </div>

  <div class="mobile-drawer-section">
    <h3>Quick Links</h3>
    <ul class="mobile-menu-list">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('page.show', 'about') }}">About Us</a></li>
      <li><a href="{{ route('page.show', 'why-ippeo') }}">Why Ippeo</a></li>
      <li><a href="{{ route('page.show', 'blog') }}">Blog</a></li>
      <li><a href="{{ route('contact') }}">Contact Us</a></li>
      <li><a href="{{ route('cart') }}">My Cart</a></li>
      <li><a href="{{ route('checkout') }}">Checkout</a></li>
    </ul>
  </div>

  <div class="mobile-drawer-section">
    <h3>Customer Care</h3>
    <ul class="mobile-menu-list">
      <li><a href="{{ route('page.show', 'faq') }}">FAQs</a></li>
      <li><a href="{{ route('page.show', 'shipping-policy') }}">Shipping Policy</a></li>
      <li><a href="{{ route('page.show', 'returns') }}">Return &amp; Refund Policy</a></li>
      <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
      <li><a href="{{ route('page.show', 'terms') }}">Terms &amp; Conditions</a></li>
    </ul>
  </div>
</aside>
