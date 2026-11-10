<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Slugger
{
    public static function make(string $value, string $fallback): string
    {
        $slug = Str::slug($value);

        return $slug !== '' ? $slug : $fallback;
    }

    public static function unique(string $table, string $value, ?int $ignoreId = null, string $fallback = 'item'): string
    {
        $base = substr(self::make($value, $fallback), 0, 180);
        $slug = $base;
        $suffix = 2;

        while (self::exists($table, $slug, $ignoreId)) {
            $ending = '-'.$suffix++;
            $slug = substr($base, 0, 191 - strlen($ending)).$ending;
        }

        return $slug;
    }

    private static function exists(string $table, string $slug, ?int $ignoreId): bool
    {
        return DB::table($table)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }
}
