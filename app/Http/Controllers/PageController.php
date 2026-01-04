<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Page;

class PageController extends Controller
{
    //
    public function index()
    {
        // Use the query builder to avoid a SoftDeletes global scope requiring a deleted_at column
        $pages = DB::table('pages')->get();
        return view('admin.pages', compact('pages'));
     
    }
    public function showAddForm()
    {
        //echo "Show Add Page Form";die;
        // Logic to show form to add new content
        return view('admin.add_page');
    }
    public function store(Request $request)
    {
        if($request->isMethod('post')){
            // echo "<pre>";print_r($request->all());die;

            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|alpha_dash|max:255|unique:pages,slug',
            ]);

            DB::table('pages')->insert([
                'title' => $request->title,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'slug' => $request->slug,
                'description' => $request->description,
                'body' => $request->content,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/admin/page-management')->with('success', 'Content added successfully.');
       
        }
        // Logic to store new content item
    }
    public function edit($id)
    {
        $page = DB::table('pages')->where('id', $id)->first();

        if (!$page) {
            return redirect('/admin/page-management')->with('error', 'Page not found.');
        }

        return view('admin.edit_page', compact('page'));
    }
    public function update(Request $request, $id)
    {
    }
    public function show($id)
    {
    }
    public function destroy($id)
    {
    }
    


}


