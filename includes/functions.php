<?php
//Escaping text before printing it in html (prevents XSS)
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// "KES 4,500" or "Call for price" when no price is set.
function format_price($price): string
{
    return $price === null ? 'Call for price' : 'KES ' . number_format((float) $price);
}

// "4MP Dome Camera" -> "4mp-dome-camera"
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'product';
}

// Adds -2, -3 ... if the slug is already taken.
function unique_product_slug(string $name, int $ignoreId = 0): string
{
    $base = slugify($name);
    $slug = $base;
    $i = 2;
    $stmt = db()->prepare('SELECT COUNT(*) FROM products WHERE slug = ? AND id <> ?');

    while (true) {
        $stmt->execute([$slug, $ignoreId]);
        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $base . '-' . $i++;
    }
}

function whatsapp_link(string $message): string
{
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message);
}

function unique_category_slug(string $name, int $ignoreId = 0): string
{
    $base = slugify($name);
    $slug = $base;
    $i = 2;
    $stmt = db()->prepare('SELECT COUNT(*) FROM categories WHERE slug = ? AND id <> ?');

    while (true) {
        $stmt->execute([$slug, $ignoreId]);
        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $base . '-' . $i++;
    }
}