<?php
$dir = new RecursiveDirectoryIterator('app/Views');
foreach(new RecursiveIteratorIterator($dir) as $f) {
    if ($f->isFile() && $f->getExtension() == 'php') {
        $c = file_get_contents($f->getPathname());
        $c2 = str_replace('white-space: normal; line-height: 1.2;', 'white-space: nowrap; line-height: 1.2;', $c);
        if ($c !== $c2) {
            file_put_contents($f->getPathname(), $c2);
            echo "Updated " . $f->getPathname() . "\n";
        }
    }
}
