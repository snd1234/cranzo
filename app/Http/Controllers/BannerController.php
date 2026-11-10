<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::query()
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', (int) $request->input('status')))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner(['status' => 1, 'sort_order' => 0])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateBanner($request);
        $data['desktop_image'] = $this->storeImage($request->file('desktop_image'));
        $data['mobile_image'] = $request->hasFile('mobile_image') ? $this->storeImage($request->file('mobile_image')) : null;
        $banner = Banner::create($data);

        return redirect()->route('banners.show', $banner)->with('success', 'Banner added.');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validateBanner($request);
        $previousDesktop = $banner->desktop_image;
        $previousMobile = $banner->mobile_image;

        if ($request->hasFile('desktop_image')) {
            $data['desktop_image'] = $this->storeImage($request->file('desktop_image'));
        }
        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = $this->storeImage($request->file('mobile_image'));
        }

        $banner->update($data);

        if ($request->hasFile('desktop_image')) {
            $this->deleteImage($previousDesktop);
        }
        if ($request->hasFile('mobile_image')) {
            $this->deleteImage($previousMobile);
        }

        return redirect()->route('banners.show', $banner)->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        $desktop = $banner->desktop_image;
        $mobile = $banner->mobile_image;
        $banner->delete();
        $this->deleteImage($desktop);
        $this->deleteImage($mobile);

        return redirect()->route('banners.index')->with('success', 'Banner deleted.');
    }

    public function status(Banner $banner)
    {
        $banner->update(['status' => $banner->status ? 0 : 1]);

        return back()->with('success', $banner->status ? 'Banner activated.' : 'Banner deactivated.');
    }

    private function validateBanner(Request $request): array
    {
        $form = BannerRequest::createFrom($request);
        $form->setContainer(app())->setRedirector(app('redirect'));
        $form->validateResolved();

        return collect($form->validated())->except(['desktop_image', 'mobile_image'])->all();
    }

    private function storeImage(UploadedFile $file): string
    {
        $directory = public_path('uploads/banners');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $filename = 'banner-'.time().'-'.Str::lower(Str::random(6)).'.'.strtolower($file->extension() ?: 'jpg');
        $file->move($directory, $filename);

        return $filename;
    }

    private function deleteImage(?string $filename): void
    {
        if (! $filename) {
            return;
        }
        $root = realpath(public_path('uploads/banners'));
        $path = public_path('uploads/banners/'.basename($filename));
        $real = is_file($path) ? realpath($path) : false;
        if ($root && $real && str_starts_with($real, $root)) {
            unlink($real);
        }
    }
}
