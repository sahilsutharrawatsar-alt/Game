<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SportController extends Controller
{
    public function index()
    {
        return view('admin.sports.index', ['sports' => Sport::orderBy('name')->paginate(20)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'max:120'], 'icon' => ['nullable', 'max:40'], 'description' => ['nullable']]);
        Sport::create($data + ['slug' => Str::slug($data['name']), 'is_active' => true]);

        return back()->with('status', 'Sport added.');
    }

    public function update(Request $request, Sport $sport)
    {
        $data = $request->validate(['name' => ['required', 'max:120'], 'icon' => ['nullable', 'max:40'], 'description' => ['nullable'], 'is_active' => ['nullable', 'boolean']]);
        $sport->update($data + ['slug' => Str::slug($data['name']), 'is_active' => $request->boolean('is_active')]);

        return back()->with('status', 'Sport updated.');
    }
}
