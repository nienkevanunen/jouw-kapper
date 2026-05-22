<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$dist = $root . '/dist';

function remove_directory(string $path): void
{
    if (!is_dir($path)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }

    rmdir($path);
}

function copy_directory(string $source, string $target, array $excludedExtensions = []): void
{
    if (!is_dir($source)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {
        $relative = substr($item->getPathname(), strlen($source) + 1);
        $destination = $target . '/' . $relative;

        if ($item->isDir()) {
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            continue;
        }

        $extension = strtolower(pathinfo($item->getFilename(), PATHINFO_EXTENSION));
        if (in_array($extension, $excludedExtensions, true)) {
            continue;
        }

        $directory = dirname($destination);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        copy($item->getPathname(), $destination);
    }
}

remove_directory($dist);
mkdir($dist, 0777, true);

ob_start();
require $root . '/index.php';
$html = ob_get_clean();

file_put_contents($dist . '/index.html', $html);
file_put_contents($dist . '/.nojekyll', '');

foreach (['css', 'data', 'img', 'js', 'lib'] as $directory) {
    copy_directory($root . '/' . $directory, $dist . '/' . $directory);
}

copy_directory($root . '/contactform', $dist . '/contactform', ['php']);
copy_directory($root . '/admin', $dist . '/admin', ['php']);

echo "Built static site in {$dist}\n";
