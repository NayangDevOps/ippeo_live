<?php

namespace App\Http\Controllers;

use App\Mail\EnquirySubmitted;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Services\MailConfigService;
use App\Services\OrderMailService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function home()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();
        $settings = Setting::many([
            'home_products_title' => 'Best Sunscreen Lotion For Women',
            'home_products_subtitle' => '',
            'home_about_title' => 'Ippeo Essential Products',
            'home_about_text' => '',
            'home_about_image' => 'images/about-woman.jpg',
            'home_inquiry_title' => "Have a Question? We're Here to Help!",
            'home_inquiry_subtitle' => '',
        ]);

        return view('shop.home', compact('banners', 'products', 'settings'));
    }

    public function shop(Request $request)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $query = Product::with('category')->where('is_active', true);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            });
        }
        if ($request->boolean('new')) {
            $query->where('is_new', true);
        }

        $products = $query->orderBy('sort_order')->paginate(12)->withQueryString();
        $activeCategory = $request->category;

        return view('shop.index', compact('products', 'categories', 'activeCategory'));
    }

    public function product(string $slug)
    {
        $product = Product::with(['images', 'videos', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $related = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->take(4)
            ->get();

        return view('shop.product', compact('product', 'related'));
    }

    public function categories()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('shop.categories', compact('categories'));
    }

    public function newLaunches()
    {
        $products = Product::where('is_active', true)->where('is_new', true)->orderBy('sort_order')->paginate(12);
        return view('shop.new-launches', compact('products'));
    }

    public function page(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('pages.show', compact('page'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function cart()
    {
        return view('cart.index');
    }

    public function checkout(RazorpayService $razorpay)
    {
        $codEnabled = Setting::getValue('cod_enabled', '1') === '1';
        $razorpayEnabled = $razorpay->isEnabled();

        return view('checkout.index', [
            'codEnabled' => $codEnabled,
            'razorpayEnabled' => $razorpayEnabled,
            'razorpayKey' => $razorpayEnabled ? $razorpay->keyId() : '',
            'freeShippingMin' => (float) Setting::getValue('free_shipping_min', 499),
            'shippingFee' => (float) Setting::getValue('shipping_fee', 49),
        ]);
    }

    public function placeOrder(Request $request, RazorpayService $razorpay, OrderMailService $orderMail)
    {
        $allowed = [];
        if (Setting::getValue('cod_enabled', '1') === '1') {
            $allowed[] = 'cod';
        }
        if ($razorpay->isEnabled()) {
            $allowed[] = 'razorpay';
        }
        if (! $allowed) {
            return back()->with('error', 'No payment methods are currently available. Please contact support.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:20',
            'payment_method' => 'required|in:' . implode(',', $allowed),
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|integer|min:1|max:20',
        ]);

        $built = $this->buildOrderLines($data['items']);
        if (! $built['lines']) {
            return $this->orderResponse($request, null, 'Your cart is empty or invalid.', 422);
        }

        $order = DB::transaction(function () use ($data, $built) {
            return $this->createOrderRecord($data, $built, [
                'payment_status' => $data['payment_method'] === 'cod' ? 'pending' : 'awaiting_payment',
                'status' => 'pending',
            ]);
        });

        if ($data['payment_method'] === 'cod') {
            $orderMail->sendConfirmation($order);

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'redirect' => route('order.success', $order->order_number),
                ]);
            }

            return redirect()->route('order.success', $order->order_number);
        }

        try {
            $rpOrder = $razorpay->createOrder(
                (int) round(((float) $order->total) * 100),
                $order->order_number,
                [
                    'order_number' => $order->order_number,
                    'customer_email' => $order->customer_email,
                ]
            );

            $order->update([
                'razorpay_order_id' => $rpOrder['id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            report($e);
            $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);

            return $this->orderResponse($request, null, 'Unable to start online payment. Please try again or use COD.', 502);
        }

        $payload = [
            'ok' => true,
            'razorpay' => true,
            'key' => $razorpay->keyId(),
            'amount' => (int) round(((float) $order->total) * 100),
            'currency' => 'INR',
            'order_id' => $order->razorpay_order_id,
            'order_number' => $order->order_number,
            'name' => Setting::getValue('site_name', 'Ippeo Essential Products'),
            'description' => 'Order #' . $order->order_number,
            'prefill' => [
                'name' => $order->customer_name,
                'email' => $order->customer_email,
                'contact' => $order->customer_phone,
            ],
            'theme' => ['color' => '#226b2c'],
            'verify_url' => route('checkout.razorpay.verify'),
            'success_url' => route('order.success', $order->order_number),
        ];

        if ($request->expectsJson()) {
            return response()->json($payload);
        }

        return view('checkout.razorpay', ['pay' => $payload]);
    }

    public function verifyRazorpay(Request $request, RazorpayService $razorpay, OrderMailService $orderMail)
    {
        $data = $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'order_number' => 'required|string',
        ]);

        $order = Order::where('order_number', $data['order_number'])->firstOrFail();

        if ($order->payment_status === 'paid') {
            return response()->json([
                'ok' => true,
                'redirect' => route('order.success', $order->order_number),
            ]);
        }

        if ($order->razorpay_order_id && $order->razorpay_order_id !== $data['razorpay_order_id']) {
            return response()->json(['ok' => false, 'message' => 'Order mismatch.'], 422);
        }

        if (! $razorpay->verifyPaymentSignature(
            $data['razorpay_order_id'],
            $data['razorpay_payment_id'],
            $data['razorpay_signature']
        )) {
            $order->update(['payment_status' => 'failed']);

            return response()->json(['ok' => false, 'message' => 'Payment verification failed.'], 422);
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature' => $data['razorpay_signature'],
            'paid_at' => now(),
        ]);

        $orderMail->sendConfirmation($order->fresh('items'));

        return response()->json([
            'ok' => true,
            'redirect' => route('order.success', $order->order_number),
        ]);
    }

    public function orderSuccess(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();
        return view('checkout.success', compact('order'));
    }

    public function enquiry(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'message' => 'required|string|max:5000',
        ]);

        $enquiry = Enquiry::create([
            ...$data,
            'source' => $request->input('source', 'website'),
        ]);

        if (Setting::getValue('contact_email_admin', '1') === '1') {
            $to = MailConfigService::adminEnquiryEmail();
            try {
                MailConfigService::applyFromSettings();
                Mail::to($to)->send(new EnquirySubmitted($enquiry));
                $enquiry->update(['emailed' => true]);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Thank you! Your inquiry has been sent.']);
        }

        return back()->with('success', 'Thank you! Your inquiry has been sent to our team.');
    }

    public function newsletter(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:150']);
        Newsletter::firstOrCreate(['email' => $data['email']]);
        return back()->with('success', 'Subscribed successfully!');
    }

    private function buildOrderLines(array $items): array
    {
        $productIds = collect($items)->pluck('id');
        $products = Product::whereIn('id', $productIds)->where('is_active', true)->get()->keyBy('id');

        $subtotal = 0;
        $lines = [];
        foreach ($items as $item) {
            $product = $products->get($item['id']);
            if (! $product) {
                continue;
            }
            $qty = (int) $item['qty'];
            $lineTotal = $product->price * $qty;
            $subtotal += $lineTotal;
            $lines[] = [
                'product' => $product,
                'qty' => $qty,
                'total' => $lineTotal,
            ];
        }

        $freeMin = (float) Setting::getValue('free_shipping_min', 499);
        $shipFee = (float) Setting::getValue('shipping_fee', 49);
        $shipping = $subtotal >= $freeMin ? 0 : $shipFee;

        return [
            'lines' => $lines,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    private function createOrderRecord(array $data, array $built, array $extras = []): Order
    {
        $customer = Customer::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
            ]
        );

        $customer->fill([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
        ])->save();

        $order = Order::create(array_merge([
            'order_number' => 'IPP' . now()->format('ymd') . strtoupper(Str::random(4)),
            'customer_id' => $customer->id,
            'customer_name' => $data['name'],
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'],
            'shipping_address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'payment_method' => $data['payment_method'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'subtotal' => $built['subtotal'],
            'shipping' => $built['shipping'],
            'total' => $built['total'],
        ], $extras));

        foreach ($built['lines'] as $line) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $line['product']->id,
                'product_name' => $line['product']->name,
                'price' => $line['product']->price,
                'qty' => $line['qty'],
                'total' => $line['total'],
            ]);
        }

        return $order->load('items');
    }

    private function orderResponse(Request $request, ?Order $order, string $message, int $status = 400)
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => false, 'message' => $message], $status);
        }

        return back()->with('error', $message);
    }
}
