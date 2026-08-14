@extends('admin.layouts.app')
@section('title','Integrations')
@section('heading','Payments & Email Integrations')
@section('content')

<form class="card" method="post" action="{{ route('admin.integrations.update') }}">
@csrf

<h3 style="color:#226b2c;margin-top:0">Payment Methods</h3>
<div class="checks">
<label><input type="checkbox" name="cod_enabled" value="1" @checked(($settings['cod_enabled'] ?? '1') === '1') /> Enable Cash on Delivery</label>
<label><input type="checkbox" name="razorpay_enabled" value="1" @checked(($settings['razorpay_enabled'] ?? '0') === '1') /> Enable Razorpay Online Payment</label>
</div>

<div class="row2">
<div>
<label>Razorpay Mode</label>
<select name="razorpay_mode">
<option value="test" @selected(($settings['razorpay_mode'] ?? 'test') === 'test')>Test (Sandbox)</option>
<option value="live" @selected(($settings['razorpay_mode'] ?? '') === 'live')>Live (Production)</option>
</select>
<p style="color:#5c6670;font-size:.85rem;margin:.4rem 0 0">
Current status:
@if($razorpayReady)
<span style="color:#1b5e20;font-weight:600">Ready ({{ strtoupper($razorpayMode) }})</span>
@else
<span style="color:#c0392b;font-weight:600">Not configured / disabled</span>
@endif
</p>
</div>
</div>

<h3 style="color:#226b2c">Razorpay Test Keys</h3>
<div class="row2">
<div>
<label>Test Key ID</label>
<input name="razorpay_key_id_test" value="{{ $settings['razorpay_key_id_test'] }}" placeholder="rzp_test_..." autocomplete="off" />
</div>
<div>
<label>Test Key Secret</label>
<input type="password" name="razorpay_key_secret_test" value="" placeholder="{{ $settings['razorpay_key_secret_test'] ? '•••••••• (leave blank to keep)' : 'Enter test secret' }}" autocomplete="new-password" />
</div>
</div>

<h3 style="color:#226b2c">Razorpay Live Keys</h3>
<div class="row2">
<div>
<label>Live Key ID</label>
<input name="razorpay_key_id_live" value="{{ $settings['razorpay_key_id_live'] }}" placeholder="rzp_live_..." autocomplete="off" />
</div>
<div>
<label>Live Key Secret</label>
<input type="password" name="razorpay_key_secret_live" value="" placeholder="{{ $settings['razorpay_key_secret_live'] ? '•••••••• (leave blank to keep)' : 'Enter live secret' }}" autocomplete="new-password" />
</div>
</div>

<hr style="border:0;border-top:1px solid #e5e8eb;margin:1.5rem 0" />

<h3 style="color:#226b2c">Email / SMTP</h3>
<div class="row2">
<div>
<label>Mailer</label>
<select name="mail_mailer">
<option value="smtp" @selected(($settings['mail_mailer'] ?? '') === 'smtp')>SMTP</option>
<option value="sendmail" @selected(($settings['mail_mailer'] ?? '') === 'sendmail')>Sendmail</option>
<option value="log" @selected(($settings['mail_mailer'] ?? '') === 'log')>Log (dev only)</option>
</select>
<label>SMTP Host</label>
<input name="mail_host" value="{{ $settings['mail_host'] }}" placeholder="smtp.gmail.com" />
<label>SMTP Port</label>
<input name="mail_port" value="{{ $settings['mail_port'] }}" placeholder="587" />
<label>Encryption</label>
<select name="mail_encryption">
<option value="tls" @selected(($settings['mail_encryption'] ?? 'tls') === 'tls')>TLS</option>
<option value="ssl" @selected(($settings['mail_encryption'] ?? '') === 'ssl')>SSL</option>
<option value="" @selected(($settings['mail_encryption'] ?? '') === '')>None</option>
</select>
</div>
<div>
<label>SMTP Username</label>
<input name="mail_username" value="{{ $settings['mail_username'] }}" autocomplete="off" />
<label>SMTP Password</label>
<input type="password" name="mail_password" value="" placeholder="{{ $settings['mail_password'] ? '•••••••• (leave blank to keep)' : 'SMTP password / app password' }}" autocomplete="new-password" />
<label>From Email</label>
<input name="mail_from_address" type="email" value="{{ $settings['mail_from_address'] }}" />
<label>From Name</label>
<input name="mail_from_name" value="{{ $settings['mail_from_name'] }}" />
</div>
</div>

<h3 style="color:#226b2c">Notification Recipients</h3>
<div class="row2">
<div>
<label>Order admin email</label>
<input name="order_admin_email" type="email" value="{{ $settings['order_admin_email'] }}" placeholder="orders@ippeo.in" />
</div>
<div>
<label>Contact / enquiry admin email</label>
<input name="enquiry_email" type="email" value="{{ $settings['enquiry_email'] }}" placeholder="info@ippeo.in" />
</div>
</div>

<div class="checks" style="margin-top:1rem">
<label><input type="checkbox" name="order_email_customer" value="1" @checked(($settings['order_email_customer'] ?? '1') === '1') /> Email customer on order confirmation</label>
<label><input type="checkbox" name="order_email_admin" value="1" @checked(($settings['order_email_admin'] ?? '1') === '1') /> Email admin on new order</label>
<label><input type="checkbox" name="contact_email_admin" value="1" @checked(($settings['contact_email_admin'] ?? '1') === '1') /> Email admin on contact / enquiry form</label>
</div>

<button class="btn" style="margin-top:1.25rem">Save Integration Settings</button>
</form>

<div class="row2" style="margin-top:1.25rem">
<form class="card" method="post" action="{{ route('admin.integrations.test-razorpay') }}">
@csrf
<h3 style="color:#226b2c;margin-top:0">Test Razorpay</h3>
<p style="color:#5c6670;font-size:.9rem">Verifies API credentials for the currently selected mode (Test/Live) using keys saved above. Save settings first if you just changed them.</p>
<button class="btn sec" type="submit">Test Razorpay Connection</button>
</form>

<form class="card" method="post" action="{{ route('admin.integrations.test-mail') }}">
@csrf
<h3 style="color:#226b2c;margin-top:0">Test Email</h3>
<p style="color:#5c6670;font-size:.9rem">Sends a plain test message using the saved mail settings.</p>
<label>Send test to</label>
<input type="email" name="test_email" required value="{{ $settings['order_admin_email'] ?: $settings['enquiry_email'] }}" />
<button class="btn sec" style="margin-top:.8rem" type="submit">Send Test Email</button>
</form>
</div>
@endsection
