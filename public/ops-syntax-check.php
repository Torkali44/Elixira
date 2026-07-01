<?php

declare(strict_types=1);

/**
 * Verify critical PHP files on the server after FTP upload.
 * Open: https://yourdomain.com/ops-syntax-check.php?token=YOUR_DEPLOY_OPS_TOKEN
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

$criticalFiles = [
    'app/Http/Requests/Auth/LoginRequest.php',
    'app/Http/Controllers/Auth/AuthenticatedSessionController.php',
    'app/Http/Controllers/Auth/RegisteredUserController.php',
    'app/Http/Controllers/Auth/EmailVerificationPromptController.php',
    'app/Support/EmailVerificationOtpService.php',
    'app/Support/PasswordResetOtpService.php',
    'app/Support/NavUserData.php',
    'app/Models/User.php',
    'routes/auth.php',
];

header('Content-Type: text/plain; charset=UTF-8');
echo "Elixira PHP syntax check\n";
echo 'Time: '.date('c')."\n";
echo 'Laravel root: '.$laravelRoot."\n\n";

$hasErrors = false;

foreach ($criticalFiles as $relativePath) {
    $path = $laravelRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (! is_file($path)) {
        echo "MISSING: {$relativePath}\n";
        $hasErrors = true;

        continue;
    }

    $output = [];
    $exitCode = 0;
    exec('php -l '.escapeshellarg($path).' 2>&1', $output, $exitCode);
    $line = trim(implode(' ', $output));

    if ($exitCode !== 0) {
        echo "FAIL: {$relativePath}\n       {$line}\n";
        $hasErrors = true;

        continue;
    }

    $lines = count(file($path) ?: []);
    echo "OK: {$relativePath} ({$lines} lines)\n";
}

echo "\n".($hasErrors ? 'Fix FAIL/MISSING files then retry login.' : 'All critical auth files look valid.')."\n";
