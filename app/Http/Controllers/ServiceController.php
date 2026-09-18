<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->orderBy('id')->get();

        $breadcrumbs = [
            ['name' => __('site.nav_services'), 'url' => '/services'],
        ];

        return view('services', compact('services', 'breadcrumbs'));
    }
}
