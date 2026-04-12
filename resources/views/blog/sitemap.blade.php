<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Blog homepage --}}
    <url>
        <loc>{{ route('cafe-blog.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('cafe-blog.categories.show', $category) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Origins --}}
    @foreach(\CafeHistoire\CafeBlog\Models\Post::ORIGINS as $key => $label)
    <url>
        <loc>{{ route('cafe-blog.origins.show', $key) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Eras --}}
    @foreach(\CafeHistoire\CafeBlog\Models\Post::ERAS as $key => $label)
    <url>
        <loc>{{ route('cafe-blog.eras.show', $key) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Posts --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ route('cafe-blog.posts.show', $post) }}</loc>
        <lastmod>{{ $post->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

</urlset>
