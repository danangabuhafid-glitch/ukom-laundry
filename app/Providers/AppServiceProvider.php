<?php

namespace App\Providers;

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
        // Share Web Setting globally
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('web_settings')) {
                $webSetting = \App\Models\WebSetting::first();
                \Illuminate\Support\Facades\View::share('webSetting', $webSetting);
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('menus')) {
                $dynamicMenus = \App\Models\Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
                \Illuminate\Support\Facades\View::share('dynamicMenus', $dynamicMenus);
            }
        } catch (\Exception $e) {
            // Ignore during migrations
        }
    }
}
