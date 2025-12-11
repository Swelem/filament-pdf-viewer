# 🚀 Quick Reference: v3.0.0

## Package Information

| Item             | Value                        |
| ---------------- | ---------------------------- |
| **Package Name** | `swelem/filament-pdf-viewer` |
| **Version**      | 3.x                          |
| **Namespace**    | `Swelem\FilamentPdfViewer`   |
| **PHP**          | 8.2+                         |
| **Laravel**      | 11.x                         |
| **Filament**     | 4.x                          |

## Installation

```bash
composer require swelem/filament-pdf-viewer:^3.0
php artisan vendor:publish --tag="filament-pdf-viewer-config"
```

## Import Statements

```php
use Swelem\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Swelem\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;
```

## Basic Usage

```php
// In Forms
PdfViewerField::make('file_path')
    ->label('PDF Document')
    ->minHeight('500px');

// In Infolists
PdfViewerEntry::make('file_path')
    ->label('PDF Document')
    ->minHeight('500px');
```

## Common Methods

```php
->usePdfJs(true)              // Enable PDF.js (default: true)
->showToolbar(true)           // Show toolbar (default: true)
->defaultScale('page-width')  // Set zoom level
->fileUrl('https://...')      // External URL
->minHeight('500px')          // Set height
->columnSpanFull()           // Full width
```

## Upgrade from v2.x

1. Update composer: `composer require swelem/filament-pdf-viewer:^3.0`
2. Replace namespace: `Joaopaulolndev\FilamentPdfViewer` → `Swelem\FilamentPdfViewer`
3. Clear cache: `php artisan config:clear`

## Links

-   📚 [Full Documentation](README.md)
-   🚀 [Quick Start](QUICKSTART.md)
-   📖 [Examples](EXAMPLES.php)
-   🐛 [GitHub Issues](https://github.com/Swelem/filament-pdf-viewer/issues)

## Features

✅ PDF.js integration  
✅ Base64/Binary support  
✅ Interactive toolbar  
✅ Dark mode  
✅ Zoom controls  
✅ CDN fallback  
✅ Custom configuration

---

**Package**: swelem/filament-pdf-viewer | **Version**: 3.0.0 | **Updated**: Dec 11, 2025
