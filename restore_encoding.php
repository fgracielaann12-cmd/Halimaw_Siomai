<?php
$directory = new RecursiveDirectoryIterator(__DIR__ . '/app/Views');
$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $info) {
    if ($info->isFile() && $info->getExtension() === 'php') {
        $path = $info->getPathname();
        $content = file_get_contents($path);
        
        $hasBom = (substr($content, 0, 3) === "\xef\xbb\xbf");
        $actualContent = $hasBom ? substr($content, 3) : $content;
        
        // We only want to convert files that have corruption.
        // Emojis starting with F0 9F in UTF-8 become ðŸ (C3 B0 C5 B8) in corrupted form.
        // So we can check for "ð" (C3 B0) or "â" (C3 A2).
        if (strpos($actualContent, "\xc3\xa2") !== false || strpos($actualContent, "\xc3\xb0") !== false) {
            // Apply mb_convert_encoding
            $restored = mb_convert_encoding($actualContent, 'Windows-1252', 'UTF-8');
            file_put_contents($path, $restored);
            echo "Restored encoding: $path\n";
        }
    }
}
echo "Done.\n";
