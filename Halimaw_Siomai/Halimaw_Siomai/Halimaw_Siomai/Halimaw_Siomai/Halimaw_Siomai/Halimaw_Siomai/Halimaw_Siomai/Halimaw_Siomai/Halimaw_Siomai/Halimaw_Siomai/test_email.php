<?php
$db = new PDO('mysql:host=localhost;dbname=halimaw_siomai;charset=utf8', 'root', '');
echo "Today: " . $db->query("SELECT SUM(total) FROM sales WHERE DATE(created_at) = CURDATE()")->fetchColumn() . "\n";
echo "Week: " . $db->query("SELECT SUM(total) FROM sales WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)")->fetchColumn() . "\n";
echo "Month: " . $db->query("SELECT SUM(total) FROM sales WHERE created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')")->fetchColumn() . "\n";

require 'c:/xampp/htdocs/Halimaw_Siomai/vendor/autoload.php';

$app = CodeIgniter\Boot::bootWeb(new \Config\Paths());
$email = \Config\Services::email();

$config = [
    'protocol'   => getenv('email.protocol') ?: 'smtp',
    'SMTPHost'   => getenv('email.SMTPHost') ?: 'smtp.gmail.com',
    'SMTPUser'   => getenv('email.SMTPUser'),
    'SMTPPass'   => getenv('email.SMTPPass'),
    'SMTPPort'   => (int)(getenv('email.SMTPPort') ?: 465),
    'SMTPCrypto' => getenv('email.SMTPCrypto') ?: 'ssl',
    'mailType'   => 'html',
    'charset'    => 'utf-8',
    'newline'    => "\r\n"
];
$email->initialize($config);

$email->setTo(getenv('email.SMTPUser'));
$email->setFrom(getenv('email.SMTPUser'), 'Test Script');
$email->setSubject('Test Email from CI4 Script');
$email->setMessage('This is a test');

if ($email->send(false)) {
    echo "SUCCESS\n";
} else {
    echo "FAILED\n";
    echo $email->printDebugger(['headers']);
}
