<?php
$content = file_get_contents('app/Views/user/dashboard.php');
preg_match_all('/<script>(.*?)<\/script>/is', $content, $matches);
$js = $matches[1][count($matches[1])-1]; // last script tag
file_put_contents('temp.js', $js);
exec('node -c temp.js 2>&1', $output, $return_var);
echo "Node output:\n" . implode("\n", $output) . "\nReturn var: $return_var\n";
