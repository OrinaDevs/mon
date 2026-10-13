<?php
// Small helpers for search-engine and link-sharing tags.

// Full web address for a path such as '/products.php' (uses SITE_URL from config.php).
function abs_url(string $path = '/'): string
{
    return rtrim(SITE_URL, '/') . $path;
}

// Plain-text summary for meta descriptions: no tags, tidy spaces, cut at a word boundary.
function seo_excerpt(?string $text, int $max = 155): string
{
    $text = trim(preg_replace('/\s+/u', ' ', strip_tags($text ?? '')));
    if (mb_strlen($text) <= $max) {
        return $text;
    }
    $cut = mb_substr($text, 0, $max - 3);
    $space = mb_strrpos($cut, ' ');
    if ($space !== false && $space > $max * 0.6) {
        $cut = mb_substr($cut, 0, $space);
    }
    return rtrim($cut, " ,.;:-") . '...';
}

// One JSON-LD block. JSON_HEX_TAG stops text such as "</script>" from breaking out of the tag.
function json_ld(array $data): string
{
    return '<script type="application/ld+json">'
         . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP)
         . "</script>\n";
}

// The shop described for Google (used on the home and contact pages).
function business_schema(): array
{
    return [
        '@context'    => 'https://schema.org',
        '@type'       => 'ElectronicsStore',
        '@id'         => abs_url('/#business'),
        'name'        => SITE_NAME,
        'url'         => abs_url('/'),
        'logo'        => abs_url('/assets/img/logo-cropped.png'),
        'image'       => abs_url('/assets/img/og-default.png'),
        'description' => 'CCTV cameras, networking components and power backups, with deliveries countrywide.',
        'telephone'   => SHOP_PHONES[0]['intl'],
        'email'       => SHOP_EMAIL,
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressLocality' => SHOP_LOCALITY,
            'addressCountry'  => SHOP_COUNTRY_CODE,
        ],
        'areaServed'  => ['@type' => 'Country', 'name' => 'Kenya'],
    ];
}