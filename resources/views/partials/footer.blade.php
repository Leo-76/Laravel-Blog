<footer class="cafe-footer" role="contentinfo">
    <div class="cafe-container">

        {{-- Top section --}}
        <div class="cafe-footer__top">

            {{-- Brand --}}
            <div class="cafe-footer__brand">
                <a href="{{ route('cafe-blog.index') }}" class="cafe-navbar__brand" aria-label="Accueil">
                    <span aria-hidden="true">☕</span>
                    <span class="cafe-navbar__logo-main">Café</span><span class="cafe-navbar__logo-accent">Histoire</span>
                </a>
                <p class="cafe-footer__tagline">
                    {{ config('cafe-blog.site_description', 'La culture et l\'histoire du café à travers les siècles.') }}
                </p>
                @if(config('cafe-blog.rss_enabled'))
                <a href="{{ route('cafe-blog.feed') }}" class="cafe-footer__rss" aria-label="Flux RSS">
                    📡 Flux RSS
                </a>
                @endif
            </div>

            {{-- Origines --}}
            <nav class="cafe-footer__nav" aria-label="Origines du café">
                <h3 class="cafe-footer__nav-title">🌍 Origines</h3>
                <ul>
                    @foreach(\CafeHistoire\CafeBlog\Models\Post::ORIGINS as $key => $label)
                    <li><a href="{{ route('cafe-blog.origins.show', $key) }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            {{-- Époques --}}
            <nav class="cafe-footer__nav" aria-label="Époques historiques">
                <h3 class="cafe-footer__nav-title">📅 Époques</h3>
                <ul>
                    @foreach(\CafeHistoire\CafeBlog\Models\Post::ERAS as $key => $label)
                    <li><a href="{{ route('cafe-blog.eras.show', $key) }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            {{-- Newsletter --}}
            <div class="cafe-footer__newsletter">
                <h3 class="cafe-footer__nav-title">✉️ Newsletter</h3>
                <p>Recevez nos articles et l'histoire du café directement dans votre tasse.</p>
                <form class="cafe-footer__newsletter-form" action="#" method="POST">
                    @csrf
                    <label for="footer-email" class="sr-only">Votre adresse e-mail</label>
                    <input type="email" id="footer-email" name="email"
                           placeholder="votre@email.com"
                           class="cafe-input"
                           required>
                    <button type="submit" class="cafe-btn cafe-btn--caramel">S'inscrire</button>
                </form>
            </div>

        </div>{{-- /.cafe-footer__top --}}

        {{-- Bottom bar --}}
        <div class="cafe-footer__bottom">
            <p>
                &copy; {{ date('Y') }} {{ config('cafe-blog.site_name', 'CaféHistoire') }}.
                Package Laravel par
                <a href="https://packagist.org/packages/cafehistoire/laravel-cafe-blog" target="_blank" rel="noopener">
                    cafehistoire/laravel-cafe-blog
                </a>.
                Licence MIT.
            </p>
            <div class="cafe-footer__bottom-links">
                @if(config('cafe-blog.sitemap_enabled'))
                <a href="{{ route('cafe-blog.sitemap') }}">Sitemap</a>
                @endif
            </div>
        </div>

    </div>{{-- /.cafe-container --}}
</footer>
