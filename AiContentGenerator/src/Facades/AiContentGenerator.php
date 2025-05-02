<?php

namespace mayank\AiContentGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string generateContent(string $prompt, array $options = [])
 * @method static string generateContentWithImage(string $prompt, string $imagePath, array $options = [])
 * @method static array generateVariations(string $prompt, int $count = 3)
 * 
 * @see \mayank\AiContentGenerator\AiContentGenerator
 */
class AiContentGenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ai-content-generator';
    }
} 