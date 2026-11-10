<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Support\HtmlCleaner;
use App\Support\Slugger;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CmsPageController extends Controller
{
    private array $reserved = [
        'products', 'cart', 'checkout', 'home', 'page', 'order', 'blog', 'news',
        'our-solutions', 'our-partners', 'events', 'news-room', 'newsletter',
        'sitemap', 'robots', 'login', 'register',
    ];

    public function index(Request $request)
    {
        $pages = CmsPage::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', $term)->orWhere('slug', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', (int) $request->input('status')))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.cms.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.form', ['page' => new CmsPage([
            'status' => 1,
            'show_in_footer' => 1,
            'footer_group' => 'information',
            'footer_order' => 0,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'));
        }
        $page = CmsPage::create($data);

        return redirect()->route('cms.show', $page)->with('success', 'Page added.');
    }

    public function show(CmsPage $page)
    {
        return view('admin.cms.show', compact('page'));
    }

    public function edit(CmsPage $page)
    {
        return view('admin.cms.form', compact('page'));
    }

    public function update(Request $request, CmsPage $page)
    {
        $data = $this->validated($request, $page);
        $previous = $page->image;
        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'));
        }
        $page->update($data);
        if ($request->hasFile('image')) {
            $this->deleteImage($previous);
        }

        return redirect()->route('cms.show', $page)->with('success', 'Page updated.');
    }

    public function destroy(CmsPage $page)
    {
        $image = $page->image;
        $page->delete();
        $this->deleteImage($image);

        return redirect()->route('cms.index')->with('success', 'Page deleted.');
    }

    public function status(CmsPage $page)
    {
        $page->update(['status' => $page->status ? 0 : 1]);

        return back()->with('success', $page->status ? 'Page activated.' : 'Page deactivated.');
    }

    private function validated(Request $request, ?CmsPage $page = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:191', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('cms_pages', 'slug')->ignore($page?->id), Rule::notIn($this->reserved)],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:0,1'],
            'show_in_footer' => ['required', 'in:0,1'],
            'footer_group' => ['required', 'in:support,information'],
            'footer_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['slug'] = $data['slug'] ?: Slugger::unique('cms_pages', $data['title'], $page?->id, 'page');
        if (in_array($data['slug'], $this->reserved, true)) {
            $data['slug'] = Slugger::unique('cms_pages', $data['slug'].'-page', $page?->id, 'page');
        }
        $data['content'] = HtmlCleaner::clean($data['content']);
        if ($data['content'] === '') {
            throw ValidationException::withMessages(['content' => 'Add page content.']);
        }
        unset($data['image']);

        return $data;
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('uploads/cms');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $filename = 'cms-'.time().'-'.Str::lower(Str::random(6)).'.'.strtolower($file->extension() ?: 'jpg');
        $file->move($directory, $filename);

        return $filename;
    }

    private function deleteImage(?string $filename): void
    {
        if (! $filename) {
            return;
        }
        $path = public_path('uploads/cms/'.basename($filename));
        if (is_file($path)) {
            unlink($path);
        }
    }
}
