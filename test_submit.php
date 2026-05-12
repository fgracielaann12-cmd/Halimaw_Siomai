<?php
$ch = curl_init('http://localhost/Halimaw_Siomai/index.php/user/dashboard');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
$html = curl_exec($ch);
curl_close($ch);

preg_match('/<meta name="csrf-token" content="(.*?)">/', $html, $matches);
$csrf = $matches[1] ?? '';

$ch2 = curl_init('http://localhost/Halimaw_Siomai/index.php/user/submit-stock-request');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query([
    'item_id' => 5615,
    'action' => 'add',
    'quantity' => 10,
    'reason' => 'test curl',
]));
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    "X-Requested-With: XMLHttpRequest",
    "X-CSRF-TOKEN: $csrf"
]);
curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookies.txt');
$response = curl_exec($ch2);
$httpcode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo "HTTP CODE: $httpcode\n";
echo "RESPONSE: $response\n";
