<?php

namespace CafeHistoire\CafeBlog\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature   = 'cafe-blog:install {--seed : Seed demo content}';
    protected $description = 'Install the CaféHistoire Blog package (publish assets, run migrations)';

    public function handle(): int
    {
        $this->info('');
        $this->info('  ☕  CaféHistoire — Installation du blog');
        $this->info('  ─────────────────────────────────────────');
        $this->info('');

        // 1. Publish config
        $this->call('vendor:publish', [
            '--tag'   => 'cafe-blog-config',
            '--force' => false,
        ]);
        $this->info('  ✓ Configuration publiée → config/cafe-blog.php');

        // 2. Publish assets
        $this->call('vendor:publish', [
            '--tag'   => 'cafe-blog-assets',
            '--force' => true,
        ]);
        $this->info('  ✓ Assets publiés → public/vendor/cafe-blog/');

        // 3. Run migrations
        $this->call('migrate', ['--force' => true]);
        $this->info('  ✓ Migrations exécutées');

        // 4. Publish and link storage
        if (! file_exists(public_path('storage'))) {
            $this->call('storage:link');
            $this->info('  ✓ Lien symbolique storage créé');
        }

        // 5. Optional seeding
        if ($this->option('seed')) {
            $this->call('db:seed', [
                '--class' => \CafeHistoire\CafeBlog\Database\Seeders\CafeBlogSeeder::class,
                '--force' => true,
            ]);
            $this->info('  ✓ Données de démonstration insérées');
        }

        $this->info('');
        $this->line('  <fg=green>Installation terminée !</>');
        $this->line('  Blog accessible sur : <fg=cyan>' . config('cafe-blog.prefix', 'blog') . '</>');
        $this->info('');
        $this->line('  Commandes utiles :');
        $this->line('    php artisan cafe-blog:publish-assets  → Re-publier les assets CSS/JS');
        $this->line('    php artisan db:seed --class=CafeBlogSeeder  → Insérer des articles de démo');
        $this->info('');

        return self::SUCCESS;
    }
}
