<?php

use App\Models\SysConfig;

if (!function_exists('sys_config')) {
    function sys_config($key, $default = null) {
        return SysConfig::get($key, $default);
    }
}
