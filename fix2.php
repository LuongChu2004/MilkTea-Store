<?php
$f = 'resources/views/layouts/app.blade.php';
$c = file_get_contents($f);
// Fix asset
$c = preg_replace("/\{\{\s*asset\('([^']+)\"([^>]*?)>/", "{{ asset('$1') }}\"$2>", $c);
// Fix url
$c = preg_replace("/\{\{\s*url\('([^']+)\"([^>]*?)>/", "{{ url('$1') }}\"$2>", $c);
file_put_contents($f, $c);
