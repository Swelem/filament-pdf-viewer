# Known Issues & Notes

## Type Hint Warnings

There are some PHPStan/IDE warnings about type hints in the original codebase:

```php
// In getFileUrl() method
return $this->evaluate($this->fileUrl);
// PHPStan expects: string|null
// May return: Closure (technically possible but not in practice)
```

These warnings existed in the original package and are not introduced by the PDF.js enhancement. They don't affect runtime behavior because:

1. The `$fileUrl` property is typed as `string|Closure`
2. The `evaluate()` method from Filament resolves Closures
3. In practice, this always returns `string|null`

## Recommendations

### For Production Use

1. **Test thoroughly** - Test all PDF sources (file paths, URLs, base64)
2. **Use local PDF.js** - Download and host PDF.js locally for better reliability
3. **Monitor CDN** - If using CDN fallback, monitor availability
4. **CORS configuration** - Ensure proper CORS headers for external PDFs

### For Development

1. **Use CDN during development** - Faster setup, no download needed
2. **Enable error reporting** - Check browser console for PDF.js errors
3. **Test with different scales** - Verify zoom levels work as expected

## Browser Compatibility

### PDF.js Mode

-   ✅ Chrome/Edge 90+
-   ✅ Firefox 88+
-   ✅ Safari 14+
-   ✅ Opera 76+

### Native Mode (Fallback)

-   Depends on browser's native PDF viewer
-   May vary in appearance across browsers

## Performance Considerations

### PDF.js Mode

-   **Pros**: Consistent rendering, more features
-   **Cons**: Larger page size (~500KB for library)
-   **Best for**: Interactive documents, custom styling

### Native Mode

-   **Pros**: Smaller page size, faster initial load
-   **Cons**: Limited control, browser-dependent
-   **Best for**: Simple document viewing

## Security Notes

1. **File Storage**: Always use appropriate visibility settings
2. **Temporary URLs**: Consider timeout for sensitive documents
3. **Base64 Data**: Be mindful of payload size in database
4. **External URLs**: Validate sources to prevent XSS

## Future Improvements

Ideas for future versions:

-   Text search functionality
-   Multi-page thumbnail view
-   Annotation tools
-   Print-friendly mode
-   Mobile gesture support
-   Accessibility enhancements

## Getting Help

-   **Issues**: [GitHub Issues](https://github.com/Swelem/filament-pdf-viewer/issues)
-   **Discussions**: [GitHub Discussions](https://github.com/Swelem/filament-pdf-viewer/discussions)
-   **Documentation**: Check README.md, EXAMPLES.php, and MIGRATION.md

## Contributing

Contributions welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Add tests if applicable
4. Submit a pull request

## Version Support

| Version | PHP  | Laravel | Filament | Support | Package Name                       |
| ------- | ---- | ------- | -------- | ------- | ---------------------------------- |
| 3.x     | 8.2+ | 11.x    | 4.x      | Active  | swelem/filament-pdf-viewer         |
| 2.x     | 8.2+ | 11.x    | 4.x      | EOL     | joaopaulolndev/filament-pdf-viewer |
| 1.x     | 8.1+ | 10.x    | 3.x      | EOL     | joaopaulolndev/filament-pdf-viewer |
