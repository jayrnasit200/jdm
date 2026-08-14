<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensurePublicStorageLink();

        if ($this->app->runningInConsole()) {
            return;
        }

        $request = request();

        if ($request->header('X-Forwarded-Proto') === 'https' || $request->secure()) {
            URL::forceScheme('https');
        }

        $configured = rtrim((string) config('app.url'), '/');
        $isPlaceholder = $configured === ''
            || str_contains($configured, 'localhost')
            || str_contains($configured, '127.0.0.1');

        if ($isPlaceholder) {
            URL::forceRootUrl(rtrim($request->root(), '/'));
        }
    }

    private function ensurePublicStorageLink(): void
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        if (file_exists($link) || ! is_dir($target)) {
            return;
        }

        try {
            symlink($target, $link);
        } catch (\Throwable $e) {
            // Shared hosting often blocks symlinks; the /storage/{path} route is the fallback.
        }
    }
}
