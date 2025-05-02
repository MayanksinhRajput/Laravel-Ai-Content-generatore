<?php

namespace mayank\AiContentGenerator;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentGenerator
{
    /**
     * API Key for Gemini API
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API Base URL
     * 
     * @var string
     */
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1';

    /**
     * Create a new AI Content Generator instance.
     *
     * @param  string  $apiKey
     * @return void
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Generate content based on a prompt.
     *
     * @param  string  $prompt
     * @param  array  $options
     * @return string
     * @throws \Exception If content generation fails
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        if (empty($prompt)) {
            throw new Exception("Prompt cannot be empty");
        }

        try {
            // Using gemini-1.5-flash as the default model (faster and more cost-effective)
            $url = "{$this->baseUrl}/models/gemini-1.5-flash:generateContent";
            
            $payload = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? 1024,
                    'topP' => $options['top_p'] ?? 0.9,
                    'topK' => $options['top_k'] ?? 40,
                ]
            ];
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url . "?key={$this->apiKey}", $payload);
            
            if (!$response->successful()) {
                throw new Exception("API request failed: " . $response->body());
            }
            
            $data = $response->json();
            
            if (empty($data['candidates'][0]['content']['parts'][0]['text'] ?? null)) {
                throw new Exception("Empty response received from Gemini API: " . json_encode($data));
            }
            
            return $data['candidates'][0]['content']['parts'][0]['text'];
        } catch (Exception $e) {
            Log::error('Gemini API error: ' . $e->getMessage(), [
                'prompt' => $prompt,
                'options' => $options
            ]);
            
            throw new Exception("Failed to generate content: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Generate content with an image.
     *
     * @param  string  $prompt
     * @param  string  $imagePath
     * @param  array  $options
     * @return string
     * @throws \Exception If content generation fails
     */
    public function generateContentWithImage(string $prompt, string $imagePath, array $options = []): string
    {
        if (empty($prompt)) {
            throw new Exception("Prompt cannot be empty");
        }
        
        if (empty($imagePath)) {
            throw new Exception("Image path cannot be empty");
        }

        try {
            // Normalize the image path to ensure consistent directory separators
            $imagePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $imagePath);
            
            if (!file_exists($imagePath)) {
                throw new Exception("Image file not found: $imagePath");
            }
            
            if (!is_readable($imagePath)) {
                throw new Exception("Image file is not readable: $imagePath");
            }
            
            $imageContent = @file_get_contents($imagePath);
            if ($imageContent === false) {
                throw new Exception("Failed to read image file: $imagePath");
            }
            
            $imageData = base64_encode($imageContent);
            if (empty($imageData)) {
                throw new Exception("Failed to encode image data");
            }
            
            // Using gemini-1.5-flash as it supports both text and vision
            $url = "{$this->baseUrl}/models/gemini-1.5-flash:generateContent";
            
            $payload = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/jpeg',
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? 1024,
                    'topP' => $options['top_p'] ?? 0.9,
                    'topK' => $options['top_k'] ?? 40,
                ]
            ];
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url . "?key={$this->apiKey}", $payload);
            
            if (!$response->successful()) {
                throw new Exception("API request failed: " . $response->body());
            }
            
            $data = $response->json();
            
            if (empty($data['candidates'][0]['content']['parts'][0]['text'] ?? null)) {
                throw new Exception("Empty response received from Gemini API: " . json_encode($data));
            }
            
            return $data['candidates'][0]['content']['parts'][0]['text'];
        } catch (Exception $e) {
            Log::error('Gemini API image error: ' . $e->getMessage(), [
                'prompt' => $prompt,
                'imagePath' => $imagePath,
                'options' => $options
            ]);
            
            throw new Exception("Failed to generate content from image: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Generate multiple variations of content.
     *
     * @param  string  $prompt
     * @param  int  $count
     * @return array
     * @throws \Exception If content generation fails
     */
    public function generateVariations(string $prompt, int $count = 3): array
    {
        if (empty($prompt)) {
            throw new Exception("Prompt cannot be empty");
        }
        
        if ($count < 1) {
            throw new Exception("Count must be at least 1");
        }

        try {
            $variations = [];
            $errors = [];
            
            for ($i = 0; $i < $count; $i++) {
                try {
                    $content = $this->generateContent($prompt, [
                        'temperature' => 0.7 + (0.1 * $i), // Slightly vary temperature for each variation
                    ]);
                    
                    if (empty($content)) {
                        $errors[] = "Empty response received for variation " . ($i + 1);
                        continue;
                    }
                    
                    $variations[] = $content;
                } catch (Exception $e) {
                    $errors[] = "Error generating variation " . ($i + 1) . ": " . $e->getMessage();
                    continue;
                }
            }
            
            if (empty($variations)) {
                if (!empty($errors)) {
                    throw new Exception("Failed to generate any valid variations: " . implode("; ", $errors));
                } else {
                    throw new Exception("Failed to generate any valid variations");
                }
            }
            
            return $variations;
        } catch (Exception $e) {
            Log::error('Gemini API variations error: ' . $e->getMessage(), [
                'prompt' => $prompt,
                'count' => $count
            ]);
            
            throw new Exception("Failed to generate content variations: " . $e->getMessage(), 0, $e);
        }
    }
} 