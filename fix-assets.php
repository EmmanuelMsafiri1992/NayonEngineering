<?php
/**
 * Fix Assets Script
 * Copies static assets from public/ to root for shared hosting
 * DELETE THIS FILE AFTER RUNNING IT!
 */

echo "<h2>Copying Assets to Root</h2>";
echo "<pre>";

$basePath = dirname(__FILE__);
$publicPath = $basePath . '/public';

// Folders to copy
$folders = ['css', 'js', 'images', 'uploads'];

foreach ($folders as $folder) {
    $source = $publicPath . '/' . $folder;
    $dest = $basePath . '/' . $folder;

    if (is_dir($source)) {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
            echo "Created: /$folder/\n";
        }

        // Copy all files recursively
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destPath = $dest . '/' . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                if (copy($item, $destPath)) {
                    echo "✓ Copied: /" . $folder . "/" . $iterator->getSubPathName() . "\n";
                } else {
                    echo "✗ Failed: /" . $folder . "/" . $iterator->getSubPathName() . "\n";
                }
            }
        }
    } else {
        echo "- Skipped: /$folder/ (not found in public)\n";
    }
}

// Also copy favicon if exists
if (file_exists($publicPath . '/favicon.ico')) {
    copy($publicPath . '/favicon.ico', $basePath . '/favicon.ico');
    echo "✓ Copied: /favicon.ico\n";
}

echo "\n</pre>";
echo "<h3 style='color:green;'>Done! Assets copied to root.</h3>";
echo "<p><strong>DELETE this file (fix-assets.php) now!</strong></p>";
echo "<p>Then refresh your website.</p>";
?>
