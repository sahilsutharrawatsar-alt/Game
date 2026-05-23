<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageBanner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banners.index', [
            'banners' => HomepageBanner::orderBy('sort_order')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'max:160'],
            'subtitle' => ['nullable', 'max:255'],
            'image' => ['required', 'url'],
            'cta_label' => ['nullable', 'max:80'],
            'cta_url' => ['nullable', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        HomepageBanner::create($data + ['is_active' => true]);

        return back()->with('status', 'Banner published.');
    }

    public function update(Request $request, HomepageBanner $banner)
    {
        $data = $request->validate(['is_active' => ['nullable', 'boolean']]);
        $banner->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('status', 'Banner updated.');
    }
}
