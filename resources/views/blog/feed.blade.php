<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('cafe-blog.site_name', 'CaféHistoire') }}</title>
        <link>{{ route('cafe-blog.index') }}</link>
        <description>{{ config('cafe-blog.site_description', '') }}</description>
        <language>fr-FR</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        <atom:link href="{{ route('cafe-blog.feed') }}" rel="self" type="application/rss+xml"/>

        @foreach($posts as $post)
        <item>
            <title><![CDATA[{{ $post->title }}]]></title>
            <link>{{ route('cafe-blog.posts.show', $post) }}</link>
            <guid isPermaLink="true">{{ route('cafe-blog.posts.show', $post) }}</guid>
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            @if($post->author)
            <author>{{ $post->author->email ?? '' }} ({{ $post->author->name }})</author>
            @endif
            @if($post->category)
            <category><![CDATA[{{ $post->category->name }}]]></category>
            @endif
            <description><![CDATA[{{ $post->excerpt }}]]></description>
            <content:encoded><![CDATA[{!! $post->content !!}]]></content:encoded>
        </item>
        @endforeach
    </channel>
</rss>
