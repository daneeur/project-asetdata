<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$has = Schema::hasColumn('assets', 'offline_qr_path') ? 'yes' : 'no';
echo "offline_qr_path column exists? " . $has . PHP_EOL;
