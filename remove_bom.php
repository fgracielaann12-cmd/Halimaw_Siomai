<?php
$directory = new RecursiveDirectoryIterator(__DIR__ . '/app/Views');
$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $info) {
    if ($info->isFile() && $info->getExtension() === 'php') {
        $path = $info->getPathname();
        $content = file_get_contents($path);
        
        $hasBom = (substr($content, 0, 3) === "\xef\xbb\xbf");
        if ($hasBom) {
            $content = substr($content, 3);
            file_put_contents($path, $content);
            echo "Removed BOM: $path\n";
        }
    }
}
echo "Done.\n";
