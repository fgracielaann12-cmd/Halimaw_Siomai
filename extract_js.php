<?php
$content = file_get_contents('app/Views/user/dashboard.php');
preg_match_all('/<script>(.*?)<\/script>/is', $content, $matches);
if (!empty($matches[1])) {
    $js = end($matches[1]);
    file_put_contents('temp_js_check.js', $js);
    echo "JS extracted.\n";
} else {
    echo "No JS found.\n";
}
