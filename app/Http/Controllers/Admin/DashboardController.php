<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'users' => User::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::byStatus(Order::STATUS_PENDING)->count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'low_stock' => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
        ];

        $recentOrders = Order::latest()->limit(5)->get();
        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $topProducts = Product::orderBy('stock', 'asc')->where('is_active', true)->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentMessages', 'topProducts'));
    }
}
