<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you would typically send an email or save to database
        // Mail::to(config('mail.admin'))->send(new ContactMessage($request->all()));

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
}
