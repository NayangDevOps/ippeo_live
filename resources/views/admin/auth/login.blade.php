@extends('admin.layouts.app')
@section('title','Login')
@section('content')
<div class="login-wrap">
  <div class="card login-card">
    <h2 style="margin-top:0;color:#226b2c">Ippeo Admin Login</h2>
    @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif
    <form method="post" action="{{ route('admin.login.post') }}">
      @csrf
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required />
      <label>Password</label>
      <input type="password" name="password" required />
      <label style="display:flex;align-items:center;gap:.4rem;margin-top:.8rem"><input type="checkbox" name="remember" value="1" style="width:auto" /> Remember me</label>
      <button class="btn" style="width:100%;margin-top:1rem" type="submit">Sign In</button>
    </form>
  </div>
</div>
@endsection
