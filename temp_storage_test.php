<?php
require __DIR__.'/vendor/autoload.php';
 = require __DIR__.'/bootstrap/app.php';
 = ->make(Illuminate\\Contracts\\Console\\Kernel::class);
->bootstrap();
use Illuminate\\Support\\Facades\\Storage;
echo 'default disk url: '.Storage::url('bukti/test.jpg'). \n;
echo 'public disk url: '.Storage::disk('public')->url('bukti/test.jpg').\n;
