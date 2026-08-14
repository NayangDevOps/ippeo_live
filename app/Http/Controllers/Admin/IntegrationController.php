<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MailConfigService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IntegrationController extends Controller
{
    private array $keys = [
        'razorpay_enabled',
        'razorpay_mode',
        'razorpay_key_id_test',
        'razorpay_key_secret_test',
        'razorpay_key_id_live',
        'razorpay_key_secret_live',
        'cod_enabled',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'order_admin_email',
        'enquiry_email',
        'order_email_customer',
        'order_email_admin',
        'contact_email_admin',
    ];

    public function edit()
    {
        $defaults = [
            'razorpay_enabled' => '0',
            'razorpay_mode' => 'test',
            'razorpay_key_id_test' => '',
            'razorpay_key_secret_test' => '',
            'razorpay_key_id_live' => '',
            'razorpay_key_secret_live' => '',
            'cod_enabled' => '1',
            'mail_mailer' => config('mail.default', 'sendmail'),
            'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => (string) config('mail.mailers.smtp.port', '587'),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_password' => config('mail.mailers.smtp.password', ''),
            'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls') ?: 'tls',
            'mail_from_address' => config('mail.from.address', 'noreply@ippeo.in'),
            'mail_from_name' => config('mail.from.name', 'Ippeo Essential Products'),
            'order_admin_email' => Setting::getValue('enquiry_email', 'info@ippeo.in'),
            'enquiry_email' => 'info@ippeo.in',
            'order_email_customer' => '1',
            'order_email_admin' => '1',
            'contact_email_admin' => '1',
        ];

        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::getValue($key, $defaults[$key] ?? '');
        }

        $razorpay = app(RazorpayService::class);

        return view('admin.integrations.edit', [
            'settings' => $settings,
            'razorpayReady' => $razorpay->isEnabled(),
            'razorpayMode' => $razorpay->mode(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'razorpay_enabled' => 'nullable|in:0,1',
            'razorpay_mode' => 'required|in:test,live',
            'razorpay_key_id_test' => 'nullable|string|max:120',
            'razorpay_key_secret_test' => 'nullable|string|max:120',
            'razorpay_key_id_live' => 'nullable|string|max:120',
            'razorpay_key_secret_live' => 'nullable|string|max:120',
            'cod_enabled' => 'nullable|in:0,1',
            'mail_mailer' => 'required|in:smtp,sendmail,log',
            'mail_host' => 'nullable|string|max:190',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:190',
            'mail_password' => 'nullable|string|max:190',
            'mail_encryption' => 'nullable|in:tls,ssl,',
            'mail_from_address' => 'nullable|email|max:150',
            'mail_from_name' => 'nullable|string|max:150',
            'order_admin_email' => 'nullable|email|max:150',
            'enquiry_email' => 'nullable|email|max:150',
            'order_email_customer' => 'nullable|in:0,1',
            'order_email_admin' => 'nullable|in:0,1',
            'contact_email_admin' => 'nullable|in:0,1',
        ]);

        $checkboxes = [
            'razorpay_enabled',
            'cod_enabled',
            'order_email_customer',
            'order_email_admin',
            'contact_email_admin',
        ];

        foreach ($checkboxes as $key) {
            $data[$key] = $request->boolean($key) ? '1' : '0';
        }

        // Keep existing secrets if left blank (masked placeholder)
        foreach (['razorpay_key_secret_test', 'razorpay_key_secret_live', 'mail_password'] as $secret) {
            if (! array_key_exists($secret, $data) || $data[$secret] === null || $data[$secret] === '') {
                unset($data[$secret]);
            }
        }

        foreach ($data as $key => $value) {
            if (! in_array($key, $this->keys, true)) {
                continue;
            }
            $group = str_starts_with($key, 'razorpay_') || $key === 'cod_enabled'
                ? 'payments'
                : 'mail';
            Setting::setValue($key, (string) ($value ?? ''), $group);
        }

        return back()->with('success', 'Integration settings saved.');
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:150',
        ]);

        try {
            MailConfigService::applyFromSettings();
            $to = $request->input('test_email');
            Mail::raw(
                "This is a test email from Ippeo admin integrations.\n\nMailer: " . config('mail.default') . "\nTime: " . now()->toDateTimeString(),
                function ($message) use ($to) {
                    $message->to($to)->subject('Ippeo — Test Email');
                }
            );

            return back()->with('success', 'Test email sent to ' . $to . '. Check inbox/spam.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Test email failed: ' . $e->getMessage());
        }
    }

    public function testRazorpay(RazorpayService $razorpay)
    {
        try {
            $result = $razorpay->testConnection();

            return back()->with('success', $result['message'] . ' Key: ' . $result['key_id']);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Razorpay test failed: ' . $e->getMessage());
        }
    }
}
