@extends('admin.layouts.app')
@section('title',$banner->exists?'Edit Banner':'Add Banner')
@section('heading',$banner->exists?'Edit Banner':'Add Banner')
@section('content')
<form class="card" method="post" enctype="multipart/form-data" action="{{ $banner->exists?route('admin.banners.update',$banner):route('admin.banners.store') }}">
@csrf @if($banner->exists)@method('PUT')@endif
<label>Script text</label><input name="script_text" value="{{ old('script_text',$banner->script_text) }}" />
<label>Title</label><input name="title" value="{{ old('title',$banner->title) }}" />
<label>Subtitle</label><input name="subtitle" value="{{ old('subtitle',$banner->subtitle) }}" />
<label>Button text</label><input name="button_text" value="{{ old('button_text',$banner->button_text) }}" />
<label>Button link</label><input name="button_link" value="{{ old('button_link',$banner->button_link) }}" />
<label>Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order',$banner->sort_order ?? 0) }}" />
<label>Banner image</label><input type="file" name="image" accept="image/*" />
@if($banner->image)<p><img src="{{ media_url($banner->image) }}" style="max-width:280px;border-radius:8px"></p>@endif
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$banner->is_active ?? true)) style="width:auto"> Active</label>
<button class="btn" style="margin-top:1rem">Save</button>
</form>
@endsection
