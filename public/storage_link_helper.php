<?php
/**
 * Elixira - Storage Link Helper for Namecheap Shared Hosting.
 * 
 * If you do not have SSH terminal access to run `php artisan storage:link`,
 * you can open this file in your browser: https://yourdomain.com/storage_link_helper.php
 * to automatically generate the symlink.
 * 
 * IMPORTANT: Delete this file after successful execution to avoid security risks.
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\Artisan;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h2>Elixira Storage Link Helper</h2>";

try {
    // Check if target link already exists
    $link = public_path('storage');
    if (file_exists($link)) {
        if (is_link($link)) {
            echo "<p style='color: green;'>✔ Storage symlink already exists at: <strong>$link</strong></p>";
        } else {
            echo "<p style='color: orange;'>⚠ A folder named 'storage' exists at: <strong>$link</strong>, but it is not a symlink. Please delete or rename it first.</p>";
        }
    } else {
        Artisan::call('storage:link');
        echo "<p style='color: green;'>✔ Artisan storage:link completed successfully!</p>";
    }
} catch (\Throwable $e) {
    echo "<p style='color: red;'>❌ Failed to create symlink: " . $e->getMessage() . "</p>";
    
    // Attempt PHP native symlink fallback
    echo "<p>Attempting native PHP symlink fallback...</p>";
    $target = storage_path('app/public');
    $link = public_path('storage');
    
    if (symlink($target, $link)) {
        echo "<p style='color: green;'>✔ Successfully created symlink using symlink() fallback!</p>";
    } else {
        echo "<p style='color: red;'>❌ Native symlink fallback failed. Please contact your host provider.</p>";
    }
}

echo "<hr><p style='color: red; font-weight: bold;'>⚠️ IMPORTANT: Delete this file (public/storage_link_helper.php) from your server now!</p>";
