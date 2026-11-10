<?php

namespace App\Support;

class HtmlCleaner
{
    public static function clean(?string $html): string
    {
        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', (string) $html) ?? '';
        $html = strip_tags($html, '<p><br><ul><ol><li><strong><em><h2><h3><h4><a>');
        $html = preg_replace_callback('/<a\b[^>]*>/i', function (array $match) {
            if (preg_match('/href\s*=\s*"([^"]*)"/i', $match[0], $href) && preg_match('#^(https?:|/[^/])#i', $href[1])) {
                return '<a href="'.htmlspecialchars($href[1], ENT_QUOTES, 'UTF-8').'">';
            }

            return '<a>';
        }, $html) ?? '';
        $html = preg_replace('/<(\/?)\s*(p|br|ul|ol|li|strong|em|h2|h3|h4)\b[^>]*>/i', '<$1$2>', $html) ?? '';

        return trim($html);
    }
}
