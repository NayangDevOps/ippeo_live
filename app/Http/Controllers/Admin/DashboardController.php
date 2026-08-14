<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function loginForm()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (!Auth::user()->is_admin) {
                Auth::logout();
                return back()->with('error', 'Unauthorized admin access.');
            }
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function index()
    {
        return view('admin.dashboard.index', [
            'products' => Product::count(),
            'orders' => Order::count(),
            'customers' => Customer::count(),
            'enquiries' => Enquiry::count(),
            'recentOrders' => Order::latest()->take(8)->get(),
            'recentEnquiries' => Enquiry::latest()->take(5)->get(),
        ]);
    }
}
