<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function edit()
    {
        $page = Page::where('name', 'home')->first();
        return view('pages.edit_homepage', compact('page'));
    }

    public function createPage()
    {
        return view('pages.create_page');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'banner'  => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('banners', 'public');
        }

        Page::updateOrCreate(
            ['name' => 'home'],
            $data
        );

        return back()->with('success', 'Homepage updated successfully.');
    }

    public function storePage(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255|unique:pages,name',
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'required|in:active,draft',
        ]);

        Page::create($data);

        return redirect()->route('admin.pages.create')->with('success', 'Page created successfully.');
    }
}
