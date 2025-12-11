# Filament PDF Viewer (Enhanced Fork)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/swelem/filament-pdf-viewer.svg?style=flat-square)](https://packagist.org/packages/swelem/filament-pdf-viewer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/swelem/filament-pdf-viewer/run-tests.yml?branch=3.x&label=tests&style=flat-square)](https://github.com/swelem/filament-pdf-viewer/actions?query=workflow%3Arun-tests+branch%3A3.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/swelem/filament-pdf-viewer/fix-php-code-style-issues.yml?branch=3.x&label=code%20style&style=flat-square)](https://github.com/swelem/filament-pdf-viewer/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3A3.x)
[![Total Downloads](https://img.shields.io/packagist/dt/swelem/filament-pdf-viewer.svg?style=flat-square)](https://packagist.org/packages/swelem/filament-pdf-viewer)

> **📌 Note**: This is an enhanced fork of [joaopaulolndev/filament-pdf-viewer](https://github.com/joaopaulolndev/filament-pdf-viewer) with PDF.js integration and additional features. See [ATTRIBUTION.md](ATTRIBUTION.md) for details.

FilamentPHP package to show PDF documents with records saved in the database or show documents without a database in the form of your resource. Now powered by **PDF.js** for enhanced viewing capabilities!

<div class="filament-hidden">

![Screenshot of Application Feature](https://raw.githubusercontent.com/swelem/filament-pdf-viewer/3.x/art/swelem-filament-pdf-viewer.jpg)

</div>

## Features & Screenshots

-   **PDF.js Integration:** Advanced PDF rendering with custom controls (replaces native browser viewer)
-   **Base64 & Binary Support:** Display PDFs from base64 encoded data or binary streams
-   **Form Field:** Show a PDF document viewer in a form field
-   **Infolist Entry:** Show a PDF document viewer in an infolist entry
-   **Customizable Toolbar:** Control zoom, pagination, and viewing options
-   **Dark Mode Support:** Fully compatible with Filament's dark mode
-   **Support**: [Laravel 11](https://laravel.com) and [Filament 4.x](https://filamentphp.com)

## Compatibility

| Package Version | Filament Version |
| --------------- | ---------------- |
| 1.x             | 3.x              |
| 2.x             | 4.x              |
| 3.x             | 4.x              |

## Installation

You can install the package via composer:

```bash
composer require swelem/filament-pdf-viewer:^3.0
```

### Publish Configuration & Assets

Publish the configuration file:

```bash
php artisan vendor:publish --tag="filament-pdf-viewer-config"
```

Optionally, publish the views:

```bash
php artisan vendor:publish --tag="filament-pdf-viewer-views"
```

Optionally, publish PDF.js assets (if you want to host them locally instead of using CDN):

```bash
php artisan vendor:publish --tag="filament-pdf-viewer-assets"
```

### PDF.js Setup

By default, the package uses Mozilla's CDN to load PDF.js. For production or offline use, you can download and host PDF.js locally:

See [SETUP_PDFJS.md](SETUP_PDFJS.md) for detailed instructions.

## Configuration

The package comes with sensible defaults. You can customize the behavior in `config/filament-pdf-viewer.php`:

```php
return [
    // Use PDF.js instead of native browser viewer
    'use_pdfjs' => env('FILAMENT_PDF_VIEWER_USE_PDFJS', true),

    // PDF.js version (used for CDN fallback)
    'pdfjs_version' => '4.0.379',

    // Use CDN if local files don't exist
    'use_cdn_fallback' => true,

    // Default viewer options
    'viewer_options' => [
        'default_scale' => 'auto', // 'auto', 'page-fit', 'page-width', or numeric
        'show_toolbar' => true,
        'show_page_navigation' => true,
        'enable_text_selection' => true,
    ],
];
```

## Usage in form field

### Basic Usage

```php
use Swelem\FilamentPdfViewer\Forms\Components\PdfViewerField;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            PdfViewerField::make('file')
                ->label('View the PDF')
                ->minHeight('40svh')
        ]);
}
```

### Advanced Usage with PDF.js Options

```php
PdfViewerField::make('file')
    ->label('View the PDF')
    ->minHeight('600px')
    ->usePdfJs(true) // Enable PDF.js (default: true)
    ->showToolbar(true) // Show navigation toolbar (default: true)
    ->defaultScale('page-width') // 'auto', 'page-fit', 'page-width', or numeric
    ->columnSpanFull()
```

### With Base64 Data

```php
PdfViewerField::make('pdf_content')
    ->label('View PDF')
    ->minHeight('500px')
    // The component automatically detects and handles base64 data
    // No special configuration needed!
```

### Using External URL

```php
PdfViewerField::make('document')
    ->label('Contract PDF')
    ->fileUrl('https://example.com/document.pdf')
    ->minHeight('70vh')
```

### Disable PDF.js (Use Native Browser Viewer)

```php
PdfViewerField::make('file')
    ->label('View the PDF')
    ->usePdfJs(false) // Falls back to iframe with native viewer
    ->minHeight('40svh')
```

## Usage in infolist entry

### Basic Usage

```php
use Swelem\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            PdfViewerEntry::make('file')
                ->label('View the PDF')
                ->minHeight('40svh')
        ]);
}
```

### With External URL

```php
PdfViewerEntry::make('file')
    ->label('View the PDF')
    ->minHeight('40svh')
    ->fileUrl(Storage::url('dummy.pdf')) // Set the file url
    ->columnSpanFull()
```

### Base64 PDF Data

```php
PdfViewerEntry::make('pdf_data')
    ->label('Generated PDF')
    ->minHeight('600px')
    // Automatically handles base64 data URLs
```

### Advanced Configuration

```php
PdfViewerEntry::make('contract_pdf')
    ->label('Contract Document')
    ->minHeight('80vh')
    ->usePdfJs(true)
    ->showToolbar(true)
    ->defaultScale('page-fit')
    ->pdfJsOptions([
        'enable_text_selection' => true,
        'enable_hand_tool' => true,
    ])
    ->columnSpanFull()
```

### With Section

```php
use Filament\Infolists\Components\Section;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            Section::make('PDF Viewer')
                ->description('Preview the document')
                ->collapsible()
                ->schema([
                    PdfViewerEntry::make('file')
                        ->label('View the PDF')
                        ->minHeight('40svh')
                        ->fileUrl(Storage::url('dummy.pdf'))
                        ->columnSpanFull()
                ]),
        ]);
}
```

### Hide Toolbar

```php
PdfViewerEntry::make('file')
    ->label('PDF Document')
    ->showToolbar(false) // Hide navigation controls
    ->minHeight('500px')
```

## Working with Binary/Base64 Data

The package automatically detects and handles base64 encoded PDFs. Here are some common scenarios:

### Database Field with Base64

```php
// In your model
public function getPdfDataAttribute()
{
    // Your binary PDF data from database
    $binaryData = $this->attributes['pdf_binary'];

    // Convert to base64 data URL
    return 'data:application/pdf;base64,' . base64_encode($binaryData);
}

// In your Resource
PdfViewerField::make('pdf_data')
    ->label('PDF Document')
```

### Generating PDF on the Fly

```php
use Barryvdh\DomPDF\Facade\Pdf;

// In your Resource
PdfViewerEntry::make('generated_pdf')
    ->label('Invoice')
    ->formatStateUsing(function ($record) {
        $pdf = Pdf::loadView('invoices.template', ['invoice' => $record]);
        $output = $pdf->output();

        return 'data:application/pdf;base64,' . base64_encode($output);
    })
```

### API Response

```php
// Display PDF fetched from external API
PdfViewerEntry::make('api_document')
    ->label('External Document')
    ->formatStateUsing(function ($record) {
        $response = Http::get($record->document_api_url);
        $pdfContent = $response->body();

        return 'data:application/pdf;base64,' . base64_encode($pdfContent);
    })
```

## Available Methods

### Common Methods (Both Field & Entry)

| Method                              | Description                          | Default    |
| ----------------------------------- | ------------------------------------ | ---------- |
| `minHeight(string)`                 | Set minimum height                   | `'50svh'`  |
| `usePdfJs(bool\|Closure)`           | Enable/disable PDF.js                | `true`     |
| `showToolbar(bool\|Closure)`        | Show/hide toolbar                    | `true`     |
| `defaultScale(string\|Closure)`     | Set default zoom                     | `'auto'`   |
| `fileUrl(string\|Closure)`          | Set external PDF URL                 | `''`       |
| `visibility(string\|Closure)`       | File visibility (`public`/`private`) | `'public'` |
| `checkFileExistence(bool\|Closure)` | Verify file exists                   | `true`     |
| `pdfJsOptions(array\|Closure)`      | Custom PDF.js options                | `[]`       |

### Scale Options

-   `'auto'` - Automatic scaling
-   `'page-fit'` - Fit entire page in view
-   `'page-width'` - Fit page width
-   Numeric values: `0.5`, `0.75`, `1`, `1.25`, `1.5`, `2`, etc.

## PDF.js vs Native Browser Viewer

### PDF.js Advantages ✅

-   Consistent rendering across browsers
-   Custom toolbar and controls
-   Better base64 handling
-   Zoom and navigation controls
-   Dark mode compatible UI
-   Works with CORS-restricted PDFs

### Native Browser Viewer Advantages ✅

-   Smaller page size (no external JS)
-   Browser's built-in features
-   Faster initial load
-   Print functionality built-in

To switch between modes:

```php
// Use PDF.js (recommended)
PdfViewerField::make('file')->usePdfJs(true)

// Use native browser viewer
PdfViewerField::make('file')->usePdfJs(false)
```

## Troubleshooting

### PDF.js Not Loading

1. Check if CDN is accessible:

```bash
curl -I https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.mjs
```

2. Or host locally:

```bash
php artisan vendor:publish --tag="filament-pdf-viewer-assets"
```

### Base64 PDFs Not Displaying

Make sure your base64 string includes the data URL prefix:

```php
'data:application/pdf;base64,' . base64_encode($pdfContent)
```

### CORS Issues

If loading PDFs from external URLs, ensure the server allows CORS. PDF.js needs to fetch the document.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Fork & Attribution

This package is a **fork** of the original [joaopaulolndev/filament-pdf-viewer](https://github.com/joaopaulolndev/filament-pdf-viewer), enhanced with additional features.

### Original Project

-   **Original Author**: [João Paulo Leite Nascimento](https://github.com/joaopaulolndev)
-   **Original Package**: `joaopaulolndev/filament-pdf-viewer`
-   **Original Repository**: https://github.com/joaopaulolndev/filament-pdf-viewer

### This Fork

-   **Fork Maintainer**: [Saif Swelem](https://github.com/swelem)
-   **Fork Package**: `swelem/filament-pdf-viewer`
-   **Major Enhancements**: PDF.js integration, base64/binary support, enhanced configuration

### Credits

-   [Saif Swelem](https://github.com/swelem) - Fork Maintainer & PDF.js Integration
-   [João Paulo Leite Nascimento](https://github.com/joaopaulolndev) - Original Package Author
-   [Rômulo Ramos](https://github.com/rmsramos) - Original Contributor
-   [All Contributors](../../contributors)

### Legal

This fork is licensed under the MIT License, same as the original work. See [LICENSE.md](LICENSE.md) and [ATTRIBUTION.md](ATTRIBUTION.md) for full legal details and attribution.

**Note**: This fork is independently maintained and is not officially affiliated with or endorsed by the original package author.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
