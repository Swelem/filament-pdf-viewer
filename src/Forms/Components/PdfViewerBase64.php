<?php

namespace Swelem\FilamentPdfViewer\Forms\Components;

use Closure;
use Filament\Forms\Components\ViewField;

class PdfViewerBase64 extends ViewField
{
    protected string $view = 'filament-pdf-viewer::filament.components.forms.pdf-viewer-base64';

    protected string $minHeight = '50svh';

    protected string|Closure $base64 = '';

    protected bool|Closure $showToolbar = true;

    protected string|Closure $defaultScale = 'auto';

    protected bool|Closure $usePdfJs = true;

    protected array|Closure $pdfJsOptions = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Hide in create context by default
        $this->hidden(fn (string $context): bool => $context === 'create');

        // Load defaults from config
        $viewerOptions = config('filament-pdf-viewer.viewer_options', []);
        $this->showToolbar = $viewerOptions['show_toolbar'] ?? true;
        $this->defaultScale = $viewerOptions['default_scale'] ?? 'auto';
        $this->usePdfJs = config('filament-pdf-viewer.use_pdfjs', true);
    }

    public function minHeight(string $minHeight): self
    {
        $this->minHeight = $minHeight;
        return $this;
    }

    public function getMinHeight(): string
    {
        return (string) $this->evaluate($this->minHeight);
    }

    public function base64(string|Closure $data): self
    {
        $this->base64 = $data;
        return $this;
    }

    public function getBase64(): string
    {
        return (string) $this->evaluate($this->base64);
    }

    public function showToolbar(bool|Closure $condition = true): self
    {
        $this->showToolbar = $condition;
        return $this;
    }

    public function shouldShowToolbar(): bool
    {
        return (bool) $this->evaluate($this->showToolbar);
    }

    public function defaultScale(string|Closure $scale): self
    {
        $this->defaultScale = $scale;
        return $this;
    }

    public function getDefaultScale(): string
    {
        return (string) $this->evaluate($this->defaultScale);
    }

    public function usePdfJs(bool|Closure $condition = true): self
    {
        $this->usePdfJs = $condition;
        return $this;
    }

    public function shouldUsePdfJs(): bool
    {
        return (bool) $this->evaluate($this->usePdfJs);
    }

    public function pdfJsOptions(array|Closure $options): self
    {
        $this->pdfJsOptions = $options;
        return $this;
    }

    public function getPdfJsOptions(): array
    {
        $options = $this->evaluate($this->pdfJsOptions);
        return array_merge(
            config('filament-pdf-viewer.viewer_options', []),
            is_array($options) ? $options : []
        );
    }

    /**
     * Get PDF.js library URL (local first, fallback to CDN)
     */
    public function getPdfJsLibraryUrl(): string
    {
        $localPath = public_path('vendor/filament-pdf-viewer/pdf.min.mjs');

        if (file_exists($localPath)) {
            return asset('vendor/filament-pdf-viewer/pdf.min.mjs');
        }

        $version = config('filament-pdf-viewer.pdfjs_version', '4.0.379');
        return "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/{$version}/pdf.min.mjs";
    }

    public function getPdfJsWorkerUrl(): string
    {
        $localPath = public_path('vendor/filament-pdf-viewer/pdf.worker.min.mjs');

        if (file_exists($localPath)) {
            return asset('vendor/filament-pdf-viewer/pdf.worker.min.mjs');
        }

        $version = config('filament-pdf-viewer.pdfjs_version', '4.0.379');
        return "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/{$version}/pdf.worker.min.mjs";
    }
}
