<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Laporan;

$laporan = Laporan::with('bukti')->latest('id')->first();
if (!$laporan) {
    echo "no laporan\n";
    exit;
}

echo "laporan id: {$laporan->id}\n";
echo "tanggal: {$laporan->tanggal}\n";
echo "bukti count: " . $laporan->bukti->count() . "\n";
foreach ($laporan->bukti as $bukti) {
    echo "- {$bukti->id} | {$bukti->file_path}\n";
}
