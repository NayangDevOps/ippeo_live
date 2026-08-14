<?php

if (!function_exists('media_url')) {
    function media_url(?string $path): string
    {
        if (!$path) {
            return asset('images/logo.png');
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        if (str_starts_with($path, 'images/') || str_starts_with($path, 'css/') || str_starts_with($path, 'js/')) {
            return asset($path);
        }
        return asset('storage/' . ltrim($path, '/'));
    }
}
