<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\LaporanBukti;

$last = LaporanBukti::orderByDesc('id')->take(5)->get();
foreach ($last as $b) {
    echo $b->id.' | laporan '.$b->laporan_id.' | '.$b->file_path."\n";
}
