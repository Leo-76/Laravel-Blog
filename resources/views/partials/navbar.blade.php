<nav class="cafe-navbar" role="navigation" aria-label="Navigation principale">
    <div class="cafe-container">
        <div class="cafe-navbar__inner">

            {{-- Logo / Brand --}}
            <a href="{{ route('cafe-blog.index') }}" class="cafe-navbar__brand" aria-label="Accueil CaféHistoire">
                <span class="cafe-navbar__logo-icon" aria-hidden="true">☕</span>
                <span class="cafe-navbar__logo-text">
                    <span class="cafe-navbar__logo-main">Café</span><span class="cafe-navbar__logo-accent">Histoire</span>
                </span>
            </a>

            {{-- Desktop Menu --}}
            <ul class="cafe-navbar__menu" role="list">
                <li class="cafe-navbar__item cafe-navbar__item--dropdown">
                    <button class="cafe-navbar__link cafe-navbar__dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                        Origines <span aria-hidden="true">▾</span>
                    </button>
                    <ul class="cafe-navbar__dropdown" role="menu">
                        @foreach(\CafeHistoire\CafeBlog\Models\Post::ORIGINS as $key => $label)
                        <li role="none">
                            <a href="{{ route('cafe-blog.origins.show', $key) }}" class="cafe-navbar__dropdown-item" role="menuitem">
                                {{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <li class="cafe-navbar__item cafe-navbar__item--dropdown">
                    <button class="cafe-navbar__link cafe-navbar__dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                        Époques <span aria-hidden="true">▾</span>
                    </button>
                    <ul class="cafe-navbar__dropdown" role="menu">
                        @foreach(\CafeHistoire\CafeBlog\Models\Post::ERAS as $key => $label)
                        <li role="none">
                            <a href="{{ route('cafe-blog.eras.show', $key) }}" class="cafe-navbar__dropdown-item" role="menuitem">
                                {{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <li class="cafe-navbar__item">
                    <a href="{{ route('cafe-blog.index') }}" class="cafe-navbar__link">Articles</a>
                </li>
            </ul>

            {{-- Search --}}
            <form action="{{ route('cafe-blog.search') }}" method="GET" class="cafe-navbar__search" role="search">
                <label for="nav-search" class="sr-only">Rechercher</label>
                <input
                    type="search"
                    id="nav-search"
                    name="q"
                    placeholder="Rechercher…"
                    class="cafe-navbar__search-input"
                    value="{{ request('q') }}"
                    aria-label="Rechercher dans le blog"
                >
                <button type="submit" class="cafe-navbar__search-btn" aria-label="Lancer la recherche">
                    🔍
                </button>
            </form>

            {{-- Mobile menu toggle --}}
            <button class="cafe-navbar__toggle" id="navToggle"
                    aria-controls="mobileMenu"
                    aria-expanded="false"
                    aria-label="Ouvrir le menu">
                <span class="cafe-navbar__hamburger"></span>
            </button>

        </div>{{-- /.cafe-navbar__inner --}}
    </div>{{-- /.cafe-container --}}

    {{-- Mobile Menu --}}
    <div class="cafe-navbar__mobile" id="mobileMenu" aria-hidden="true">
        <div class="cafe-navbar__mobile-inner">
            <a href="{{ route('cafe-blog.index') }}" class="cafe-navbar__mobile-link">🏠 Accueil</a>
            <hr class="cafe-navbar__mobile-sep">
            <strong class="cafe-navbar__mobile-group">🌍 Origines</strong>
            @foreach(\CafeHistoire\CafeBlog\Models\Post::ORIGINS as $key => $label)
            <a href="{{ route('cafe-blog.origins.show', $key) }}" class="cafe-navbar__mobile-link cafe-navbar__mobile-link--sub">{{ $label }}</a>
            @endforeach
            <hr class="cafe-navbar__mobile-sep">
            <strong class="cafe-navbar__mobile-group">📅 Époques</strong>
            @foreach(\CafeHistoire\CafeBlog\Models\Post::ERAS as $key => $label)
            <a href="{{ route('cafe-blog.eras.show', $key) }}" class="cafe-navbar__mobile-link cafe-navbar__mobile-link--sub">{{ $label }}</a>
            @endforeach
            <hr class="cafe-navbar__mobile-sep">
            <form action="{{ route('cafe-blog.search') }}" method="GET" class="cafe-navbar__mobile-search">
                <input type="search" name="q" placeholder="Rechercher…" class="cafe-input">
                <button type="submit" class="cafe-btn cafe-btn--primary cafe-btn--sm">Chercher</button>
            </form>
        </div>
    </div>
</nav>
