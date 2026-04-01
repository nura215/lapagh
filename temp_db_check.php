<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Laporan;
use App\Models\LaporanBukti;

echo 'laporan: '.Laporan::count()."\n";
echo 'laporan_bukti: '.LaporanBukti::count()."\n";
$last = Laporan::latest('id')->first();
if ($last) {
    echo 'last laporan id '.$last->id.' bukti count '.$last->bukti()->count()."\n";
}
