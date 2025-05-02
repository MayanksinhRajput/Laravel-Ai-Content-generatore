# AI Content Generator for Laravel

A Laravel package for AI-powered content generation using Google Gemini.

## Installation

You can install the package via composer:

```bash
composer require mayank/ai-content-generator
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=ai-content-generator-config
```

Add your Gemini API key to your `.env` file:

```
GEMINI_API_KEY=your-api-key
```

## Usage

### Generate Content

```php
use mayank\AiContentGenerator\Facades\AiContentGenerator;

// Simple content generation
$content = AiContentGenerator::generateContent('Write a blog post about Laravel and AI');

// Generate content with an image
$content = AiContentGenerator::generateContentWithImage(
    'Describe this image',
    storage_path('app/images/example.jpg')
);

// Generate multiple variations
$variations = AiContentGenerator::generateVariations(
    'Write a tagline for a tech company',
    3 // Number of variations
);
```

### Image-Based Content Generation

For image-based content generation, make sure the image file exists at the specified path. The package requires the full path to the image file.

```php
// Make sure the directory exists
Storage::makeDirectory('temp');

// Save the image (example for handling uploads)
$path = $request->file('image')->store('temp');
$fullPath = storage_path('app/' . $path);

// Generate content with image
$content = AiContentGenerator::generateContentWithImage(
    'Analyze this image and describe what you see',
    $fullPath
);
```

> **Note**: Make sure the storage directories exist and have appropriate permissions. For image uploads, you need to create the necessary directories first (e.g., `storage/app/temp`).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 