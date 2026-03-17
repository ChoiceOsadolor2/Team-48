<?php

namespace App\Providers;

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

    public function boot(): void
    {
        Password::defaults(function () {
            return Password::min(8)->rules([
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! is_string($value) || ! preg_match('/[A-Z]/', $value)) {
                        $fail('The password must contain at least one capital letter.');
                    }
                },
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! is_string($value) || ! preg_match('/[!\?\/\\\\%&]/', $value)) {
                        $fail('The password must contain at least one symbol from ! ? / \\ % &.');
                    }
                },
            ]);
        });
    }

}
