<?php

namespace mayank\AiContentGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generateContent(string $prompt, array $options = [])
 * @method static string generateContentWithImage(string $prompt, string $imagePath, array $options = [])
 * @method static array generateVariations(string $prompt, int $count = 3)
 * @method static string generateBlogPost(string $topic, array $options = [])
 * @method static string generateProductDescription(string $product, array $options = [])
 * @method static string generateSocialMediaPost(string $topic, string $platform, array $options = [])
 * 
 * @see \mayank\AiContentGenerator\AiContentGenerator
 */
class AiContentGeneratorFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ai-content-generator';
    }
} 