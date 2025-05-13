# Laravel AI Content Generator

A powerful Laravel package for AI-powered content generation using Google's Gemini API. This package makes it easy to generate various types of content including blog posts, product descriptions, and social media posts.

## Installation

You can install the package via composer:

```bash
composer require mayank/ai-content-generator
```

After installing the package, publish the configuration file:

```bash
php artisan vendor:publish --provider="mayank\AiContentGenerator\AiContentGeneratorServiceProvider" --tag="ai-content-generator-config"
```

Add your Gemini API key to your `.env` file:

```
GEMINI_API_KEY=your-api-key-here
```

## Usage

### Basic Usage

You can use the package in two ways:

1. Using the Facade:

```php
use mayank\AiContentGenerator\AiContentGeneratorFacade as AI;

// Generate content
$content = AI::generateContent('Write about artificial intelligence');

// Generate content with image
$content = AI::generateContentWithImage('Describe this image', 'path/to/image.jpg');

// Generate multiple variations
$variations = AI::generateVariations('Write a tagline for a tech company', 3);
```

2. Using Dependency Injection:

```php
use mayank\AiContentGenerator\AiContentGenerator;

class YourController extends Controller
{
    protected $ai;

    public function __construct(AiContentGenerator $ai)
    {
        $this->ai = $ai;
    }

    public function generate()
    {
        $content = $this->ai->generateContent('Your prompt here');
    }
}
```

### Helper Methods

The package includes several helper methods for common content generation tasks:

```php
// Generate a blog post
$blogPost = AI::generateBlogPost('The Future of AI');

// Generate a product description
$description = AI::generateProductDescription('Smart Home Assistant');

// Generate a social media post
$post = AI::generateSocialMediaPost('New Product Launch', 'twitter');
```

### Configuration

The package configuration file (`config/ai-content-generator.php`) includes the following options:

```php
return [
    'gemini_api_key' => env('GEMINI_API_KEY', ''),
    'default_model' => env('GEMINI_DEFAULT_MODEL', 'gemini-pro'),
    'parameters' => [
        'temperature' => 0.7,
        'max_output_tokens' => 1024,
        'top_p' => 0.9,
        'top_k' => 40,
    ],
];
```

### Advanced Usage

You can customize the generation parameters for each request:

```php
$content = AI::generateContent('Your prompt', [
    'temperature' => 0.8,
    'max_tokens' => 2048,
    'top_p' => 0.95,
    'top_k' => 50,
]);
```

## Error Handling

The package includes comprehensive error handling:

```php
try {
    $content = AI::generateContent('Your prompt');
} catch (\Exception $e) {
    // Handle the error
    Log::error('Content generation failed: ' . $e->getMessage());
}
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@example.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 