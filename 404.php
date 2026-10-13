<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/functions.php';

http_response_code(404);
$page_title = 'Page not found';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/not_found_body.php';
require __DIR__ . '/includes/footer.php';