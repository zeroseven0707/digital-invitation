<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SanitizeHtml implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            return;
        }

        // Check for potentially dangerous HTML tags and scripts
        $dangerousPatterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
            '/javascript:/i',
            '/on\w+\s*=/i', // onclick, onload, etc.
            '/<embed\b[^>]*>/i',
            '/<object\b[^>]*>/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail("The {$attribute} contains potentially dangerous content.");
                return;
            }
        }
    }

    /**
     * Sanitize the input by removing dangerous HTML
     */
    public static function sanitize(string $input): string
    {
        // Strip all HTML tags except safe ones
        $allowedTags = '<p><br><strong><em><u><a><ul><ol><li>';
        $sanitized = strip_tags($input, $allowedTags);

        // Remove any remaining javascript: protocols
        $sanitized = preg_replace('/javascript:/i', '', $sanitized);

        // Remove event handlers
        $sanitized = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $sanitized);

        return $sanitized;
    }
}
