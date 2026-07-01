<?php

declare(strict_types=1);

/**
 * Emergency cache reset when Laravel cannot boot (500 after deploy).
 * Open: https://yourdomain.com/ops-cache-reset.php?token=YOUR_DEPLOY_OPS_TOKEN
 *
 * Delete this file after the site is healthy.
 */
$token = (string) ($_GET['token'] ?? '');

$laravelRoot = null;

foreach ([
    dirname(__DIR__),
    dirname(__DIR__).DIRECTORY_SEPARATOR.'elixira',
] as $candidate) {
    if (is_file($candidate.DIRECTORY_SEPARATOR.'artisan')) {
        $laravelRoot = $candidate;
        break;
    }
}

if ($laravelRoot === null) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    exit("Laravel root not found.\n");
}

$expectedToken = '';

$envPath = $laravelRoot.DIRECTORY_SEPARATOR.'.env';

if (is_readable($envPath)) {
    $envContents = (string) file_get_contents($envPath);

    if (preg_match('/^DEPLOY_OPS_TOKEN=(.*)$/m', $envContents, $matches) === 1) {
        $expectedToken = trim($matches[1], " \t\n\r\0\x0B\"'");
    }
}

if ($expectedToken === '' || ! hash_equals($expectedToken, $token)) {
    http_response_code(404);
    exit('Not found');
}

$cleared = [];
$cacheDir = $laravelRoot.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache';

foreach (glob($cacheDir.DIRECTORY_SEPARATOR.'*.php') ?: [] as $file) {
    if (@unlink($file)) {
        $cleared[] = basename($file);
    }
}

header('Content-Type: text/plain; charset=UTF-8');
echo "Elixira cache reset\n";
echo 'Time: '.date('c')."\n";
echo 'Laravel root: '.$laravelRoot."\n";
echo 'Cleared: '.($cleared === [] ? '(none)' : implode(', ', $cleared))."\n";
echo "\nNext: open /ops/run/{token} or reload the homepage.\n";
