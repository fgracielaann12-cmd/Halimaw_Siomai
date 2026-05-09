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
        }
        
        // Check if the file contains the corruption pattern (e.g. â‚± which is C3 A2 E2 80 9A C2 B1)
        // Or if we just blindly revert, wait, only revert if it was corrupted!
        // We know it was corrupted if we find "â" (C3 A2) frequently, but it's safer to check if mb_convert_encoding produces valid UTF-8.
        // Actually, we ONLY ran the powershell script on files where `$content -match 'overflow-x:\s*hidden;'`!
        // Wait, the powershell script ran: 
        // if ($content -match 'overflow-x:\s*hidden;') { ... Set-Content ... }
        // Let's check which files were modified today.
        
        // Better: let's use a safe str_replace for the specific corrupted characters we saw.
        // This is MUCH safer than blindly mb_convert_encoding because what if a file wasn't corrupted?
        // mb_convert_encoding on a normal UTF-8 file would destroy it!
        
        $replacements = [
            "â‚±" => "₱",
            "â†‘" => "↑",
            "â†“" => "↓",
            "â€”" => "—",
            "â€“" => "–",
            "â†’" => "→",
            "ðŸ“ˆ" => "📈",
            "âœ¨" => "✨",
            "âœ…" => "✅",
            "â€¦" => "…"
        ];
        
        $newContent = str_replace(array_keys($replacements), array_values($replacements), $content);
        
        if ($newContent !== $content) {
            file_put_contents($path, $newContent);
            echo "Fixed: $path\n";
        } elseif ($hasBom) {
            // Just remove the BOM
            file_put_contents($path, $content);
            echo "Removed BOM: $path\n";
        }
    }
}
echo "Done.\n";
