@extends('layouts.store')

@section('title', ($site['site_name'] ?? 'Ippeo') . " | Nature's Secret; Ippeo's Promise")

@section('content')
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

<section class="hero" aria-label="Promotions">
  <div class="hero-slider" id="heroSlider">
    @forelse($banners as $i => $banner)
      <article class="hero-slide {{ $i === 0 ? 'is-active' : '' }}" style="--hero-bg: url('{{ media_url($banner->image) }}')">
        <div class="hero-content">
          @if($banner->script_text)<p class="hero-script">{{ $banner->script_text }}</p>@endif
          <h1>{{ $banner->title }}</h1>
          @if($banner->subtitle)<p class="hero-sub">{{ $banner->subtitle }}</p>@endif
          @if($banner->button_text)
            <a href="{{ $banner->button_link ?: route('shop') }}" class="btn btn-primary">{{ $banner->button_text }}</a>
          @endif
        </div>
      </article>
    @empty
      <article class="hero-slide is-active" style="--hero-bg: url('{{ asset('images/hero-slide-1.jpg') }}')">
        <div class="hero-content">
          <p class="hero-script">Embrace Nature</p>
          <h1>Nourish Your Skin</h1>
          <p class="hero-sub">Nature's care for healthy, glowing skin every day.</p>
          <a href="{{ route('shop') }}" class="btn btn-primary">SHOP NOW</a>
        </div>
      </article>
    @endforelse
  </div>
  <button class="hero-arrow prev" type="button" aria-label="Previous slide">&#10094;</button>
  <button class="hero-arrow next" type="button" aria-label="Next slide">&#10095;</button>
  <div class="hero-dots" id="heroDots"></div>
</section>

<section class="products-section" id="products">
  <div class="container">
    <header class="section-head">
      <h2>{{ $settings['home_products_title'] }}</h2>
      <p>{{ $settings['home_products_subtitle'] }}</p>
    </header>
    <div class="product-grid">
      @foreach($products as $product)
        @include('partials.product-card', ['product' => $product])
      @endforeach
    </div>
    <div style="text-align:center;margin-top:1.5rem">
      <a class="btn btn-primary" href="{{ route('shop') }}">View All Products</a>
    </div>
  </div>
</section>

<section class="about-section" id="about">
  <div class="container about-grid">
    <div class="about-copy">
      <h2>{{ $settings['home_about_title'] }}</h2>
      @foreach(preg_split("/\n\s*\n/", $settings['home_about_text'] ?? '') as $para)
        @if(trim($para))
          <p>{{ trim($para) }}</p>
        @endif
      @endforeach
      <a href="{{ route('page.show', 'about') }}" class="show-more">SHOW MORE</a>
    </div>
    <div class="about-visual">
      <img src="{{ media_url($settings['home_about_image']) }}" alt="Ippeo brand" loading="lazy" />
    </div>
  </div>
</section>

<section class="inquiry-section" id="inquiry">
  <div class="container">
    <header class="section-head center">
      <h2>{{ $settings['home_inquiry_title'] }}</h2>
      <p>{{ $settings['home_inquiry_subtitle'] }}</p>
    </header>
    <form class="inquiry-form" action="{{ route('enquiry.submit') }}" method="post">
      @csrf
      <input type="hidden" name="source" value="homepage" />
      <div class="form-grid">
        <div class="form-col">
          <label for="name">Your Name</label>
          <input id="name" name="name" type="text" required value="{{ old('name') }}" />
          <label for="email">Email Address</label>
          <input id="email" name="email" type="email" required value="{{ old('email') }}" />
          <label for="phone">Phone Number</label>
          <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" />
        </div>
        <div class="form-col">
          <label for="message">Your Message</label>
          <textarea id="message" name="message" rows="8" required placeholder="How can we help you?">{{ old('message') }}</textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-wide">SEND INQUIRY</button>
    </form>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const slides = [...document.querySelectorAll('.hero-slide')];
  const dots = document.getElementById('heroDots');
  if (!slides.length || !dots) return;
  let i = 0, t;
  const go = n => {
    i = (n + slides.length) % slides.length;
    slides.forEach((s, idx) => s.classList.toggle('is-active', idx === i));
    [...dots.children].forEach((d, idx) => d.classList.toggle('is-active', idx === i));
  };
  slides.forEach((_, n) => {
    const b = document.createElement('button');
    b.type = 'button';
    if (!n) b.classList.add('is-active');
    b.onclick = () => { go(n); restart(); };
    dots.appendChild(b);
  });
  document.querySelector('.hero-arrow.prev')?.addEventListener('click', () => { go(i-1); restart(); });
  document.querySelector('.hero-arrow.next')?.addEventListener('click', () => { go(i+1); restart(); });
  function restart(){ clearInterval(t); t = setInterval(() => go(i+1), 5500); }
  restart();
});
</script>
@endpush
