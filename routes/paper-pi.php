<?php

use BakeMorePies\PaperPi\PaperPi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Paper-Pi Documentation Routes
|--------------------------------------------------------------------------
|
| These routes handle displaying your markdown documentation files.
| The routes support multiple locales and nested documentation paths.
| Documentation as easy as π!
|
*/

$locales = implode('|', config('paper-pi.locales'));
$prefix = config('paper-pi.route_prefix');
$middleware = config('paper-pi.route_middleware');
$structure = config('paper-pi.route_structure', 'prefix-first');

Route::middleware($middleware)->group(function () use ($locales, $prefix, $structure) {
    
    if ($structure === 'locale-first') {
        // /{locale}/{prefix} and /{locale}/{prefix}/{page}
        
        // Documentation index
        Route::get('/{locale}/' . $prefix, function (string $locale) {
            app()->setLocale($locale);
            
            $docs = new PaperPi($locale, 'index');
            
            if (!$docs->exists()) {
                abort(404, 'Documentation not found');
            }
            
            return $docs->view(config('paper-pi.views.docs', 'paper-pi::page'));
        })->where('locale', $locales);
        
        // Documentation pages
        Route::get('/{locale}/' . $prefix . '/{page}', function (string $locale, string $page) {
            app()->setLocale($locale);
            
            $docs = new PaperPi($locale, $page);
            
            if (!$docs->exists()) {
                abort(404, 'Documentation page not found');
            }
            
            return $docs->view(config('paper-pi.views.docs', 'paper-pi::page'));
        })->where('locale', $locales)
          ->where('page', '(.*)');
        
        // Redirect from root to default locale
        Route::get('/' . $prefix, function () {
            $defaultLocale = config('paper-pi.default_locale', 'en');
            return redirect("/{$defaultLocale}/{$prefix}");
        });
        
    } else {
        // /{prefix}/{locale} and /{prefix}/{locale}/{page} (default: prefix-first)
        
        // Documentation index
        Route::get($prefix . '/{locale}', function (string $locale) {
            app()->setLocale($locale);
            
            $docs = new PaperPi($locale, 'index');
            
            if (!$docs->exists()) {
                abort(404, 'Documentation not found');
            }
            
            return $docs->view(config('paper-pi.views.docs', 'paper-pi::page'));
        })->where('locale', $locales);
        
        // Documentation pages
        Route::get($prefix . '/{locale}/{page}', function (string $locale, string $page) {
            app()->setLocale($locale);
            
            $docs = new PaperPi($locale, $page);
            
            if (!$docs->exists()) {
                abort(404, 'Documentation page not found');
            }
            
            return $docs->view(config('paper-pi.views.docs', 'paper-pi::page'));
        })->where('locale', $locales)
          ->where('page', '(.*)');
        
        // Redirect from root to default locale
        Route::get('/' . $prefix, function () use ($prefix) {
            $defaultLocale = config('paper-pi.default_locale', 'en');
            return redirect("/{$prefix}/{$defaultLocale}");
        });
    }
});
