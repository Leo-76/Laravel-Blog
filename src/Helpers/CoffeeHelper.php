<?php

namespace CafeHistoire\CafeBlog\Helpers;

use CafeHistoire\CafeBlog\Models\Post;

class CoffeeHelper
{
    /**
     * Format a coffee origin key as a human-readable label.
     */
    public static function formatOrigin(?string $origin): string
    {
        if (! $origin) {
            return '';
        }

        return Post::ORIGINS[$origin] ?? ucfirst($origin);
    }

    /**
     * Format brewing time in minutes to a human-readable string.
     */
    public static function formatBrewingTime(int $minutes): string
    {
        if ($minutes < 1) {
            return 'Moins d\'une minute';
        }

        if ($minutes === 1) {
            return '1 minute';
        }

        if ($minutes < 60) {
            return "{$minutes} minutes";
        }

        $hours   = intdiv($minutes, 60);
        $remain  = $minutes % 60;
        $label   = $hours === 1 ? '1 heure' : "{$hours} heures";

        return $remain > 0 ? "{$label} {$remain} min" : $label;
    }

    /**
     * Calculate and format reading time from raw content.
     */
    public static function readingTime(string $content): string
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes   = max(1, (int) ceil($wordCount / 200));

        return $minutes === 1
            ? __('cafe-blog::blog.reading_time_one')
            : __('cafe-blog::blog.reading_time_many', ['minutes' => $minutes]);
    }

    /**
     * Return a curated list of famous coffee-related literary quotes.
     */
    public static function famousQuotes(): array
    {
        return [
            ['quote' => 'Le café est une affaire sérieuse.', 'author' => 'Voltaire'],
            ['quote' => 'Sans café, impossible de penser. Sans penser, impossible d\'être un philosophe.', 'author' => 'Immanuel Kant'],
            ['quote' => 'Le café est le pouls de la civilisation.', 'author' => 'Honoré de Balzac'],
            ['quote' => 'Un café bien préparé est le luxe le plus accessible.', 'author' => 'Anonyme'],
            ['quote' => 'La vie est trop courte pour boire du mauvais café.', 'author' => 'Proverbe américain'],
        ];
    }

    /**
     * Get the emoji flag for a coffee-producing country.
     */
    public static function originFlag(string $origin): string
    {
        return match ($origin) {
            'ethiopie'   => '🇪🇹',
            'yemen'      => '🇾🇪',
            'bresil'     => '🇧🇷',
            'colombie'   => '🇨🇴',
            'vietnam'    => '🇻🇳',
            'indonesia'  => '🇮🇩',
            'guatemala'  => '🇬🇹',
            'kenya'      => '🇰🇪',
            'costa_rica' => '🇨🇷',
            'jamaique'   => '🇯🇲',
            default      => '🌍',
        };
    }

    /**
     * Estimated altitude ranges for coffee cultivation by origin (in metres).
     */
    public static function originAltitude(string $origin): string
    {
        return match ($origin) {
            'ethiopie'   => '1 500 – 2 200 m',
            'yemen'      => '1 200 – 2 000 m',
            'colombie'   => '1 200 – 2 000 m',
            'kenya'      => '1 500 – 2 100 m',
            'guatemala'  => '1 300 – 2 000 m',
            'costa_rica' => '1 200 – 1 900 m',
            'jamaique'   => '1 000 – 1 700 m',
            'bresil'     => '600 – 1 200 m',
            'vietnam'    => '500 – 1 500 m',
            'indonesia'  => '1 000 – 1 700 m',
            default      => 'Variable',
        };
    }
}
