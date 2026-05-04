<?php
$dashboard = file_get_contents('app/Views/user/dashboard.php');
$dashboard = preg_replace(
    '/(<td>\s*<\?php if \(\$isLowStock\): \?>\s*<strong><\?= esc\(\$vItem\[\'qty\'\]\) \?><\/strong> <span class="badge bg-warning text-dark ms-1">Low<\/span>\s*<\?php else: \?>\s*<\?= esc\(\$vItem\[\'qty\'\]\) \?>\s*<\?php endif; \?>\s*<\?php if \(stripos\(\$item\[\'name\'\], \'burger patty\'\) !== false && empty\(\$vItem\[\'variation\'\]\)\): \?>)\s*<small class="text-muted ms-1">\(6\)<\/small>\s*(<\?php endif; \?>\s*<\/td>)/is',
    '<td>' . "\n" .
    '                                <?php if ($isLowStock): ?>' . "\n" .
    '                                <span><strong><?= esc($vItem[\'qty\']) ?></strong> <span class="badge bg-warning text-dark ms-1">Low</span></span><?php if (stripos($item[\'name\'], \'burger patty\') !== false && empty($vItem[\'variation\'])): ?> <small class="text-muted ms-1">(6)</small><?php endif; ?>' . "\n" .
    '                                <?php else: ?>' . "\n" .
    '                                <span><?= esc($vItem[\'qty\']) ?></span><?php if (stripos($item[\'name\'], \'burger patty\') !== false && empty($vItem[\'variation\'])): ?> <small class="text-muted ms-1">(6)</small><?php endif; ?>' . "\n" .
    '                                <?php endif; ?>' . "\n" .
    '                            </td>',
    $dashboard
);
file_put_contents('app/Views/user/dashboard.php', $dashboard);

$list = file_get_contents('app/Views/items/list.php');
$list = preg_replace(
    '/(<td class="text-center align-middle">)\s*<span>(<\?= esc\(\$item\[\'quantity\'\]\) \?>)<\/span>\s*(<\?php if \(stripos\(\$item\[\'name\'\], \'burger patty\'\) !== false\): \?>)\s*<small class="text-muted ms-1">\(6\)<\/small>\s*(<\?php endif; \?>\s*<\/td>)/is',
    '<td class="text-center align-middle"><span>$2</span><?php if (stripos($item[\'name\'], \'burger patty\') !== false): ?> <small class="text-muted ms-1">(6)</small><?php endif; ?></td>',
    $list
);
file_put_contents('app/Views/items/list.php', $list);
echo "Done";
