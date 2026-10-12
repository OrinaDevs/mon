<?php
const UPLOAD_DIR = __DIR__ . '/../uploads/products/';
const MAX_IMAGE_BYTES = 2 * 1024 * 1024; // 2 MB

/**
 * Returns [filename or null, error message or null].
 * No file chosen is not an error: it returns [null, null].
 */
function save_product_image(array $file): array
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'The upload failed. Try a smaller image.'];
    }
    if ($file['size'] > MAX_IMAGE_BYTES) {
        return [null, 'The image must be 2 MB or smaller.'];
    }

    // Check the real file type, not the name the browser sent.
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        return [null, 'Only JPG, PNG or WebP images are allowed.'];
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    // Random name: no clashes, and nothing the user typed ends up in the path.
    $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $name)) {
        return [null, 'Could not save the image.'];
    }

    return [$name, null];
}

function delete_product_image(?string $filename): void
{
    if (!$filename) {
        return;
    }
    // basename() makes sure we can only ever touch files inside the uploads folder.
    $path = UPLOAD_DIR . basename($filename);
    if (is_file($path)) {
        unlink($path);
    }
}