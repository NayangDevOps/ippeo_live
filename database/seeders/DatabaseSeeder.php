<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@ippeo.in')],
            [
                'name' => 'Ippeo Admin',
                'password' => env('ADMIN_PASSWORD', 'IppeoAdmin@2026'),
                'is_admin' => true,
            ]
        );

        $categories = [
            ['name' => 'De-Tan', 'icon' => 'Tg'],
            ['name' => 'Sunscreen', 'icon' => 'Su'],
            ['name' => 'Face Wash', 'icon' => 'Fc'],
            ['name' => 'Facial Kit', 'icon' => 'Fl'],
            ['name' => 'Probiome', 'icon' => 'Pb'],
            ['name' => 'Proceuticals', 'icon' => 'Pc'],
            ['name' => 'Prosculpt Treatment', 'icon' => 'Ps'],
            ['name' => 'Salon at Home', 'icon' => 'Sl'],
        ];

        $catMap = [];
        foreach ($categories as $i => $c) {
            $cat = Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                [
                    'name' => $c['name'],
                    'icon' => $c['icon'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]
            );
            $catMap[Str::slug($c['name'])] = $cat->id;
        }

        $products = [
            [
                'name' => 'Tan to Tango Combo - De Tan Sunscreen',
                'category' => 'de-tan',
                'sku' => 'IPP-TT-001',
                'size' => 'Combo Pack',
                'short' => 'De-Tan + SPF protection duo for sun-exposed skin.',
                'description' => 'A powerful de-tan and sunscreen combo crafted with botanical extracts to fade tan, even skin tone, and shield against UVA & UVB rays.',
                'benefits' => "Reduces visible tan and dullness\nBroad-spectrum SPF protection\nNon-greasy, quick-absorbing texture\nSuitable for regular daytime use",
                'ingredients' => 'Aloe Vera, Niacinamide, Vitamin E, Zinc Oxide, Green Tea Extract, Glycerin',
                'how' => 'Apply sunscreen generously 15 minutes before sun exposure. Use de-tan cream as directed on clean skin.',
                'price' => 539, 'mrp' => 799, 'discount' => 33, 'rating' => 4.6, 'reviews' => 125,
                'badge' => 'New Arrival', 'cashback' => true, 'best' => false, 'featured' => true, 'new' => true,
                'image' => 'images/products/product-combo.jpg',
            ],
            [
                'name' => 'Ippeo SPF 50+ Sunscreen Lotion',
                'category' => 'sunscreen',
                'sku' => 'IPP-SU-050',
                'size' => '100 ml',
                'short' => 'Broad-spectrum SPF 50+ lotion for everyday sun care.',
                'description' => 'Ippeo SPF 50+ Sunscreen Lotion offers reliable daily protection against UVA & UVB damage with a soft, non-greasy finish.',
                'benefits' => "SPF 50+ UVA/UVB defence\nLightweight & non-greasy\nHelps prevent sun-induced dullness\nDermatologically inspired formula",
                'ingredients' => 'Zinc Oxide, Titanium Dioxide, Aloe Vera, Vitamin E, Hyaluronic Acid, Cucumber Extract',
                'how' => 'Shake well. Apply evenly on face and exposed skin 15 minutes before going outdoors.',
                'price' => 449, 'mrp' => 599, 'discount' => 25, 'rating' => 4.4, 'reviews' => 98,
                'badge' => 'Best Seller', 'cashback' => false, 'best' => true, 'featured' => true, 'new' => false,
                'image' => 'images/products/product-sunscreen.jpg',
            ],
            [
                'name' => 'Aloe Vera Face Wash — Gentle Cleanse',
                'category' => 'face-wash',
                'sku' => 'IPP-FW-ALV',
                'size' => '150 ml',
                'short' => 'Soothing daily cleanser with pure aloe care.',
                'description' => 'A gentle aloe-based face wash that cleanses impurities without stripping natural moisture.',
                'benefits' => "Deep yet gentle cleanse\nSoothes irritation and dryness\nMaintains skin’s moisture balance\nFresh, non-tight feel after wash",
                'ingredients' => 'Aloe Vera Leaf Juice, Glycerin, Cocamidopropyl Betaine, Chamomile Extract, Vitamin B5',
                'how' => 'Wet face, apply a small amount, massage in circular motions, and rinse thoroughly. Use twice daily.',
                'price' => 299, 'mrp' => 375, 'discount' => 20, 'rating' => 4.7, 'reviews' => 210,
                'badge' => 'Best Seller', 'cashback' => true, 'best' => true, 'featured' => true, 'new' => false,
                'image' => 'images/products/product-facewash.jpg',
            ],
            [
                'name' => 'Tea Tree Face Toner — Clarify & Refresh',
                'category' => 'proceuticals',
                'sku' => 'IPP-TN-TT',
                'size' => '120 ml',
                'short' => 'Clarifying toner for balanced, refreshed skin.',
                'description' => 'Tea Tree Face Toner helps refine pores, control excess oil, and refresh skin after cleansing.',
                'benefits' => "Helps control excess oil\nRefines appearance of pores\nRefreshing post-cleanse feel\nSupports clearer-looking skin",
                'ingredients' => 'Tea Tree Oil, Witch Hazel, Aloe Vera, Niacinamide, Rose Water',
                'how' => 'After cleansing, apply with a cotton pad or mist onto face. Avoid eye area.',
                'price' => 349, 'mrp' => 410, 'discount' => 15, 'rating' => 4.3, 'reviews' => 76,
                'badge' => 'New Arrival', 'cashback' => false, 'best' => false, 'featured' => true, 'new' => true,
                'image' => 'images/products/product-toner.jpg',
            ],
            [
                'name' => 'Herbal Face Cream — Deep Nourish',
                'category' => 'probiome',
                'sku' => 'IPP-CR-HB',
                'size' => '50 g',
                'short' => 'Rich herbal cream for lasting moisture and glow.',
                'description' => 'A nourishing herbal face cream enriched with botanical oils and plant extracts.',
                'benefits' => "Deep, lasting hydration\nImproves softness and elasticity feel\nSupports natural skin barrier\nIdeal for day or night use",
                'ingredients' => 'Shea Butter, Almond Oil, Aloe Vera, Turmeric Extract, Vitamin E, Honey Extract',
                'how' => 'Apply a pea-sized amount on clean face and neck. Massage gently until absorbed.',
                'price' => 499, 'mrp' => 609, 'discount' => 18, 'rating' => 4.8, 'reviews' => 154,
                'badge' => 'Best Seller', 'cashback' => true, 'best' => true, 'featured' => true, 'new' => false,
                'image' => 'images/products/product-cream.jpg',
            ],
            [
                'name' => 'Salon at Home Facial Kit — Complete Ritual',
                'category' => 'facial-kit',
                'sku' => 'IPP-FK-SAH',
                'size' => 'Kit (5 steps)',
                'short' => 'Complete facial kit for spa-like care at home.',
                'description' => 'Bring salon freshness home with this complete facial kit.',
                'benefits' => "Full 5-step facial experience\nVisible glow after one session\nGentle enough for fortnightly use\nTravel-friendly kit packaging",
                'ingredients' => 'Aloe Vera, Sandalwood, Rose, Almond Oil, Multani Mitti, Vitamin E',
                'how' => 'Follow the numbered steps in the kit leaflet. Use on clean skin.',
                'price' => 899, 'mrp' => 1285, 'discount' => 30, 'rating' => 4.5, 'reviews' => 67,
                'badge' => 'New Arrival', 'cashback' => true, 'best' => false, 'featured' => true, 'new' => true,
                'image' => 'images/products/product-kit.jpg',
            ],
            [
                'name' => 'Prosculpt Firming Treatment Gel',
                'category' => 'prosculpt-treatment',
                'sku' => 'IPP-PS-GEL',
                'size' => '75 ml',
                'short' => 'Targeted firming gel for contoured skin feel.',
                'description' => 'Prosculpt Treatment Gel supports a firmer, more contoured feel with botanical actives.',
                'benefits' => "Cooling gel texture\nSupports firm, toned feel\nIdeal for facial massage\nAbsorbs without heavy residue",
                'ingredients' => 'Caffeine, Green Tea, Aloe Vera, Peptide Complex, Menthol (mild)',
                'how' => 'Apply a small amount on face/neck and massage upward for 2–3 minutes.',
                'price' => 699, 'mrp' => 899, 'discount' => 22, 'rating' => 4.2, 'reviews' => 41,
                'badge' => 'New Arrival', 'cashback' => false, 'best' => false, 'featured' => false, 'new' => true,
                'image' => 'images/products/product-cream.jpg',
            ],
            [
                'name' => 'Salon at Home Glow Mask',
                'category' => 'salon-at-home',
                'sku' => 'IPP-SL-MSK',
                'size' => '100 g',
                'short' => 'Brightening mask for instant salon-fresh glow.',
                'description' => 'A glow-boosting salon-style mask that helps revive dull skin.',
                'benefits' => "Instant fresh glow\nHelps refine skin texture\nIdeal pre-event ritual\nEasy rinse-off formula",
                'ingredients' => 'Saffron Extract, Sandalwood, Honey, Rose Petal, Kaolin Clay',
                'how' => 'Apply an even layer on clean face. Leave for 10–15 minutes. Rinse with lukewarm water.',
                'price' => 379, 'mrp' => 475, 'discount' => 20, 'rating' => 4.4, 'reviews' => 88,
                'badge' => 'Best Seller', 'cashback' => true, 'best' => true, 'featured' => false, 'new' => false,
                'image' => 'images/products/product-kit.jpg',
            ],
        ];

        foreach ($products as $i => $p) {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id' => $catMap[$p['category']] ?? null,
                    'name' => $p['name'],
                    'sku' => $p['sku'],
                    'size' => $p['size'],
                    'short_description' => $p['short'],
                    'description' => $p['description'],
                    'benefits' => $p['benefits'],
                    'ingredients' => $p['ingredients'],
                    'how_to_use' => $p['how'],
                    'price' => $p['price'],
                    'mrp' => $p['mrp'],
                    'discount' => $p['discount'],
                    'rating' => $p['rating'],
                    'reviews_count' => $p['reviews'],
                    'badge' => $p['badge'],
                    'cashback' => $p['cashback'],
                    'is_best_seller' => $p['best'],
                    'is_featured' => $p['featured'],
                    'is_new' => $p['new'],
                    'is_active' => true,
                    'amazon_url' => 'https://www.amazon.in',
                    'thumbnail' => $p['image'],
                    'stock' => 100,
                    'sort_order' => $i + 1,
                ]
            );

            if (!$product->images()->exists()) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $p['image'],
                    'alt' => $p['name'],
                    'sort_order' => 0,
                ]);
            }
        }

        Banner::updateOrCreate(['title' => 'Nourish Your Skin'], [
            'script_text' => 'Embrace Nature',
            'subtitle' => "Nature's care for healthy, glowing skin every day.",
            'button_text' => 'SHOP NOW',
            'button_link' => '/shop',
            'image' => 'images/hero-slide-1.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        Banner::updateOrCreate(['title' => 'Protect & Glow'], [
            'script_text' => 'Sun Ready',
            'subtitle' => 'SPF protection with a non-greasy, nature-inspired feel.',
            'button_text' => 'SHOP SUN CARE',
            'button_link' => '/shop?category=sunscreen',
            'image' => 'images/hero-slide-2.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        Banner::updateOrCreate(['title' => 'Cleanse. Tone. Nourish.'], [
            'script_text' => 'Daily Ritual',
            'subtitle' => 'Botanical essentials for your everyday skincare routine.',
            'button_text' => 'EXPLORE RANGE',
            'button_link' => '/shop',
            'image' => 'images/hero-slide-1.jpg',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $pages = [
            ['About Us', 'about', '<h2>Who We Are</h2><p>Ippeo is a new-age cosmetic brand dedicated to health and skin care. We create nature-inspired, safe formulations for skincare, haircare and daily wellness — because nature’s secret is Ippeo’s promise.</p><h2>Our Mission</h2><p>To make clean, effective, botanical skincare accessible for everyday Indian routines.</p>'],
            ['Why Ippeo', 'why-ippeo', '<p>Skincare that feels like nature’s care — made for healthy, glowing skin every day.</p><ul><li>Botanical First</li><li>Daily Practical</li><li>Sun Smart</li><li>Honest Value</li><li>Made for India</li></ul>'],
            ['FAQs', 'faq', '<h3>Are Ippeo products suitable for daily use?</h3><p>Yes. Our face wash, toner, cream and sunscreen are designed for everyday routines.</p><h3>Do you ship across India?</h3><p>Yes, we ship pan-India. Orders above ₹499 qualify for free shipping.</p>'],
            ['Shipping Policy', 'shipping-policy', '<p>Free shipping on orders ₹499 and above. Flat ₹49 on orders below ₹499. Delivery typically 3–7 business days.</p>'],
            ['Return & Refund Policy', 'returns', '<p>Unused products in original packaging can be returned within 7 days of delivery. Email info@ippeo.in with your order ID.</p>'],
            ['Privacy Policy', 'privacy-policy', '<p>We collect name, email, phone and shipping address to process orders. We do not sell your personal data. Contact info@ippeo.in for privacy requests.</p>'],
            ['Terms & Conditions', 'terms', '<p>By purchasing from Ippeo Essential Products you agree to our product, pricing and order terms. Governed by laws of India; jurisdiction Ahmedabad, Gujarat.</p>'],
            ['Blog', 'blog', '<p>Tips, rituals and nature-inspired guidance for glowing skin. New articles coming soon.</p>'],
            ['New Launches', 'new-launches', '<p>Explore our newest botanical essentials from the shop — filter by New Arrival badges.</p>'],
        ];

        foreach ($pages as [$title, $slug, $content]) {
            CmsPage::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => $content,
                    'meta_title' => $title . ' | Ippeo',
                    'meta_description' => strip_tags($content),
                    'is_active' => true,
                ]
            );
        }

        $settings = [
            'site_name' => 'Ippeo Essential Products',
            'tagline' => "Nature's secret; Ippeo's promise",
            'logo' => 'images/logo.png',
            'phone_1' => '+91 99999 99999',
            'phone_2' => '+91 88888 88888',
            'email' => 'info@ippeo.in',
            'address' => 'Ahmedabad, Gujarat, India',
            'instagram' => 'https://instagram.com',
            'facebook' => 'https://facebook.com',
            'copyright' => '© 2025 Ippeo Essential Products. All Rights Reserved.',
            'home_products_title' => 'Best Sunscreen Lotion For Women',
            'home_products_subtitle' => 'SPF protection against UVA & UVB damage with non-greasy formulation.',
            'home_about_title' => 'Ippeo Essential Products',
            'home_about_text' => "Ippeo is a new-age cosmetic brand dedicated to health and skin care.\nWe create nature-inspired, safe formulations for skincare, haircare and daily wellness — because nature’s secret is Ippeo’s promise.\n\nFrom sunscreen lotions to face washes, toners and salon-at-home kits, every product is crafted to nourish, protect and restore your natural glow.",
            'home_about_image' => 'images/about-woman.jpg',
            'home_inquiry_title' => "Have a Question? We're Here to Help!",
            'home_inquiry_subtitle' => 'Share your query and our team will get back to you shortly.',
            'enquiry_email' => 'info@ippeo.in',
            'free_shipping_min' => '499',
            'shipping_fee' => '49',
            'cod_enabled' => '1',
            'razorpay_enabled' => '0',
            'razorpay_mode' => 'test',
            'razorpay_key_id_test' => '',
            'razorpay_key_secret_test' => '',
            'razorpay_key_id_live' => '',
            'razorpay_key_secret_live' => '',
            'mail_mailer' => 'sendmail',
            'mail_host' => '',
            'mail_port' => '587',
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@ippeo.in',
            'mail_from_name' => 'Ippeo Essential Products',
            'order_admin_email' => 'info@ippeo.in',
            'order_email_customer' => '1',
            'order_email_admin' => '1',
            'contact_email_admin' => '1',
            'footer_company_links' => json_encode([
                ['label' => 'About Us', 'url' => '/page/about'],
                ['label' => 'Our Products', 'url' => '/shop'],
                ['label' => 'Why Ippeo', 'url' => '/page/why-ippeo'],
                ['label' => 'New Launches', 'url' => '/new-launches'],
                ['label' => 'Blog', 'url' => '/page/blog'],
                ['label' => 'Contact Us', 'url' => '/contact'],
            ]),
            'footer_care_links' => json_encode([
                ['label' => 'FAQs', 'url' => '/page/faq'],
                ['label' => 'Shipping Policy', 'url' => '/page/shipping-policy'],
                ['label' => 'Return & Refund Policy', 'url' => '/page/returns'],
                ['label' => 'Privacy Policy', 'url' => '/page/privacy-policy'],
                ['label' => 'Terms & Conditions', 'url' => '/page/terms'],
            ]),
        ];

        foreach ($settings as $key => $value) {
            $group = str_starts_with($key, 'home_')
                ? 'homepage'
                : (str_starts_with($key, 'footer_')
                    ? 'footer'
                    : (str_starts_with($key, 'razorpay_') || $key === 'cod_enabled'
                        ? 'payments'
                        : (str_starts_with($key, 'mail_') || str_contains($key, 'email') || str_starts_with($key, 'order_email') || str_starts_with($key, 'contact_email')
                            ? 'mail'
                            : 'general')));
            Setting::setValue($key, $value, $group);
        }
    }
}
