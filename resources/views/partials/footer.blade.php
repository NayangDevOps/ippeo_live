@php
  $company = json_decode($site['footer_company_links'] ?? '[]', true) ?: [];
  $care = json_decode($site['footer_care_links'] ?? '[]', true) ?: [];
@endphp
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a href="{{ route('home') }}"><img src="{{ media_url($site['logo'] ?? 'images/logo.png') }}" alt="{{ $site['site_name'] ?? 'Ippeo' }}" /></a>
      <p class="footer-tagline">{{ $site['tagline'] ?? '' }}</p>
      <div class="socials">
        <a href="{{ $site['instagram'] ?? '#' }}" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
        </a>
        <a href="{{ $site['facebook'] ?? '#' }}" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h2.5l.5-3H14V9z"/></svg>
        </a>
      </div>
    </div>
    <div>
      <h4>Company</h4>
      <ul>
        @foreach($company as $link)
          <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></li>
        @endforeach
      </ul>
    </div>
    <div>
      <h4>Customer Care</h4>
      <ul>
        @foreach($care as $link)
          <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['label'] ?? '' }}</a></li>
        @endforeach
      </ul>
    </div>
    <div>
      <h4>Contact Us</h4>
      <ul class="contact-list">
        @if(!empty($site['phone_1']))<li><a href="tel:{{ preg_replace('/\s+/', '', $site['phone_1']) }}">{{ $site['phone_1'] }}</a></li>@endif
        @if(!empty($site['phone_2']))<li><a href="tel:{{ preg_replace('/\s+/', '', $site['phone_2']) }}">{{ $site['phone_2'] }}</a></li>@endif
        <li><a href="mailto:{{ $site['email'] ?? 'info@ippeo.in' }}">{{ $site['email'] ?? 'info@ippeo.in' }}</a></li>
        <li>{{ $site['address'] ?? '' }}</li>
      </ul>
    </div>
    <div class="newsletter">
      <h4>Subscribe to get updates...</h4>
      <form class="newsletter-form" action="{{ route('newsletter') }}" method="post">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" aria-label="Newsletter email" required />
        <button type="submit" aria-label="Subscribe">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
        </button>
      </form>
    </div>
  </div>
  <div class="footer-bar">
    <div class="container footer-bar-inner">
      <p>{{ $site['copyright'] ?? '' }}</p>
      <button class="back-top" type="button" aria-label="Back to top" id="backTop">↑</button>
    </div>
  </div>
</footer>
