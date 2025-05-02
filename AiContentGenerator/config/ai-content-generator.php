<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gemini API Key
    |--------------------------------------------------------------------------
    |
    | This is the API key for the Google Gemini AI service. You can get one
    | by visiting https://ai.google.dev/ and creating an API key.
    |
    */
    'gemini_api_key' => env('GEMINI_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Model
    |--------------------------------------------------------------------------
    |
    | This is the default model to use for content generation.
    | Available models: gemini-pro, gemini-pro-vision
    |
    */
    'default_model' => env('GEMINI_DEFAULT_MODEL', 'gemini-pro'),

    /*
    |--------------------------------------------------------------------------
    | Default Generation Parameters
    |--------------------------------------------------------------------------
    |
    | These are the default parameters for content generation.
    |
    */
    'parameters' => [
        'temperature' => 0.7,
        'max_output_tokens' => 1024,
        'top_p' => 0.9,
        'top_k' => 40,
    ],
]; 