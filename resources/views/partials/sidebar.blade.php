{{-- Sidebar partial
     Variables: $categories (Collection), $popular (Collection)
--}}

{{-- Popular posts --}}
@if(isset($popular) && $popular->isNotEmpty())
<div class="cafe-widget">
    <h2 class="cafe-widget__title">🔥 Articles populaires</h2>
    <ol class="cafe-widget__popular-list">
        @foreach($popular as $i => $pop)
        <li class="cafe-widget__popular-item">
            <span class="cafe-widget__popular-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <div>
                <a href="{{ route('cafe-blog.posts.show', $pop) }}" class="cafe-widget__popular-title">
                    {{ $pop->title }}
                </a>
                <span class="cafe-meta__date">{{ $pop->published_at->translatedFormat('j M Y') }}</span>
            </div>
        </li>
        @endforeach
    </ol>
</div>
@endif

{{-- Categories --}}
@if(isset($categories) && $categories->isNotEmpty())
<div class="cafe-widget">
    <h2 class="cafe-widget__title">📂 Catégories</h2>
    <ul class="cafe-widget__category-list">
        @foreach($categories as $cat)
        <li class="cafe-widget__category-item">
            <a href="{{ route('cafe-blog.categories.show', $cat) }}"
               class="cafe-widget__category-link"
               style="--cat-color: {{ $cat->color }}">
                <span class="cafe-widget__category-icon">{{ $cat->icon }}</span>
                <span class="cafe-widget__category-name">{{ $cat->name }}</span>
                <span class="cafe-widget__category-count">{{ $cat->published_posts_count }}</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif

{{-- Origins map widget --}}
<div class="cafe-widget cafe-widget--origins">
    <h2 class="cafe-widget__title">🌍 Origines du café</h2>
    <div class="cafe-origins-grid">
        @foreach(\CafeHistoire\CafeBlog\Models\Post::ORIGINS as $key => $label)
        <a href="{{ route('cafe-blog.origins.show', $key) }}" class="cafe-origin-chip">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

{{-- Historical eras --}}
<div class="cafe-widget">
    <h2 class="cafe-widget__title">📅 Ères historiques</h2>
    <ul class="cafe-widget__era-list">
        @foreach(\CafeHistoire\CafeBlog\Models\Post::ERAS as $key => $label)
        <li>
            <a href="{{ route('cafe-blog.eras.show', $key) }}" class="cafe-widget__era-link">
                {{ $label }}
            </a>
        </li>
        @endforeach
    </ul>
</div>

{{-- Coffee fact of the day --}}
<div class="cafe-widget cafe-widget--fact">
    <h2 class="cafe-widget__title">☕ Le saviez-vous ?</h2>
    @php
    $facts = [
        'Le café est la deuxième marchandise la plus échangée au monde, après le pétrole.',
        'Il existe plus de 100 espèces de caféiers, mais seules Arabica et Robusta sont cultivées à grande échelle.',
        'Un caféier produit environ 2 000 cerises par an, soit 500 g de café torréfié.',
        'Les Finlandais sont les plus grands consommateurs de café par habitant au monde.',
        'La découverte de la caféine est attribuée au chimiste Friedrich Runge en 1819.',
        'Le mot "café" vient de l\'arabe "qahwa", lui-même dérivé du nom de la région de Kaffa en Éthiopie.',
        'Bach a composé une "Cantate du café" pour célébrer la boisson en 1732.',
    ];
    $todayFact = $facts[date('N') % count($facts)];
    @endphp
    <p class="cafe-widget__fact-text">{{ $todayFact }}</p>
</div>
