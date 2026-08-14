@extends('layouts.store')
@section('title', 'Contact Us | Ippeo')
@section('content')
<section class="page-hero"><div class="container">
<nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <span>Contact Us</span></nav>
<h1>Have a Question? We're Here to Help!</h1>
<p>Share your query and our team will get back to you shortly.</p>
</div></section>
<section class="page-section"><div class="container checkout-grid">
<form class="inquiry-form content-card" action="{{ route('enquiry.submit') }}" method="post">
@csrf
<input type="hidden" name="source" value="contact" />
<div class="form-grid">
<div class="form-col">
<label for="name">Your Name</label><input id="name" name="name" required />
<label for="email">Email Address</label><input id="email" name="email" type="email" required />
<label for="phone">Phone Number</label><input id="phone" name="phone" type="tel" />
</div>
<div class="form-col">
<label for="message">Your Message</label>
<textarea id="message" name="message" rows="8" required></textarea>
</div>
</div>
<button type="submit" class="btn btn-primary btn-wide">SEND INQUIRY</button>
</form>
<aside class="content-card">
<h3 style="margin-top:0;color:var(--green)">Reach Us</h3>
<p><strong>Email:</strong><br><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></p>
<p><strong>Phone:</strong><br>{{ $site['phone_1'] }}<br>{{ $site['phone_2'] }}</p>
<p><strong>Address:</strong><br>{{ $site['address'] }}</p>
</aside>
</div></section>
@endsection
