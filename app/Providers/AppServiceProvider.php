<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            static $shared = null;
            if ($shared === null) {
                try {
                    $shared = [
                        'navCategories' => Category::where('is_active', true)->orderBy('sort_order')->get(),
                        'site' => Setting::many([
                            'site_name' => 'Ippeo Essential Products',
                            'tagline' => "Nature's secret; Ippeo's promise",
                            'logo' => 'images/logo.png',
                            'phone_1' => '',
                            'phone_2' => '',
                            'email' => 'info@ippeo.in',
                            'address' => '',
                            'instagram' => '#',
                            'facebook' => '#',
                            'copyright' => '© 2025 Ippeo Essential Products. All Rights Reserved.',
                            'footer_company_links' => '[]',
                            'footer_care_links' => '[]',
                        ]),
                    ];
                } catch (\Throwable $e) {
                    $shared = [
                        'navCategories' => collect(),
                        'site' => [
                            'site_name' => 'Ippeo Essential Products',
                            'tagline' => "Nature's secret; Ippeo's promise",
                            'logo' => 'images/logo.png',
                            'phone_1' => '',
                            'phone_2' => '',
                            'email' => 'info@ippeo.in',
                            'address' => '',
                            'instagram' => '#',
                            'facebook' => '#',
                            'copyright' => '© 2025 Ippeo Essential Products. All Rights Reserved.',
                            'footer_company_links' => '[]',
                            'footer_care_links' => '[]',
                        ],
                    ];
                }
            }
            $view->with($shared);
        });
    }
}
