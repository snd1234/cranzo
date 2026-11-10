<?php

use App\Support\Slugger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addSeoColumns('category', 'name', 'image');
        $this->addSeoColumns('sub_category', 'name', 'image');
        $this->addSeoColumns('product', 'product_title', 'product_title');

        $this->fillSlugs('category', 'name', 'category');
        $this->fillSlugs('sub_category', 'name', 'subcategory');
        $this->fillSlugs('product', 'product_title', 'product');

        $this->addUniqueSlug('category', 'category_slug_unique');
        $this->addUniqueSlug('sub_category', 'sub_category_slug_unique');
        $this->addUniqueSlug('product', 'product_slug_unique');
    }

    public function down(): void
    {
        foreach (['category', 'sub_category', 'product'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                foreach (['slug', 'meta_title', 'meta_keywords', 'meta_description'] as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        $blueprint->dropColumn($column);
                    }
                }
            });
        }
    }

    private function addSeoColumns(string $table, string $sourceColumn, string $after): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $sourceColumn) || Schema::hasColumn($table, 'slug')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($after) {
            $blueprint->string('slug', 191)->nullable()->after($after);
            $blueprint->string('meta_title')->nullable();
            $blueprint->string('meta_keywords')->nullable();
            $blueprint->text('meta_description')->nullable();
        });
    }

    private function fillSlugs(string $table, string $sourceColumn, string $fallbackPrefix): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'slug')) {
            return;
        }

        DB::table($table)->whereNull('slug')->orderBy('id')->select('id', $sourceColumn)->chunkById(100, function ($rows) use ($table, $sourceColumn, $fallbackPrefix) {
            foreach ($rows as $row) {
                DB::table($table)->where('id', $row->id)->update([
                    'slug' => Slugger::unique($table, (string) $row->{$sourceColumn}, (int) $row->id, $fallbackPrefix.'-'.$row->id),
                ]);
            }
        });
    }

    private function addUniqueSlug(string $table, string $index): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'slug')) {
            return;
        }

        $exists = collect(Schema::getIndexes($table))->contains(fn ($item) => ($item['name'] ?? '') === $index);
        if ($exists) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($index) {
            $blueprint->unique('slug', $index);
        });
    }
};
