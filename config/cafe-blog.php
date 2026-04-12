<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */
    'prefix'     => 'blog',
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel
    |--------------------------------------------------------------------------
    */
    'admin_enabled'     => true,
    'admin_prefix'      => 'blog/admin',
    'admin_middleware'  => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'posts_per_page' => 12,

    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    */
    'comments_enabled'       => true,
    'auto_approve_comments'  => false,
    'comment_notification'   => true,
    'comment_notification_email' => env('CAFE_BLOG_COMMENT_EMAIL', null),

    /*
    |--------------------------------------------------------------------------
    | Media & Images
    |--------------------------------------------------------------------------
    */
    'disk'                    => env('CAFE_BLOG_DISK', 'public'),
    'image_max_width'         => 1920,
    'thumbnail_width'         => 800,
    'thumbnail_height'        => 450,
    'thumbnail_quality'       => 85,

    /*
    |--------------------------------------------------------------------------
    | SEO Defaults
    |--------------------------------------------------------------------------
    */
    'site_name'        => env('APP_NAME', 'CaféHistoire'),
    'site_description' => 'La culture et l\'histoire du café à travers les siècles.',
    'default_og_image' => null,

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'rss_enabled'           => true,
    'sitemap_enabled'       => true,
    'reading_time_enabled'  => true,
    'view_count_enabled'    => true,
    'related_posts_enabled' => true,
    'social_sharing_enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Social Sharing Networks
    |--------------------------------------------------------------------------
    */
    'social_networks' => ['twitter', 'facebook', 'linkedin', 'whatsapp'],

    /*
    |--------------------------------------------------------------------------
    | Theme Options
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'color_primary'   => '#6B3A2A',   // Espresso brown
        'color_secondary' => '#D4A96A',   // Caramel gold
        'color_accent'    => '#2D5A27',   // Coffee leaf green
        'font_display'    => 'Playfair Display',
        'font_body'       => 'Lora',
        'posts_layout'    => 'grid',      // grid | list | masonry
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache_enabled'  => true,
    'cache_ttl'      => 3600, // seconds

];
