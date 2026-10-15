<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /**
     * Get a site setting value by key with cache and fallback.
     */
    function setting(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return app(SiteSetting::class);
        }

        try {
            // Cache as a clean associative array (scalars only, avoids Eloquent serialization issues)
            $all = Cache::remember('site_settings_map', now()->addDay(), function () {
                return SiteSetting::all(['key', 'value', 'type'])
                    ->keyBy('key')
                    ->map(fn ($item) => [
                        'value' => $item->value,
                        'type' => $item->type,
                    ])
                    ->toArray();
            });

            if (isset($all[$key])) {
                $val = $all[$key]['value'];
                $type = $all[$key]['type'] ?? 'text';

                if ($val !== null && $val !== '') {
                    if ($type === 'json') {
                        $decoded = json_decode($val, true);

                        return $decoded !== null ? $decoded : $val;
                    }

                    if ($type === 'boolean') {
                        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
                    }

                    return $val;
                }
            }
        } catch (Throwable) {
            // Table might not exist yet or connection issue
        }

        if ($default !== null) {
            return $default;
        }

        return config("portfolio.{$key}");
    }
}
