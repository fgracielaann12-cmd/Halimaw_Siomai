<?php
file_put_contents(__DIR__ . "/test_log.txt", "Script ran at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
echo "Cron job ran successfully at " . date_default_timezone_set('Asia/Manila'); // or your local timezone

