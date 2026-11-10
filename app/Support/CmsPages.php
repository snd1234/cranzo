<?php

namespace App\Support;

class CmsPages
{
    public const FIXED = [
        'about-us',
        'contact-us',
        'faq',
        'privacy-policy',
        'terms-conditions',
        'returns-exchange',
        'terms-of-use',
    ];

    public static function url(string $slug): string
    {
        return in_array($slug, self::FIXED, true)
            ? url('/'.$slug)
            : route('cms.page', ['slug' => $slug]);
    }
}
