<?php

/**
 * One-time / repeatable extractor: splits resources/views/front-of-the-e_commerce.html
 * into fragments (skips browser-extension junk at start and end).
 */
$srcPath = dirname(__DIR__) . '/resources/views/front-of-the-e_commerce.html';
$outDir = dirname(__DIR__) . '/resources/fragments';

if (! is_file($srcPath)) {
    fwrite(STDERR, "Missing source: {$srcPath}\n");
    exit(1);
}

$lines = file($srcPath, FILE_IGNORE_NEW_LINES);
$n = count($lines);

// 1-based line numbers from audit:
$headStart = 2565;
$headEnd = 12583;
$bodyStart = 12584;
$bodyEnd = 26186;

$toIdx = static fn (int $line) => $line - 1;

$headSlice = array_slice($lines, $toIdx($headStart), $headEnd - $headStart + 1);
$headSlice[0] = preg_replace('/^\s*><head>/', '<head>', $headSlice[0], 1);

$bodySlice = array_slice($lines, $toIdx($bodyStart), $bodyEnd - $bodyStart + 1);

if (! is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

file_put_contents($outDir . '/framer-head.html', implode("\n", $headSlice) . "\n");
file_put_contents($outDir . '/framer-body-content.html', implode("\n", $bodySlice) . "\n");

$navSlice = array_slice($lines, $toIdx(12618), 13118 - 12618 + 1);
file_put_contents($outDir . '/framer-nav.html', implode("\n", $navSlice) . "\n");

echo "Wrote framer-head.html, framer-body-content.html, framer-nav.html to {$outDir}\n";
