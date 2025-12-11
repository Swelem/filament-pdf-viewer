# Setting up PDF.js

## Download PDF.js

1. Visit https://github.com/mozilla/pdf.js/releases
2. Download the latest stable release (e.g., v4.x.x)
3. Extract the `build/` folder contents
4. Copy these files to `resources/dist/`:
    - `pdf.min.mjs` (or `pdf.min.js`)
    - `pdf.worker.min.mjs` (or `pdf.worker.min.js`)

## Or use CDN (automatic)

The package will automatically use Mozilla's CDN if local files are not found.

## Manual Installation

```bash
# From the package root directory
cd resources/dist

# Download PDF.js (v4.0.379 - latest stable as of Dec 2024)
curl -L https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.min.mjs -o pdf.min.mjs
curl -L https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.379/pdf.worker.min.mjs -o pdf.worker.min.mjs
```

## Publish Assets

After installation, publish the assets:

```bash
php artisan vendor:publish --tag="filament-pdf-viewer-assets"
```
