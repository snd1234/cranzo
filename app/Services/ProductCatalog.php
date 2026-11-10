<?php

namespace App\Services;

use App\Support\Slugger;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductCatalog
{
    public function create(array $input, array $files, int $adminId): int
    {
        $stored = [];

        try {
            return DB::transaction(function () use ($input, $files, $adminId, &$stored) {
                $slug = $this->resolveSlug($input['slug'] ?? null, $input['product_title']);
                $now = now();
                $productId = DB::table('product')->insertGetId([
                    'category_id' => $input['category_id'],
                    'sub_category_id' => $input['sub_category_id'],
                    'ternary_category_id' => $input['ternary_category_id'] ?: null,
                    'product_title' => $input['product_title'],
                    'slug' => $slug,
                    'description' => $this->cleanHtml($input['description'] ?? ''),
                    'product_highlights' => $this->cleanHtml($input['product_highlights'] ?? ''),
                    'sku_id' => $input['sku_id'],
                    'actual_price' => $input['actual_price'],
                    'discounted_price' => $input['discounted_price'],
                    'discount_rate' => $this->discountRate($input['actual_price'], $input['discounted_price']),
                    'length' => $input['length'] ?: null,
                    'width' => $input['width'] ?: null,
                    'height' => $input['height'] ?: null,
                    'm_unit' => $input['m_unit'] ?: null,
                    'featured_item' => $input['featured_item'],
                    'cod_available' => $input['cod_available'],
                    'status' => $input['status'],
                    'meta_title' => $input['meta_title'] ?: null,
                    'meta_keywords' => $input['meta_keywords'] ?: null,
                    'meta_description' => $input['meta_description'] ?: null,
                    'created_at' => $now,
                    'created_by' => $adminId,
                    'updated_at' => $now,
                    'update_by' => $adminId,
                ]);

                $detailId = $this->nextId('product_details', 'product_details_id');
                DB::table('product_details')->insert([
                    'product_details_id' => $detailId,
                    'product_id' => $productId,
                    'color_id' => $input['color_id'],
                    'product_quantity' => $input['quantity'],
                ]);
                $this->storeImages($productId, $detailId, $files, $stored);

                return $productId;
            });
        } catch (\Throwable $exception) {
            $this->deleteFiles($stored);
            throw $exception;
        }
    }

    public function update(int $productId, array $input, array $files, array $removeImageIds, int $adminId): void
    {
        $stored = [];
        $obsolete = [];

        try {
            DB::transaction(function () use ($productId, $input, $files, $removeImageIds, $adminId, &$stored, &$obsolete) {
                $current = DB::table('product')->where('id', $productId)->lockForUpdate()->first();
                if (! $current) {
                    throw ValidationException::withMessages(['product' => 'Product not found.']);
                }

                $slug = $this->resolveSlug($input['slug'] ?? null, $input['product_title'], $productId);
                if ($current->slug && $current->slug !== $slug) {
                    DB::table('product_slug_redirects')->updateOrInsert(
                        ['slug' => $current->slug],
                        ['product_id' => $productId, 'created_at' => now()]
                    );
                }
                DB::table('product_slug_redirects')->where('slug', $slug)->where('product_id', $productId)->delete();

                DB::table('product')->where('id', $productId)->update([
                    'category_id' => $input['category_id'],
                    'sub_category_id' => $input['sub_category_id'],
                    'ternary_category_id' => $input['ternary_category_id'] ?: null,
                    'product_title' => $input['product_title'],
                    'slug' => $slug,
                    'description' => $this->cleanHtml($input['description'] ?? ''),
                    'product_highlights' => $this->cleanHtml($input['product_highlights'] ?? ''),
                    'sku_id' => $input['sku_id'],
                    'actual_price' => $input['actual_price'],
                    'discounted_price' => $input['discounted_price'],
                    'discount_rate' => $this->discountRate($input['actual_price'], $input['discounted_price']),
                    'length' => $input['length'] ?: null,
                    'width' => $input['width'] ?: null,
                    'height' => $input['height'] ?: null,
                    'm_unit' => $input['m_unit'] ?: null,
                    'featured_item' => $input['featured_item'],
                    'cod_available' => $input['cod_available'],
                    'status' => $input['status'],
                    'meta_title' => $input['meta_title'] ?: null,
                    'meta_keywords' => $input['meta_keywords'] ?: null,
                    'meta_description' => $input['meta_description'] ?: null,
                    'updated_at' => now(),
                    'update_by' => $adminId,
                ]);

                foreach ($input['quantities'] ?? [] as $detailId => $quantity) {
                    DB::table('product_details')
                        ->where('product_details_id', (int) $detailId)
                        ->where('product_id', $productId)
                        ->update(['product_quantity' => (int) $quantity]);
                }

                if (! empty($input['color_id']) && $input['quantity'] !== null && $input['quantity'] !== '') {
                    $existingDetail = DB::table('product_details')
                        ->where('product_id', $productId)
                        ->where('color_id', $input['color_id'])
                        ->first();
                    if ($existingDetail) {
                        DB::table('product_details')
                            ->where('product_details_id', $existingDetail->product_details_id)
                            ->update(['product_quantity' => (int) $input['quantity']]);
                    } else {
                        DB::table('product_details')->insert([
                            'product_details_id' => $this->nextId('product_details', 'product_details_id'),
                            'product_id' => $productId,
                            'color_id' => $input['color_id'],
                            'product_quantity' => (int) $input['quantity'],
                        ]);
                    }
                }

                if ($removeImageIds) {
                    $rows = DB::table('product_images')
                        ->where('product_id', $productId)
                        ->whereIn('image_id', $removeImageIds)
                        ->get();
                    foreach ($rows as $row) {
                        $obsolete[] = $this->imagePath($row->image);
                        DB::table('product_images')->where('image_id', $row->image_id)->where('product_id', $productId)->delete();
                    }
                }

                $detailId = (int) DB::table('product_details')->where('product_id', $productId)->orderBy('product_details_id')->value('product_details_id');
                if ($files && ! $detailId) {
                    throw ValidationException::withMessages(['images' => 'Add a color and stock quantity before uploading images.']);
                }
                $this->storeImages($productId, $detailId, $files, $stored);
            });
        } catch (\Throwable $exception) {
            $this->deleteFiles($stored);
            throw $exception;
        }

        $this->deleteFiles($obsolete);
    }

    private function resolveSlug(?string $slug, string $title, ?int $ignoreId = null): string
    {
        $source = trim((string) $slug) !== '' ? $slug : $title;

        return Slugger::unique('product', $source, $ignoreId, 'product-'.($ignoreId ?: time()));
    }

    private function discountRate(float|string $regular, float|string $selling): string
    {
        $regular = (float) $regular;
        $selling = (float) $selling;
        if ($regular <= 0 || $selling >= $regular) {
            return '0.00';
        }

        return number_format((($regular - $selling) / $regular) * 100, 2, '.', '');
    }

    private function cleanHtml(string $html): string
    {
        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $html) ?? '';
        $html = strip_tags($html, '<p><br><ul><ol><li><strong><em><h2><h3>');
        $html = preg_replace('/<(\/?)\s*(p|br|ul|ol|li|strong|em|h2|h3)\b[^>]*>/i', '<$1$2>', $html) ?? '';

        return trim($html);
    }

    private function storeImages(int $productId, int $detailId, array $files, array &$stored): void
    {
        $directory = public_path('uploads/products');
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $nextId = $this->nextId('product_images', 'image_id');
        foreach (array_values($files) as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }
            $extension = strtolower($file->extension() ?: 'jpg');
            $filename = 'product-'.time().'_'.$index.'_'.Str::lower(Str::random(4)).'.'.$extension;
            $file->move($directory, $filename);
            $stored[] = $directory.DIRECTORY_SEPARATOR.$filename;
            DB::table('product_images')->insert([
                'image_id' => $nextId++,
                'product_details_id' => $detailId,
                'product_id' => $productId,
                'thumbnail' => $filename,
                'image' => $filename,
                'created_date' => now(),
            ]);
        }
    }

    private function nextId(string $table, string $column): int
    {
        $current = DB::table($table)->orderByDesc($column)->lockForUpdate()->value($column);

        return ((int) $current) + 1;
    }

    private function imagePath(?string $filename): ?string
    {
        if (! $filename) {
            return null;
        }

        return public_path('uploads/products/'.basename($filename));
    }

    private function deleteFiles(array $paths): void
    {
        $root = realpath(public_path('uploads/products'));
        foreach ($paths as $path) {
            $real = $path && is_file($path) ? realpath($path) : false;
            if ($root && $real && str_starts_with($real, $root)) {
                unlink($real);
            }
        }
    }
}
