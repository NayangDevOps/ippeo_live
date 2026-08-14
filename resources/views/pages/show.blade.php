@extends('layouts.store')
@section('title', ($page->meta_title ?: $page->title) . ' | Ippeo')
@section('meta', $page->meta_description)
@section('content')
<section class="page-hero"><div class="container">
<nav class="breadcrumbs"><a href="{{ route('home') }}">Home</a> <span>&gt;</span> <span>{{ $page->title }}</span></nav>
<h1>{{ $page->title }}</h1>
</div></section>
<section class="page-section"><div class="container prose content-card">{!! $page->content !!}</div></section>
@endsection
