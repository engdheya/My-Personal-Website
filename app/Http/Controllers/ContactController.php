<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // Honeypot spam protection
        if ($request->filled('_gotcha')) {
            return back()->with('success', 'Your message has been sent successfully.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        $validated['ip_address'] = $request->ip();
        Message::create($validated);

        return back()->with('success', 'Thank you! Your message has been sent successfully.');
    }
}
