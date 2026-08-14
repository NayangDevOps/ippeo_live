<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $keys = [
            'site_name', 'tagline', 'logo', 'phone_1', 'phone_2', 'email', 'address',
            'instagram', 'facebook', 'copyright', 'enquiry_email',
            'free_shipping_min', 'shipping_fee',
            'home_products_title', 'home_products_subtitle',
            'home_about_title', 'home_about_text', 'home_about_image',
            'home_inquiry_title', 'home_inquiry_subtitle',
            'footer_company_links', 'footer_care_links',
        ];
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::getValue($key, '');
        }
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = $request->except(['_token', 'logo_file', 'home_about_image_file']);

        if ($request->hasFile('logo_file')) {
            $fields['logo'] = $request->file('logo_file')->store('site', 'public');
        }
        if ($request->hasFile('home_about_image_file')) {
            $fields['home_about_image'] = $request->file('home_about_image_file')->store('site', 'public');
        }

        foreach ($fields as $key => $value) {
            $group = str_starts_with($key, 'home_') ? 'homepage' : (str_starts_with($key, 'footer_') ? 'footer' : 'general');
            Setting::setValue($key, is_array($value) ? json_encode($value) : $value, $group);
        }

        return back()->with('success', 'Settings saved.');
    }
}
