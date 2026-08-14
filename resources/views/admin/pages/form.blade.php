@extends('admin.layouts.app')
@section('title',$page->exists?'Edit Page':'Add Page')
@section('heading',$page->exists?'Edit Page':'Add Page')
@section('content')
<form class="card" method="post" action="{{ $page->exists?route('admin.pages.update',$page):route('admin.pages.store') }}">
@csrf @if($page->exists)@method('PUT')@endif
<label>Title</label><input name="title" value="{{ old('title',$page->title) }}" required />
<label>Slug</label><input name="slug" value="{{ old('slug',$page->slug) }}" />
<label>Meta title</label><input name="meta_title" value="{{ old('meta_title',$page->meta_title) }}" />
<label>Meta description</label><input name="meta_description" value="{{ old('meta_description',$page->meta_description) }}" />
<label>Content (HTML allowed)</label><textarea name="content" rows="14">{{ old('content',$page->content) }}</textarea>
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$page->is_active ?? true)) style="width:auto"> Active</label>
<button class="btn" style="margin-top:1rem">Save Page</button>
</form>
@endsection
