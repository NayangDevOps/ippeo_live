<?php

namespace App\Services;

use App\Mail\OrderPlacedAdmin;
use App\Mail\OrderPlacedCustomer;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class OrderMailService
{
    public function sendConfirmation(Order $order): void
    {
        if ($order->confirmation_emailed) {
            return;
        }

        MailConfigService::applyFromSettings();
        $order->loadMissing('items');

        $sentAny = false;

        if (Setting::getValue('order_email_customer', '1') === '1' && $order->customer_email) {
            try {
                Mail::to($order->customer_email)->send(new OrderPlacedCustomer($order));
                $sentAny = true;
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (Setting::getValue('order_email_admin', '1') === '1') {
            $adminTo = MailConfigService::adminOrderEmail();
            if ($adminTo) {
                try {
                    Mail::to($adminTo)->send(new OrderPlacedAdmin($order));
                    $sentAny = true;
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        if ($sentAny) {
            $order->update(['confirmation_emailed' => true]);
        }
    }
}
