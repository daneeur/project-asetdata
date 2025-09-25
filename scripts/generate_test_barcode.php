<?php
require __DIR__ . '/../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorPNG;
$generator = new BarcodeGeneratorPNG();
$data = 'AST-TEST1';
$img = $generator->getBarcode($data, $generator::TYPE_CODE_128, 2, 60);
$dir = __DIR__ . '/../storage/app/public/barcode';
if (!is_dir($dir)) mkdir($dir, 0755, true);
file_put_contents($dir . '/test-barcode.png', $img);
echo "wrote " . $dir . '/test-barcode.png' . PHP_EOL;
