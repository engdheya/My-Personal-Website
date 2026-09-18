<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show(Request $request)
    {
        $breadcrumbs = [
            ['name' => __('site.nav_contact'), 'url' => '/contact'],
        ];

        // Deep link from the services page: /contact?service=Web Development
        $prefillSubject = $request->query('service')
            ? 'Inquiry regarding: '.$request->query('service')
            : '';

        return view('contact', compact('breadcrumbs', 'prefillSubject'));
    }

    public function submit(Request $request)
    {
        // Honeypot spam trap: bots fill every field, humans never see it.
        if ($request->filled('_gotcha')) {
            report(new \RuntimeException('Spam submission prevented via honeypot.'));

            return back()->with('success', __('site.contact_success'));
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $validated['status'] = 'unread';
        $validated['ip_address'] = $request->ip();

        Message::create($validated);

        return redirect()
            ->to(url('contact'))
            ->with('success', __('site.contact_success'));
    }
}
