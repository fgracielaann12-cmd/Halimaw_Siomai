<?php
$dir = new RecursiveDirectoryIterator('app/Views');
foreach(new RecursiveIteratorIterator($dir) as $f) {
    if ($f->isFile() && $f->getExtension() == 'php') {
        $c = file_get_contents($f->getPathname());
        $target = '.main-content { margin-left: 0; width: 100%; }';
        $replacement = ".main-content { margin-left: 0; width: 100%; }\n            .top-navbar { position: sticky; top: 0; z-index: 1000; border-radius: 0; margin-bottom: 15px; }";
        
        // only replace if we haven't already replaced it in this file
        if (strpos($c, '.top-navbar { position: sticky; top: 0; z-index: 1000; border-radius: 0; margin-bottom: 15px; }') === false) {
            $c2 = str_replace($target, $replacement, $c);
            if ($c !== $c2) {
                file_put_contents($f->getPathname(), $c2);
                echo "Updated " . $f->getPathname() . "\n";
            }
        }
    }
}
