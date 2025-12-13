<?php

namespace Swelem\FilamentPdfViewer;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPdfViewerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-pdf-viewer';

    public static string $viewNamespace = 'filament-pdf-viewer';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('swelem/filament-pdf-viewer');
            });

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }

        if (file_exists($package->basePath('/../resources/dist'))) {
            $package->hasAssets();
        }
    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish PDF.js assets
            $this->publishes([
                __DIR__ . '/../resources/dist' => public_path('vendor/filament-pdf-viewer'),
            ], 'filament-pdf-viewer-assets');

            // Publish plugin JS
            $this->publishes([
                __DIR__ . '/../resources/js' => public_path('vendor/filament-pdf-viewer/js'),
            ], 'filament-pdf-viewer-js');
        }
        
    }
}
