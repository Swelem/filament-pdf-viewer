<?php

namespace Swelem\FilamentPdfViewer\Infolists\Components;

use Closure;
use Filament\Infolists\Components\ViewEntry;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToCheckFileExistence;
use Throwable;

class PdfViewerEntry extends ViewEntry
{
    protected string $view = 'filament-pdf-viewer::filament.components.infolists.pdf-viewer-entry';

    protected string $minHeight = '50svh';

    protected string|Closure $fileUrl = '';

    protected string|Closure|null $disk = null;

    protected string|Closure $visibility = 'public';

    protected bool|Closure $shouldCheckFileExistence = true;

    protected bool|Closure $usePdfJs = true;

    protected array|Closure $pdfJsOptions = [];

    protected bool|Closure $showToolbar = true;

    protected string|Closure $defaultScale = 'auto';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set default PDF.js usage from config
        $this->usePdfJs = config('filament-pdf-viewer.use_pdfjs', true);
        
        // Load default viewer options from config
        $viewerOptions = config('filament-pdf-viewer.viewer_options', []);
        $this->showToolbar = $viewerOptions['show_toolbar'] ?? true;
        $this->defaultScale = $viewerOptions['default_scale'] ?? 'auto';
    }

    public function minHeight(string $minHeight): self
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    public function getMinHeight(): string
    {
        return $this->minHeight;
    }

    public function getDisk(): Filesystem
    {
        return Storage::disk($this->getDiskName());
    }

    public function getDiskName(): string
    {
        return $this->evaluate($this->disk) ?? config('filament.default_filesystem_disk');
    }

    public function fileUrl(string|Closure $fileUrl): self
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    public function getFileUrl(?string $state = null): string|null
    {
        if (empty($state)) {
            return $this->evaluate($this->fileUrl);
        }

        if ((filter_var($state, FILTER_VALIDATE_URL) !== false) || str($state)->startsWith('data:')) {
            return $state;
        }

        /** @var FilesystemAdapter $storage */
        $storage = $this->getDisk();

        if ($this->shouldCheckFileExistence()) {
            try {
                if (! $storage->exists($state)) {
                    return null;
                }
            } catch (UnableToCheckFileExistence $exception) {
                return null;
            }
        }

        if ($this->getVisibility() === 'private') {
            try {
                return $storage->temporaryUrl(
                    $state,
                    now()->addMinutes(60),
                );
            } catch (Throwable $exception) {
                // This driver does not support creating temporary URLs.
            }
        }

        return $storage->url($state);
    }

    // public function getFileUrl(): string
    // {
    //     return $this->evaluate($this->fileUrl);
    // }

    public function getVisibility(): string
    {
        return $this->evaluate($this->visibility);
    }

    public function checkFileExistence(bool|Closure $condition = true): static
    {
        $this->shouldCheckFileExistence = $condition;

        return $this;
    }

    public function shouldCheckFileExistence(): bool
    {
        return (bool) $this->evaluate($this->shouldCheckFileExistence);
    }

    /**
     * @return null|string|void
     */
    public function getRoute(string $file)
    {
        return $this->getFileUrl($file);
    }

    /**
     * Enable or disable PDF.js viewer
     */
    public function usePdfJs(bool|Closure $condition = true): static
    {
        $this->usePdfJs = $condition;

        return $this;
    }

    /**
     * Check if PDF.js should be used
     */
    public function shouldUsePdfJs(): bool
    {
        return (bool) $this->evaluate($this->usePdfJs);
    }

    /**
     * Set PDF.js viewer options
     */
    public function pdfJsOptions(array|Closure $options): static
    {
        $this->pdfJsOptions = $options;

        return $this;
    }

    /**
     * Get PDF.js viewer options
     */
    public function getPdfJsOptions(): array
    {
        $options = $this->evaluate($this->pdfJsOptions);
        
        // Merge with config defaults
        return array_merge(
            config('filament-pdf-viewer.viewer_options', []),
            is_array($options) ? $options : []
        );
    }

    /**
     * Show or hide the PDF viewer toolbar
     */
    public function showToolbar(bool|Closure $condition = true): static
    {
        $this->showToolbar = $condition;

        return $this;
    }

    /**
     * Check if toolbar should be shown
     */
    public function shouldShowToolbar(): bool
    {
        return (bool) $this->evaluate($this->showToolbar);
    }

    /**
     * Set the default scale for PDF rendering
     */
    public function defaultScale(string|Closure $scale): static
    {
        $this->defaultScale = $scale;

        return $this;
    }

    /**
     * Get the default scale
     */
    public function getDefaultScale(): string
    {
        return (string) $this->evaluate($this->defaultScale);
    }

    /**
     * Convert binary data to base64 data URL
     */
    public function getBinaryAsBase64(string $binaryData): string
    {
        return 'data:application/pdf;base64,' . base64_encode($binaryData);
    }

    /**
     * Check if the data is base64 encoded
     */
    public function isBase64Data(?string $data): bool
    {
        if (empty($data)) {
            return false;
        }

        return str($data)->startsWith('data:application/pdf;base64,')
            || str($data)->startsWith('data:');
    }

    /**
     * Get PDF.js library URL
     */
    public function getPdfJsLibraryUrl(): string
    {
        $localPath = public_path('vendor/filament-pdf-viewer/pdf.min.mjs');
        
        if (file_exists($localPath)) {
            return asset('vendor/filament-pdf-viewer/pdf.min.mjs');
        }

        // Fallback to CDN
        if (config('filament-pdf-viewer.use_cdn_fallback', true)) {
            $version = config('filament-pdf-viewer.pdfjs_version', '4.0.379');
            $cdnUrl = config('filament-pdf-viewer.cdn_url', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/{version}');
            
            return str_replace('{version}', $version, $cdnUrl) . '/pdf.min.mjs';
        }

        return '';
    }

    /**
     * Get PDF.js worker URL
     */
    public function getPdfJsWorkerUrl(): string
    {
        $localPath = public_path('vendor/filament-pdf-viewer/pdf.worker.min.mjs');
        
        if (file_exists($localPath)) {
            return asset('vendor/filament-pdf-viewer/pdf.worker.min.mjs');
        }

        // Fallback to CDN
        if (config('filament-pdf-viewer.use_cdn_fallback', true)) {
            $version = config('filament-pdf-viewer.pdfjs_version', '4.0.379');
            $cdnUrl = config('filament-pdf-viewer.cdn_url', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/{version}');
            
            return str_replace('{version}', $version, $cdnUrl) . '/pdf.worker.min.mjs';
        }

        return '';
    }
}
