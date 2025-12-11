# 📚 Documentation Index

Welcome to the Filament PDF Viewer documentation! This index will help you find what you need quickly.

## 🚀 Getting Started

| Document                             | Description            | When to Read      |
| ------------------------------------ | ---------------------- | ----------------- |
| **[QUICKSTART.md](QUICKSTART.md)**   | 5-minute setup guide   | Start here!       |
| **[README.md](README.md)**           | Complete documentation | After quick start |
| **[SETUP_PDFJS.md](SETUP_PDFJS.md)** | PDF.js installation    | For local hosting |

## 📖 Usage & Examples

| Document                         | Description               | When to Read            |
| -------------------------------- | ------------------------- | ----------------------- |
| **[EXAMPLES.php](EXAMPLES.php)** | Real-world usage examples | Learning best practices |
| **[README.md](README.md)**       | API reference & methods   | Building features       |

## 🔄 Upgrading

| Document                         | Description     | When to Read                 |
| -------------------------------- | --------------- | ---------------------------- |
| **[MIGRATION.md](MIGRATION.md)** | Upgrade guide   | Updating from older versions |
| **[CHANGELOG.md](CHANGELOG.md)** | Version history | Checking what's new          |

## 🔧 Configuration

| Document                                                             | Description        | When to Read          |
| -------------------------------------------------------------------- | ------------------ | --------------------- |
| **[config/filament-pdf-viewer.php](config/filament-pdf-viewer.php)** | Configuration file | Customizing defaults  |
| **[README.md](README.md)**                                           | Configuration docs | Understanding options |

## 📊 Project Information

| Document                                     | Description                 | When to Read              |
| -------------------------------------------- | --------------------------- | ------------------------- |
| **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)** | Complete feature list       | Understanding the project |
| **[KNOWN_ISSUES.md](KNOWN_ISSUES.md)**       | Known issues & limitations  | Troubleshooting           |
| **[CHANGELOG.md](CHANGELOG.md)**             | Version history             | Release notes             |
| **[ATTRIBUTION.md](ATTRIBUTION.md)**         | Fork relationship & credits | Understanding origin      |

## 🛠️ Development

| Document                                 | Description            | When to Read       |
| ---------------------------------------- | ---------------------- | ------------------ |
| **[composer.json](composer.json)**       | Package dependencies   | Contributing       |
| **[install-pdfjs.sh](install-pdfjs.sh)** | PDF.js download script | Local PDF.js setup |

## 📝 By Use Case

### I want to...

#### Display a PDF from database

→ Start with **[QUICKSTART.md](QUICKSTART.md)** → "Database File Path"

#### Use base64 encoded PDFs

→ **[README.md](README.md)** → "Working with Binary/Base64 Data"  
→ **[EXAMPLES.php](EXAMPLES.php)** → Example 4, 9

#### Display PDF from external URL

→ **[QUICKSTART.md](QUICKSTART.md)** → "External URL"  
→ **[EXAMPLES.php](EXAMPLES.php)** → Example 3

#### Generate PDF on the fly

→ **[README.md](README.md)** → "Generating PDF on the Fly"  
→ **[EXAMPLES.php](EXAMPLES.php)** → Example 9

#### Customize the viewer appearance

→ **[README.md](README.md)** → "Available Methods"  
→ **[config/filament-pdf-viewer.php](config/filament-pdf-viewer.php)**

#### Upgrade from older version

→ **[MIGRATION.md](MIGRATION.md)**  
→ **[CHANGELOG.md](CHANGELOG.md)**

#### Host PDF.js locally

→ **[SETUP_PDFJS.md](SETUP_PDFJS.md)**  
→ Run `./install-pdfjs.sh`

#### Troubleshoot issues

→ **[KNOWN_ISSUES.md](KNOWN_ISSUES.md)**  
→ **[README.md](README.md)** → "Troubleshooting"

#### See real examples

→ **[EXAMPLES.php](EXAMPLES.php)**  
→ **[README.md](README.md)** → Usage sections

#### Understand what changed

→ **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)**  
→ **[CHANGELOG.md](CHANGELOG.md)**

## 🎯 Quick Links

### Installation

```bash
composer require swelem/filament-pdf-viewer:^3.0
php artisan vendor:publish --tag="filament-pdf-viewer-config"
```

### Basic Usage

```php
use Swelem\FilamentPdfViewer\Forms\Components\PdfViewerField;

PdfViewerField::make('file_path')
    ->label('PDF Document')
    ->minHeight('500px')
```

### Repository

-   GitHub: [Swelem/filament-pdf-viewer](https://github.com/Swelem/filament-pdf-viewer)
-   Issues: [Report a bug](https://github.com/Swelem/filament-pdf-viewer/issues)

## 📄 License

MIT License - See [LICENSE.md](LICENSE.md)

## 🤝 Contributing

Contributions welcome! See the main **[README.md](README.md)** for guidelines.

---

**Need help?** Start with [QUICKSTART.md](QUICKSTART.md) or check [KNOWN_ISSUES.md](KNOWN_ISSUES.md)
