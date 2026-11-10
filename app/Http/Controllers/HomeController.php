<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Support\CataloguePath;
use App\Support\CmsPages;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeController extends BaseController
{
    public function index()
    {
        $featured = DB::table('product')
            ->where('status', 1)
            ->where('featured_item', 1)
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $this->attachProductImages($featured);

        $banners = Schema::hasTable('banners')
            ? Banner::query()->where('status', 1)->orderBy('sort_order')->orderBy('id')->limit(5)->get()
            : collect();

        return view('index', compact('featured', 'banners'));
    }

    public function aboutUs()
    {
        return $this->page('about-us');
    }

    public function ourPartners()
    {
        $partners = [];

        if (Schema::hasTable('partners')) {
            $partners = DB::table('partners')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->get();
        }

        return view('our_partners', compact('partners'));
    }

    public function events()
    {
        $events = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 4)
            ->orderByDesc('created_at')
            ->get();

        return view('events', compact('events'));
    }

    public function newsRoom()
    {
        $latestnews = DB::table('blogs')
            ->where('status', 1)
            ->where('type', 2)
            ->orderByDesc('created_at')
            ->get();

        return view('news_room', compact('latestnews'));
    }

    public function contactUs()
    {
        return $this->page('contact-us');
    }

    public function page(string $slug)
    {
        if (request()->routeIs('cms.page') && in_array($slug, CmsPages::FIXED, true)) {
            return redirect(CmsPages::url($slug), 301);
        }

        $page = CmsPage::query()->where('slug', $slug)->where('status', 1)->firstOrFail();
        Seo::forCms($page);

        if ($slug === 'faq') {
            $faqs = Faq::query()->where('status', 1)->orderBy('sort_order')->orderBy('id')->get();

            return view('faq', compact('page', 'faqs'));
        }

        if ($slug === 'contact-us') {
            return view('contact_us', compact('page'));
        }

        return view('cms_page', compact('page'));
    }

    public function submitContact(Request $request)
    {
        if ($request->filled('company_website')) {
            return back()->with('success', 'Thank you. We have received your message.');
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $row = [
            'name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'message' => $data['message'],
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('contact_us', 'subject')) {
            $row['subject'] = $data['subject'] ?? null;
        }

        DB::table('contact_us')->insert($row);

        $admin = config('mail.contact_notify');
        if ($admin) {
            try {
                Mail::raw($data['full_name'].' <'.$data['email'].">\n".($data['subject'] ?? '')."\n".$data['message'], function ($message) use ($admin) {
                    $message->to($admin)->subject('New Cranzo contact message');
                });
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return back()->with('success', 'Thank you. We have received your message.');
    }

    public function products(Request $request)
    {
        $query = DB::table('product')->where('status', 1);

        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('product_title', 'like', $term)
                    ->orWhere('sku_id', 'like', $term);
            });
        }

        $subcategory = null;
        if ($request->filled('category')) {
            $query->where('category_id', $this->catalogueId('category', (string) $request->input('category')));
        }

        if ($request->filled('subcategory')) {
            $subcategoryId = $this->catalogueId('sub_category', (string) $request->input('subcategory'));
            $query->where('sub_category_id', $subcategoryId);
            $subcategory = DB::table('sub_category')->where('id', $subcategoryId)->first();
        }

        $sort = $request->input('sort', 'name');
        if ($sort === 'price_asc') {
            $query->orderBy('discounted_price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('discounted_price');
        } elseif ($sort === 'newest') {
            $query->orderByDesc('id');
        } else {
            $query->orderBy('product_title');
        }

        $products = $query->paginate(12)->withQueryString();
        $this->attachProductImages(collect($products->items()));

        if ($subcategory) {
            Seo::share([
                'title' => $subcategory->meta_title ?: $subcategory->name.' | Cranzo',
                'description' => $subcategory->meta_description ?: $subcategory->name.' from Cranzo.',
                'keywords' => $subcategory->meta_keywords ?: '',
                'canonical' => CataloguePath::subcategory($subcategory),
            ]);
        } elseif ($request->filled('q')) {
            Seo::share([
                'title' => 'Search: '.$request->input('q').' | Cranzo',
                'robots' => 'noindex,follow',
            ]);
        }

        return view('products', compact('products'));
    }

    public function productDetail($slug)
    {
        $product = DB::table('product')
            ->where('status', 1)
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug);
                if (ctype_digit((string) $slug)) {
                    $query->orWhere('id', (int) $slug);
                } else {
                    $query->orWhere('product_title', $slug);
                }
            })
            ->first();

        if (! $product) {
            $redirect = DB::table('product_slug_redirects')->where('slug', $slug)->first();
            $product = $redirect
                ? DB::table('product')->where('id', $redirect->product_id)->where('status', 1)->first()
                : null;
            if (! $product || ! $product->slug) {
                abort(404);
            }

            return redirect(CataloguePath::product($product), 301);
        }

        if (ctype_digit((string) $slug) && $product->slug && $product->slug !== $slug) {
            return redirect(CataloguePath::product($product), 301);
        }

        Seo::forProduct($product);

        $productImages = DB::table('product_images')
            ->where('product_id', $product->id)
            ->get();

        $productCatalogs = DB::table('product_images')
            ->where('product_id', $product->id)
            ->get();

        $related = DB::table('product')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(4)
            ->get();
        $this->attachProductImages($related);

        $stock = (int) DB::table('product_details')->where('product_id', $product->id)->sum('product_quantity');

        return view('product_detail', compact('product', 'productImages', 'productCatalogs', 'related', 'stock'));
    }

    public function ourSolutionDetail($slug)
    {
        $category = DB::table('category')
            ->where('status', 1)
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug);
                if (ctype_digit((string) $slug)) {
                    $query->orWhere('id', (int) $slug);
                } else {
                    $query->orWhere('name', $slug);
                }
            })
            ->first();

        if (! $category) {
            abort(404);
        }

        if (ctype_digit((string) $slug) && $category->slug && $category->slug !== $slug) {
            return redirect(CataloguePath::category($category), 301);
        }

        Seo::forCategory($category);

        $products = DB::table('product')
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();

        $this->attachProductImages($products);

        $subcategories = DB::table('sub_category')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('our_solution_detail', compact('category', 'products', 'subcategories'));
    }

    public function commonDetailPage()
    {
        return view('common_detail_page');
    }

    public function blogDetail($slug)
    {
        $blog = DB::table('blogs')
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (! $blog) {
            abort(404);
        }

        $relatedBlogs = DB::table('blogs')
            ->where('status', 1)
            ->where('id', '!=', $blog->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('blog_detail', compact('blog', 'relatedBlogs'));
    }

    public function sitemap()
    {
        $categories = DB::table('category')->where('status', 1)->whereNotNull('slug')->get(['slug', 'updated_at']);
        $subcategories = DB::table('sub_category')->where('status', 1)->whereNotNull('slug')->get(['slug', 'updated_at']);
        $products = DB::table('product')->where('status', 1)->whereNotNull('slug')->get(['slug', 'updated_at']);
        $pages = Schema::hasTable('cms_pages')
            ? DB::table('cms_pages')->where('status', 1)->get(['slug', 'updated_at'])
            : collect();

        return response()->view('sitemap', compact('categories', 'subcategories', 'products', 'pages'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /system-auth',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Disallow: /newsletter',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }

    private function catalogueId(string $table, string $value): int
    {
        if (ctype_digit($value)) {
            return (int) $value;
        }

        return (int) DB::table($table)->where('slug', $value)->value('id');
    }
}
