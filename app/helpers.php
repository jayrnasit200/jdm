<?php

use App\Models\SysConfig;

if (!function_exists('sys_config')) {
    function sys_config($key, $default = null) {
        return SysConfig::get($key, $default);
    }
}

if (!function_exists('media_url')) {
    function media_url(?string $path): string
    {
        if (!$path) {
            return '';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
