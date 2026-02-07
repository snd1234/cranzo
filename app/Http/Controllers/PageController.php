<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Page;
use App\Models\SeoContent; // Ensure this class exists in the specified namespace or create it if missing

class PageController extends Controller
{
    public function index()
    {
        $pages = DB::table('pages')->get();
        return view('admin.pages', compact('pages'));
    }

    public function showAddForm()
    {
        return view('admin.add_page');
    }

    /**
     * CKEditor image upload
     */
    public function uploadEditorImage(Request $request)
    {
        if ($request->hasFile('upload')) {

            $request->validate([
                'upload' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $file = $request->file('upload');
            $fileName = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/pages', $fileName, 'public');
            return response()->json(['url' => asset('storage/' . $path)]);
        }

        return response()->json(['error' => 'Upload failed'], 400);
    }

    /**
     * Store page + SEO
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|max:255|unique:pages,slug',
            'description' => 'required',
            'content' => 'required',
        ]);

        // Save SEO
        $seoContent = SeoContent::addOrUpdateSeoContent([
            'page_slug' => $request->slug,
            'meta_title' => $request->title,
            'meta_description' => $request->description,
            'meta_keywords' => $request->meta_keywords ?? '',
        ]);


        // Save Page
        DB::table('pages')->insert([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'body' => $request->content, // CKEditor HTML with <img>
            'status' => $request->status,
            'seo_id' => $seoContent->id,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/page-management')->with('success', 'Page created successfully.');
    }

    
    public function edit($id)
    {
        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) {
            return redirect('/admin/page-management')->with('error', 'Page not found.');
        }
        $seoContent = SeoContent::find($page->seo_id);
        //echo "<pre>"; print_r($seoContent); echo "</pre>"; die;
        return view('admin.edit_page', compact('page', 'seoContent'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|max:255|unique:pages,slug,' . $id,
            'description' => 'required',
            'content' => 'required',
        ]);

        $page = DB::table('pages')->where('id', $id)->first();
        if (!$page) {
            return redirect('/admin/page-management')->with('error', 'Page not found.');
        }

        // Update SEO
        $seoContent = SeoContent::addOrUpdateSeoContent([
            'id' => $page->seo_id,
            'page_slug' => $request->slug,
            'meta_title' => $request->title,
            'meta_description' => $request->meta_description ?? '',
            'meta_keywords' => $request->meta_keywords ?? '',
        ]);

        // Update Page
        DB::table('pages')->where('id', $id)->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'body' => $request->content, // CKEditor HTML with <img>
            'status' => $request->status,
            'seo_id' => $seoContent->id,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/page-management')->with('success', 'Page updated successfully.');
    }



}


