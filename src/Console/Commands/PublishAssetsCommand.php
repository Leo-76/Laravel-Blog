<?php

namespace CafeHistoire\CafeBlog\Console\Commands;

use Illuminate\Console\Command;

class PublishAssetsCommand extends Command
{
    protected $signature   = 'cafe-blog:publish-assets';
    protected $description = 'Re-publier les assets CSS/JS du package CaféHistoire Blog';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag'   => 'cafe-blog-assets',
            '--force' => true,
        ]);

        $this->info('☕ Assets CaféHistoire re-publiés vers public/vendor/cafe-blog/');

        return self::SUCCESS;
    }
}
