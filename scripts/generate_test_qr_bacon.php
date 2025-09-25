<?php
require __DIR__ . '/../vendor/autoload.php';
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

$renderer = new ImageRenderer(
    new RendererStyle(300),
    new SvgImageBackEnd()
);
$writer = new Writer($renderer);
$svg = $writer->writeString('https://example.com/test?asset=1');
$dir = __DIR__ . '/../storage/app/public/qr';
if (!is_dir($dir)) mkdir($dir, 0755, true);
file_put_contents($dir . '/test-qr.svg', $svg);
echo "wrote " . $dir . '/test-qr.svg' . PHP_EOL;
