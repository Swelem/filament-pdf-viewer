# Quick Start Guide

Get up and running with Filament PDF Viewer in 5 minutes!

## 📦 Step 1: Install

```bash
composer require swelem/filament-pdf-viewer:^3.0
```

## ⚙️ Step 2: Publish Config (Optional)

```bash
php artisan vendor:publish --tag="filament-pdf-viewer-config"
```

## 🚀 Step 3: Use in Your Resource

### In a Form (Edit View)

```php
use Swelem\FilamentPdfViewer\Forms\Components\PdfViewerField;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Your other fields...

            PdfViewerField::make('file_path')
                ->label('Document Preview')
                ->minHeight('500px'),
        ]);
}
```

### In an Infolist (View Page)

```php
use Swelem\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            PdfViewerEntry::make('file_path')
                ->label('Document')
                ->minHeight('600px'),
        ]);
}
```

## ✨ That's It!

The component will automatically:

-   ✅ Use PDF.js for rendering
-   ✅ Detect and handle base64 data
-   ✅ Show navigation toolbar
-   ✅ Support dark mode
-   ✅ Work with URLs, file paths, and base64

## 🎯 Common Use Cases

### Database File Path

```php
// Model has 'pdf_path' field: 'documents/invoice.pdf'
PdfViewerField::make('pdf_path')
```

### External URL

```php
PdfViewerField::make('document')
    ->fileUrl('https://example.com/contract.pdf')
```

### Base64 Data

```php
// Model has 'pdf_base64' field with base64 string
PdfViewerField::make('pdf_base64')
```

### Generated PDF

```php
PdfViewerEntry::make('invoice')
    ->formatStateUsing(function ($record) {
        $pdf = Pdf::loadView('invoice', ['data' => $record]);
        return 'data:application/pdf;base64,' . base64_encode($pdf->output());
    })
```

## 🎨 Customization

### Hide Toolbar

```php
PdfViewerField::make('file')->showToolbar(false)
```

### Set Zoom Level

```php
PdfViewerField::make('file')->defaultScale('page-width')
```

### Use Native Viewer

```php
PdfViewerField::make('file')->usePdfJs(false)
```

## 📚 Learn More

-   Full documentation: [README.md](README.md)
-   Usage examples: [EXAMPLES.php](EXAMPLES.php)
-   Configuration: [config/filament-pdf-viewer.php](config/filament-pdf-viewer.php)

## 🐛 Troubleshooting

**PDF not loading?**

-   Check file path is correct
-   Verify file exists in storage
-   Check browser console for errors

**Toolbar not showing?**

-   Ensure `show_toolbar` is `true` in config
-   Or use `->showToolbar(true)` on component

**Using base64?**

-   Must start with `data:application/pdf;base64,`
-   Component auto-detects this format

## 🆘 Need Help?

Open an issue: [GitHub Issues](https://github.com/Swelem/filament-pdf-viewer/issues)

## 🎉 You're Ready!

Start viewing PDFs in your Filament app!
