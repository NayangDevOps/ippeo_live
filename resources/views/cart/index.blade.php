@extends('layouts.store')
@section('title', 'Cart | Ippeo')
@section('content')
<section class="page-hero"><div class="container">
<nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <span>Cart</span></nav>
<h1>Shopping Cart</h1>
</div></section>
<section class="page-section"><div class="container" id="cartRoot"></div></section>
@endsection
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => IppeoCart.renderPage());</script>
@endpush
