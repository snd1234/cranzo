<?php

namespace App\Support;

class InvoicePdf
{
    public static function make(array $invoice, ?string $logoPath = null): string
    {
        $jpeg = null;
        $imageWidth = 0;
        $imageHeight = 0;
        if ($logoPath && is_file($logoPath)) {
            $info = @getimagesize($logoPath);
            if ($info && ($info[2] ?? null) === IMAGETYPE_JPEG) {
                $jpeg = file_get_contents($logoPath);
                $imageWidth = (int) $info[0];
                $imageHeight = (int) $info[1];
            }
        }

        $content = self::page($invoice, $jpeg !== null);
        $resources = "<< /Font << /F1 5 0 R /F2 6 0 R >>".($jpeg !== null ? " /XObject << /Im1 7 0 R >>" : '')." >>";
        $objects = [
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
            "2 0 obj\n<< /Type /Pages /Count 1 /Kids [3 0 R] >>\nendobj\n",
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources {$resources} >>\nendobj\n",
            "4 0 obj\n<< /Length ".strlen($content)." >>\nstream\n".$content."endstream\nendobj\n",
            "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n",
            "6 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n",
        ];
        if ($jpeg !== null) {
            $objects[] = "7 0 obj<< /Type /XObject /Subtype /Image /Width {$imageWidth} /Height {$imageHeight} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ".strlen($jpeg)." >>stream\n".$jpeg."\nendstream\nendobj\n";
        }

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object;
        }
        $xref = strlen($pdf);
        $size = count($objects) + 1;
        $pdf .= "xref\n0 {$size}\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }
        $pdf .= "trailer<< /Size {$size} /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
    }

    private static function page(array $invoice, bool $logo): string
    {
        $ink = [0.106, 0.086, 0.075];
        $gold = [0.784, 0.627, 0.310];
        $muted = [0.42, 0.40, 0.38];
        $stream = sprintf("%.3f %.3f %.3f rg\n0 734 595 108 re f\n", $ink[0], $ink[1], $ink[2]);
        $stream .= sprintf("%.3f %.3f %.3f rg\n0 730 595 4 re f\n", $gold[0], $gold[1], $gold[2]);
        if ($logo) {
            $stream .= "q\n92 0 0 92 28 742 cm\n/Im1 Do\nQ\n";
        }
        $stream .= self::right('F2', 18, 560, 804, 'INVOICE', [1, 1, 1]);
        $stream .= self::right('F1', 10, 560, 784, self::clip((string) ($invoice['number'] ?? ''), 24), $gold);
        $stream .= self::right('F1', 9, 560, 766, self::clip((string) ($invoice['date'] ?? ''), 24), [0.86, 0.84, 0.80]);

        $stream .= "0.965 0.957 0.945 rg\n36 612 250 96 re f\n";
        $stream .= "0.965 0.957 0.945 rg\n310 612 249 96 re f\n";
        $stream .= self::text('F2', 8, 48, 688, 'BILL TO', $gold);
        $y = 670;
        foreach (array_slice($invoice['bill_to'] ?? [], 0, 4) as $index => $line) {
            $stream .= self::text($index === 0 ? 'F2' : 'F1', $index === 0 ? 11 : 9, 48, $y, self::clip($line, 36), $index === 0 ? $ink : $muted);
            $y -= 14;
        }

        $facts = [
            ['Payment', (string) ($invoice['payment'] ?? '')],
            ['Order status', (string) ($invoice['status'] ?? '')],
        ];
        $factY = 670;
        foreach ($facts as [$label, $value]) {
            $stream .= self::text('F1', 8, 322, $factY, strtoupper($label), $gold);
            $stream .= self::text('F2', 10, 322, $factY - 13, self::clip($value, 28), $ink);
            $factY -= 32;
        }

        $tableTop = 572;
        $stream .= sprintf("%.3f %.3f %.3f rg\n36 %d 523 24 re f\n", $ink[0], $ink[1], $ink[2], $tableTop);
        $stream .= self::text('F2', 8, 48, $tableTop + 8, 'ITEM', [1, 1, 1]);
        $stream .= self::text('F2', 8, 330, $tableTop + 8, 'QTY', [1, 1, 1]);
        $stream .= self::text('F2', 8, 390, $tableTop + 8, 'RATE', [1, 1, 1]);
        $stream .= self::text('F2', 8, 480, $tableTop + 8, 'AMOUNT', [1, 1, 1]);

        $rowY = $tableTop - 26;
        $shade = false;
        foreach (array_slice($invoice['lines'] ?? [], 0, 12) as $line) {
            if ($shade) {
                $stream .= "0.965 0.957 0.945 rg\n36 {$rowY} 523 26 re f\n";
            }
            $shade = ! $shade;
            $stream .= self::text('F1', 10, 48, $rowY + 9, self::clip((string) ($line['title'] ?? ''), 42), $ink);
            $stream .= self::text('F1', 10, 336, $rowY + 9, (string) ($line['qty'] ?? ''), $ink);
            $stream .= self::text('F1', 10, 390, $rowY + 9, self::money($line['rate'] ?? 0), $ink);
            $stream .= self::text('F2', 10, 480, $rowY + 9, self::money($line['amount'] ?? 0), $ink);
            $rowY -= 26;
        }

        $totals = [
            ['Subtotal', $invoice['subtotal'] ?? 0],
            ['Discount', $invoice['discount'] ?? 0],
            [self::clip((string) ($invoice['tax_label'] ?? 'GST'), 26), $invoice['tax'] ?? 0],
        ];
        $totalY = $rowY - 18;
        foreach ($totals as [$label, $amount]) {
            $stream .= self::text('F1', 10, 360, $totalY, $label, $muted);
            $stream .= self::text('F1', 10, 480, $totalY, self::money($amount), $ink);
            $totalY -= 16;
        }
        $stream .= sprintf("%.3f %.3f %.3f rg\n348 %d 211 28 re f\n", $ink[0], $ink[1], $ink[2], $totalY - 6);
        $stream .= self::text('F2', 11, 360, $totalY + 3, 'Total', [1, 1, 1]);
        $stream .= self::text('F2', 11, 470, $totalY + 3, self::money($invoice['total'] ?? 0), $gold);

        $noteY = max(64, $totalY - 36);
        foreach ($invoice['notes'] ?? [] as $note) {
            $stream .= self::text('F1', 8, 36, $noteY, self::clip($note, 108), $muted);
            $noteY -= 12;
        }
        $stream .= sprintf("%.3f %.3f %.3f rg\n0 0 595 36 re f\n", $ink[0], $ink[1], $ink[2]);
        $stream .= self::text('F1', 9, 36, 14, 'cranzo.in  ·  Car and bike accessories', $gold);

        return $stream;
    }

    private static function text(string $font, int $size, float $x, float $y, string $value, array $rgb = [0.06, 0.09, 0.16]): string
    {
        return sprintf(
            "%.3f %.3f %.3f rg\nBT /%s %d Tf %.2f %.2f Td (%s) Tj ET\n",
            $rgb[0],
            $rgb[1],
            $rgb[2],
            $font,
            $size,
            $x,
            $y,
            self::escape($value)
        );
    }

    private static function right(string $font, int $size, float $right, float $y, string $value, array $rgb = [0.06, 0.09, 0.16]): string
    {
        $x = $right - (strlen($value) * $size * 0.52);

        return self::text($font, $size, $x, $y, $value, $rgb);
    }

    private static function money(mixed $amount): string
    {
        return 'Rs '.number_format((float) $amount, 2);
    }

    private static function clip(string $value, int $length): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');
        if (strlen($value) <= $length) {
            return $value;
        }

        return substr($value, 0, $length - 1).'.';
    }

    private static function escape(string $value): string
    {
        $value = preg_replace('/[^\x20-\x7E]/', '', $value) ?? '';

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $value);
    }
}
