<?php
$dir = new RecursiveDirectoryIterator('app/Views');
foreach(new RecursiveIteratorIterator($dir) as $f) {
    if ($f->isFile() && $f->getExtension() == 'php') {
        $c = file_get_contents($f->getPathname());
        $c2 = preg_replace('/(\.badge-dot\s*\{[^\}]+?height:\s*20px\s*!important;)/s', "$1\n    flex-shrink: 0 !important;", $c);
        if ($c !== $c2) {
            file_put_contents($f->getPathname(), $c2);
            echo "Updated " . $f->getPathname() . "\n";
        }
    }
}
