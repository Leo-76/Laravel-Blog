<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Authors ───────────────────────────────────────────────────────────
        Schema::create('cafe_blog_authors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('coffee_preference')->nullable();
            $table->string('origin_specialty')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Categories ────────────────────────────────────────────────────────
        Schema::create('cafe_blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B3A2A');
            $table->string('icon', 10)->nullable();
            $table->string('cover_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Posts ─────────────────────────────────────────────────────────────
        Schema::create('cafe_blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption')->nullable();

            $table->foreignId('author_id')->constrained('cafe_blog_authors')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('cafe_blog_categories')->nullOnDelete();

            $table->string('status')->default('draft'); // draft | published | scheduled
            $table->boolean('is_featured')->default(false);

            // Coffee-specific metadata
            $table->string('coffee_origin')->nullable()->index();
            $table->string('coffee_variety')->nullable();
            $table->string('coffee_era')->nullable()->index();
            $table->string('brewing_method')->nullable();

            $table->unsignedSmallInteger('reading_time_minutes')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->timestamp('published_at')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        // ── Related Posts (pivot) ─────────────────────────────────────────────
        Schema::create('cafe_blog_related_posts', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('cafe_blog_posts')->cascadeOnDelete();
            $table->foreignId('related_post_id')->constrained('cafe_blog_posts')->cascadeOnDelete();
            $table->primary(['post_id', 'related_post_id']);
        });

        // ── Comments ──────────────────────────────────────────────────────────
        Schema::create('cafe_blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('cafe_blog_posts')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('cafe_blog_comments')->nullOnDelete();
            $table->string('author_name');
            $table->string('author_email');
            $table->string('author_website')->nullable();
            $table->text('content');
            $table->string('status')->default('pending'); // pending | approved | spam | trash
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_blog_comments');
        Schema::dropIfExists('cafe_blog_related_posts');
        Schema::dropIfExists('cafe_blog_posts');
        Schema::dropIfExists('cafe_blog_categories');
        Schema::dropIfExists('cafe_blog_authors');
    }
};
