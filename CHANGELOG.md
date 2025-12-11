# Changelog

All notable changes to `filament-pdf-viewer` will be documented in this file.

## v3.0.0 - 2025-12-11

### 🎉 Major Release - Fork with Enhanced Features

This is a fork of `joaopaulolndev/filament-pdf-viewer` with significant enhancements:

-   **PDF.js Integration** - Complete integration with Mozilla's PDF.js library for enhanced PDF rendering
-   **Base64 Support** - Native support for base64 encoded PDF data
-   **Binary Data Support** - Handle binary PDF streams directly
-   **Custom Toolbar** - Interactive toolbar with zoom, pagination, and navigation controls
-   **Dark Mode Support** - Fully compatible with Filament's dark mode
-   **Configuration System** - New config file for customizing defaults

### ✨ New Features

-   Added `usePdfJs()` method to enable/disable PDF.js viewer
-   Added `showToolbar()` method to control toolbar visibility
-   Added `defaultScale()` method for zoom control (`auto`, `page-fit`, `page-width`, or numeric)
-   Added `pdfJsOptions()` method for advanced PDF.js configuration
-   Added automatic base64 detection with `isBase64Data()` helper
-   Added `getBinaryAsBase64()` helper for binary conversion
-   Added CDN fallback for PDF.js library
-   Added asset publishing support
-   Added comprehensive configuration file

### 📦 Installation

```bash
composer require swelem/filament-pdf-viewer:^3.0
```

### Migration from Original Package (joaopaulolndev/filament-pdf-viewer v2.x)

```bash
# Update composer.json
composer remove joaopaulolndev/filament-pdf-viewer
composer require swelem/filament-pdf-viewer:^3.0

# Update use statements in your code
# From: use Joaopaulolndev\FilamentPdfViewer\...
# To:   use Swelem\FilamentPdfViewer\...
```

**Full Changelog**: https://github.com/Swelem/filament-pdf-viewer/commits/feat/pdfjs

### 📚 Documentation

-   Added extensive usage examples in README
-   Added SETUP_PDFJS.md for PDF.js installation guide
-   Added MIGRATION.md for upgrading from original package
-   Added EXAMPLES.php with real-world usage scenarios
-   Added install-pdfjs.sh script for easy PDF.js setup
-   Added ATTRIBUTION.md for fork relationship transparency

### 🔧 Technical Changes

-   Enhanced `PdfViewerField` component with PDF.js support
-   Enhanced `PdfViewerEntry` component with PDF.js support
-   Updated Blade views with PDF.js rendering engine
-   Updated ServiceProvider to publish config and assets
-   Maintained full backward compatibility with iframe viewer

### 🎨 UI Improvements

-   Interactive pagination controls
-   Zoom level selector (auto, page-fit, page-width, percentages)
-   Responsive canvas rendering
-   Smooth page transitions
-   Better error handling and display
-   Dark mode compatible styling

### 🐛 Bug Fixes

-   Improved URL and base64 data detection
-   Better handling of private file storage
-   Fixed CORS issues with PDF.js
-   Enhanced file existence checking
-   Added CDN fallback for PDF.js library

---

## Previous Versions (Original Package)

For the changelog of the original `joaopaulolndev/filament-pdf-viewer` package, please visit:
https://github.com/joaopaulolndev/filament-pdf-viewer/blob/2.x/CHANGELOG.md

-   initial release
