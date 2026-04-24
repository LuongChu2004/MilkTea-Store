<?php
function replaceInDir($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            replaceInDir($path);
        } else if (pathinfo($path, PATHINFO_EXTENSION) == 'php') {
            $content = file_get_contents($path);
            if (strpos($content, "asset('admin/product/") !== false || strpos($content, 'asset("admin/product/') !== false) {
                $newContent = str_replace("asset('admin/product/", "asset('admin_assets/product/", $content);
                $newContent = str_replace('asset("admin/product/', 'asset("admin_assets/product/', $newContent);
                file_put_contents($path, $newContent);
                echo "Updated $path\n";
            }
        }
    }
}
replaceInDir('resources/views');
