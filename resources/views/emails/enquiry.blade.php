<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;color:#222">
<h2 style="color:#226b2c">New Website Enquiry</h2>
<p><strong>Name:</strong> {{ $enquiry->name }}</p>
<p><strong>Email:</strong> {{ $enquiry->email }}</p>
<p><strong>Phone:</strong> {{ $enquiry->phone }}</p>
<p><strong>Source:</strong> {{ $enquiry->source }}</p>
<p><strong>Message:</strong></p>
<p style="white-space:pre-wrap;background:#f5f6f7;padding:12px;border-radius:8px">{{ $enquiry->message }}</p>
<p style="color:#777;font-size:12px">Submitted at {{ $enquiry->created_at }}</p>
</body></html>
