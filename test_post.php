<?php
$data = [
    'item_id' => 5615,
    'action' => 'add',
    'quantity' => 45,
    'reason' => 'wowowowo'
];

$ch = curl_init('http://localhost/Halimaw_Siomai/index.php/user/submit-stock-request');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
// We also need to send cookies if user_id is checked.
// Let's first test without cookies, if it succeeds but user_id is null, it'll tell us.
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
