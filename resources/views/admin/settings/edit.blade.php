@extends('admin.layouts.app')
@section('title','Site Settings')
@section('heading','Header, Footer & Homepage Content')
@section('content')
<form class="card" method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">
@csrf
<h3 style="color:#226b2c;margin-top:0">General / Header & Footer</h3>
<div class="row2">
<div>
<label>Site name</label><input name="site_name" value="{{ $settings['site_name'] }}" />
<label>Tagline</label><input name="tagline" value="{{ $settings['tagline'] }}" />
<label>Phone 1</label><input name="phone_1" value="{{ $settings['phone_1'] }}" />
<label>Phone 2</label><input name="phone_2" value="{{ $settings['phone_2'] }}" />
<label>Email</label><input name="email" value="{{ $settings['email'] }}" />
<label>Enquiry forward email</label><input name="enquiry_email" value="{{ $settings['enquiry_email'] }}" />
</div>
<div>
<label>Address</label><textarea name="address" rows="3">{{ $settings['address'] }}</textarea>
<label>Instagram URL</label><input name="instagram" value="{{ $settings['instagram'] }}" />
<label>Facebook URL</label><input name="facebook" value="{{ $settings['facebook'] }}" />
<label>Copyright</label><input name="copyright" value="{{ $settings['copyright'] }}" />
<label>Free shipping min (₹)</label><input name="free_shipping_min" value="{{ $settings['free_shipping_min'] }}" />
<label>Shipping fee (₹)</label><input name="shipping_fee" value="{{ $settings['shipping_fee'] }}" />
<label>Logo file</label><input type="file" name="logo_file" accept="image/*" />
@if($settings['logo'])<p><img src="{{ media_url($settings['logo']) }}" style="height:60px"></p>@endif
<input type="hidden" name="logo" value="{{ $settings['logo'] }}" />
</div>
</div>

<h3 style="color:#226b2c">Homepage Sections</h3>
<label>Products section title</label><input name="home_products_title" value="{{ $settings['home_products_title'] }}" />
<label>Products section subtitle</label><input name="home_products_subtitle" value="{{ $settings['home_products_subtitle'] }}" />
<label>About title</label><input name="home_about_title" value="{{ $settings['home_about_title'] }}" />
<label>About text</label><textarea name="home_about_text" rows="6">{{ $settings['home_about_text'] }}</textarea>
<label>About image</label><input type="file" name="home_about_image_file" accept="image/*" />
<input type="hidden" name="home_about_image" value="{{ $settings['home_about_image'] }}" />
@if($settings['home_about_image'])<p><img src="{{ media_url($settings['home_about_image']) }}" style="max-width:220px;border-radius:8px"></p>@endif
<label>Inquiry title</label><input name="home_inquiry_title" value="{{ $settings['home_inquiry_title'] }}" />
<label>Inquiry subtitle</label><input name="home_inquiry_subtitle" value="{{ $settings['home_inquiry_subtitle'] }}" />

<h3 style="color:#226b2c">Footer Links (JSON)</h3>
<label>Company links JSON</label>
<textarea name="footer_company_links" rows="6">{{ $settings['footer_company_links'] }}</textarea>
<label>Customer care links JSON</label>
<textarea name="footer_care_links" rows="6">{{ $settings['footer_care_links'] }}</textarea>
<p style="color:#5c6670;font-size:.85rem">Format: [{"label":"About Us","url":"/page/about"}]</p>
<button class="btn" style="margin-top:1rem">Save Settings</button>
</form>
@endsection
