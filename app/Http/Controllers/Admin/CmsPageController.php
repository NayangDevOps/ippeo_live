<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsPageController extends Controller
{
    public function index()
    {
        $pages = CmsPage::orderBy('title')->paginate(30);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.form', ['page' => new CmsPage()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:180',
            'slug' => 'nullable|string|max:180',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:180',
            'meta_description' => 'nullable|string|max:300',
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);
        CmsPage::create($data);
        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(CmsPage $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, CmsPage $page)
    {
        $data = $request->validate([
            'title' => 'required|string|max:180',
            'slug' => 'required|string|max:180',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:180',
            'meta_description' => 'nullable|string|max:300',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $page->update($data);
        return back()->with('success', 'Page updated.');
    }

    public function destroy(CmsPage $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }
}
