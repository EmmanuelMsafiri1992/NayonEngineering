<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function account(): View
    {
        return view('pages.account');
    }

    public function checkout(): View
    {
        return view('pages.checkout');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function faqs(): View
    {
        return view('pages.faqs');
    }

    public function careers(): View
    {
        return view('pages.careers');
    }

    public function trackOrder(): View
    {
        return view('pages.track-order');
    }
}
