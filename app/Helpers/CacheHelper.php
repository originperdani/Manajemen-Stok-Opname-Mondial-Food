<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    public static function bumpVersion(string $namespace): void
    {
        $key = "cache_versions.{$namespace}";
        $currentVersion = (int) Cache::get($key, 1);
        Cache::forever($key, $currentVersion + 1);
    }
}
