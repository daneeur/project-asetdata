<?php
require __DIR__ . '/../vendor/autoload.php';
use SimpleSoftwareIO\QrCode\Facades\QrCode;
$qr = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(300)->generate('https://example.com/test?asset=1');
$dir = __DIR__ . '/../storage/app/public/qr';
if (!is_dir($dir)) mkdir($dir, 0755, true);
file_put_contents($dir . '/test-qr.svg', $qr);
echo "wrote " . $dir . '/test-qr.svg' . PHP_EOL;
