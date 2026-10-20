<?php

namespace App\Providers;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\View;
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
        // Share unread messages count to all admin layout views.
        View::composer('admin.layout', function (\Illuminate\View\View $view) {
            $view->with('unreadMessagesCount', ContactMessage::whereNull('read_at')->count());
        });
    }
}
