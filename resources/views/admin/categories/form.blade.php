@extends('admin.layouts.app')
@section('title',$category->exists?'Edit Category':'Add Category')
@section('heading',$category->exists?'Edit Category':'Add Category')
@section('content')
<form class="card" method="post" enctype="multipart/form-data" action="{{ $category->exists?route('admin.categories.update',$category):route('admin.categories.store') }}">
@csrf @if($category->exists)@method('PUT')@endif
<label>Name</label><input name="name" value="{{ old('name',$category->name) }}" required />
<label>Icon (2 letters)</label><input name="icon" value="{{ old('icon',$category->icon) }}" maxlength="10" />
<label>Description</label><textarea name="description" rows="3">{{ old('description',$category->description) }}</textarea>
<label>Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order',$category->sort_order ?? 0) }}" />
<label>Image</label><input type="file" name="image" accept="image/*" />
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$category->is_active ?? true)) style="width:auto"> Active</label>
<button class="btn" style="margin-top:1rem">Save</button>
</form>
@endsection
