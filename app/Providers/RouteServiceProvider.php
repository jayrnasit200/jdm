<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

public function boot(): void
{
    Route::aliasMiddleware('role', RoleMiddleware::class);
}
    