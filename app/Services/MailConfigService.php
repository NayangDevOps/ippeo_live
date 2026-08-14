<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class MailConfigService
{
    public static function applyFromSettings(): void
    {
        $mailer = Setting::getValue('mail_mailer', config('mail.default', 'sendmail'));

        if (! in_array($mailer, ['smtp', 'sendmail', 'log', 'array'], true)) {
            $mailer = 'sendmail';
        }

        Config::set('mail.default', $mailer);

        if ($mailer === 'smtp') {
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', Setting::getValue('mail_host', config('mail.mailers.smtp.host')));
            Config::set('mail.mailers.smtp.port', (int) Setting::getValue('mail_port', config('mail.mailers.smtp.port', 587)));
            Config::set('mail.mailers.smtp.username', Setting::getValue('mail_username', config('mail.mailers.smtp.username')));
            Config::set('mail.mailers.smtp.password', Setting::getValue('mail_password', config('mail.mailers.smtp.password')));
            $encryption = Setting::getValue('mail_encryption', config('mail.mailers.smtp.encryption'));
            Config::set('mail.mailers.smtp.encryption', $encryption === '' ? null : $encryption);
            Config::set('mail.mailers.smtp.timeout', 30);
        }

        $fromAddress = Setting::getValue('mail_from_address', config('mail.from.address'));
        $fromName = Setting::getValue('mail_from_name', config('mail.from.name'));

        if ($fromAddress) {
            Config::set('mail.from.address', $fromAddress);
        }
        if ($fromName) {
            Config::set('mail.from.name', $fromName);
        }
    }

    public static function adminOrderEmail(): string
    {
        return (string) (
            Setting::getValue('order_admin_email')
            ?: Setting::getValue('enquiry_email')
            ?: Setting::getValue('email')
            ?: env('MAIL_ADMIN_TO', 'info@ippeo.in')
        );
    }

    public static function adminEnquiryEmail(): string
    {
        return (string) (
            Setting::getValue('enquiry_email')
            ?: Setting::getValue('email')
            ?: env('MAIL_ADMIN_TO', 'info@ippeo.in')
        );
    }
}
