<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem; // এই লাইনটি যোগ করুন
use App\Services\CustomFilesystem;    // এই লাইনটি যোগ করুন

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // --- ফাইল লকিং সমস্যার চূড়ান্ত সমাধান ---
        // Laravel-কে বলছি যে Filesystem এর জন্য আমাদের কাস্টম ক্লাস ব্যবহার করতে
        $this->app->singleton(Filesystem::class, function ($app) {
            return new CustomFilesystem;
        });
        // -------------------------------------
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // boot মেথডটি খালি থাকবে
    }
}