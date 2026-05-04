<?php

$dir = new RecursiveDirectoryIterator('c:\xampp\htdocs\Halimaw_Siomai\app\Views');
$iterator = new RecursiveIteratorIterator($dir);

$filesUpdated = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $originalContent = file_get_contents($path);
        $content = $originalContent;

        // Strip HTML classes
        $content = preg_replace('/\brounded-pill\b/', 'rounded-1', $content); // Bootstrap rounded-1 is 0.2rem (~3-5px)
        $content = preg_replace('/\brounded-circle\b/', 'rounded-1', $content);
        $content = preg_replace('/\bbtn-pill\b/', 'btn', $content);

        // Replace CSS styles in <style> or inline style=""
        $content = preg_replace('/border-radius:\s*50px;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*25px;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*15px;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*12px;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*10px;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*0\.65rem;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*0\.35rem;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*0\.375rem;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*var\(--border-radius\);?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*50rem;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*30px;?/i', 'border-radius: 5px;', $content);
        $content = preg_replace('/border-radius:\s*20px;?/i', 'border-radius: 5px;', $content);

        // Explicitly update the global CSS injection to be absolutely bulletproof and include all possible buttons, cards, inputs.
        // Wait, replacing everything is better.

        if ($content !== $originalContent) {
            file_put_contents($path, $content);
            $filesUpdated++;
        }
    }
}

echo "Updated $filesUpdated files to forcefully apply 5px border-radius and strip pill classes.\n";
