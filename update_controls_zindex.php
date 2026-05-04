<?php
$dir = new RecursiveDirectoryIterator('app/Views');
foreach(new RecursiveIteratorIterator($dir) as $f) {
    if ($f->isFile() && $f->getExtension() == 'php') {
        $c = file_get_contents($f->getPathname());
        $c2 = preg_replace('/(\.controls-section\s*\{[^\}]+?z-index:\s*)1050(;| !important;)/s', '${1}10$2', $c);
        if ($c !== $c2) {
            file_put_contents($f->getPathname(), $c2);
            echo "Updated " . $f->getPathname() . "\n";
        }
    }
}
