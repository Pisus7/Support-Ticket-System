<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        Password::defaults(function () {
            return Password::min(12) // Mindestens 12 Zeichen
            ->letters()          // Muss Buchstaben enthalten
            ->mixedCase()        // Groß- und Kleinschreibung
            ->numbers()          // Muss Zahlen enthalten
            ->symbols()          // Muss Sonderzeichen enthalten
            ->uncompromised();   // CRITICAL: Prüft über die "Have I Been Pwned" API, ob das Passwort jemals geleakt wurde!
        });
    }
}
