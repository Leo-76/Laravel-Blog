# ☕ CaféHistoire — Laravel Blog Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cafehistoire/laravel-cafe-blog.svg?style=flat-square)](https://packagist.org/packages/cafehistoire/laravel-cafe-blog)
[![Tests](https://img.shields.io/github/actions/workflow/status/cafehistoire/laravel-cafe-blog/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cafehistoire/laravel-cafe-blog/actions)
[![License](https://img.shields.io/packagist/l/cafehistoire/laravel-cafe-blog.svg?style=flat-square)](LICENSE.md)
[![PHP](https://img.shields.io/packagist/php-v/cafehistoire/laravel-cafe-blog.svg?style=flat-square)](composer.json)

Un package Laravel complet pour créer un blog élégant dédié à la **culture et l'histoire du café**. Prêt à l'emploi, entièrement personnalisable, optimisé SEO.

---

## Fonctionnalités

- **Blog complet** — Articles, catégories, tags, auteurs, commentaires
-  **Filtres par origine** — Éthiopie, Yémen, Brésil, Kenya, Colombie…
- **Filtres par époque** — Antiquité, Moyen Âge, Renaissance, Époque Moderne…
- **Thème espresso** — Design magazine avec Playfair Display + Lora, 100 % CSS custom properties
-  **Profils auteurs** — Bio, préférence café, origine de spécialité
- **Commentaires** — Avec modération et réponses imbriquées
-  **Recherche full-text** — Parmi tous les articles
- **Flux RSS** — Flux XML standard
- **Sitemap XML** — Pour les moteurs de recherche
-  **API REST JSON** — `/api/blog/posts`, `/api/blog/search`, etc.
- **Admin intégré** — Tableau de bord, CRUD articles/catégories/auteurs/commentaires
- **Temps de lecture** — Calculé automatiquement
- **Compteur de vues** — Throttlé par session
-  **Tests Pest** — Feature + Unit tests inclus
- **i18n** — Traductions FR + EN fournies

---

## Prérequis

| Dépendance | Version |
|---|---|
| PHP | ^8.1 |
| Laravel | ^10.0 \| ^11.0 |
| Spatie Tags | ^4.3 |
| Spatie Sluggable | ^3.4 |
| League CommonMark | ^2.4 |

---

##  Installation

### 1. Via Composer

```bash
composer require cafehistoire/laravel-cafe-blog
```

### 2. Installation automatique

```bash
php artisan cafe-blog:install
```

Cette commande :
- Publie la configuration (`config/cafe-blog.php`)
- Publie les assets CSS/JS (`public/vendor/cafe-blog/`)
- Exécute les migrations
- Crée le lien symbolique storage

### 3. Avec données de démo

```bash
php artisan cafe-blog:install --seed
```

Insère 3 auteurs, 8 catégories et 3 articles d'exemple sur l'histoire du café.

---

## ⚙️ Configuration

Publiez et éditez le fichier de configuration :

```bash
php artisan vendor:publish --tag=cafe-blog-config
```

```php
// config/cafe-blog.php

return [
    'prefix'     => 'blog',           // URL du blog : /blog
    'middleware' => ['web'],

    'posts_per_page'         => 12,
    'comments_enabled'       => true,
    'auto_approve_comments'  => false,

    'rss_enabled'      => true,
    'sitemap_enabled'  => true,
    'view_count_enabled' => true,

    'theme' => [
        'color_primary'   => '#6B3A2A',  // Espresso brown
        'color_secondary' => '#D4A96A',  // Caramel gold
        'color_accent'    => '#2D5A27',  // Leaf green
        'font_display'    => 'Playfair Display',
        'font_body'       => 'Lora',
        'posts_layout'    => 'grid',     // grid | list | masonry
    ],
];
```

---

## 🛣️ Routes générées

| Méthode | URL | Nom de route |
|---|---|---|
| GET | `/blog` | `cafe-blog.index` |
| GET | `/blog/{slug}` | `cafe-blog.posts.show` |
| POST | `/blog/{slug}/commentaire` | `cafe-blog.posts.comment` |
| GET | `/blog/categorie/{slug}` | `cafe-blog.categories.show` |
| GET | `/blog/origine/{origin}` | `cafe-blog.origins.show` |
| GET | `/blog/epoque/{era}` | `cafe-blog.eras.show` |
| GET | `/blog/auteur/{slug}` | `cafe-blog.authors.show` |
| GET | `/blog/recherche` | `cafe-blog.search` |
| GET | `/blog/feed.rss` | `cafe-blog.feed` |
| GET | `/blog/sitemap.xml` | `cafe-blog.sitemap` |
| GET | `/api/blog/posts` | `cafe-blog.api.posts.index` |
| GET | `/api/blog/posts/{slug}` | `cafe-blog.api.posts.show` |
| GET | `/api/blog/categories` | `cafe-blog.api.categories` |
| GET | `/api/blog/search` | `cafe-blog.api.search` |

**Admin (authentification requise) :**

| URL | Description |
|---|---|
| `/blog/admin` | Tableau de bord |
| `/blog/admin/posts` | CRUD articles |
| `/blog/admin/categories` | CRUD catégories |
| `/blog/admin/authors` | CRUD auteurs |
| `/blog/admin/comments` | Modération commentaires |

---

## 🎨 Personnalisation des vues

Publiez les vues pour les personnaliser :

```bash
php artisan vendor:publish --tag=cafe-blog-views
```

Les vues sont copiées dans `resources/views/vendor/cafe-blog/`.

---

## Facade & Service

```php
use CafeHistoire\CafeBlog\Facades\CafeBlog;

// Derniers articles publiés
$posts = CafeBlog::latestPosts(perPage: 8);

// Articles mis en avant
$featured = CafeBlog::featuredPosts(limit: 3);

// Articles les plus lus
$popular = CafeBlog::popularPosts(limit: 5);

// Articles par origine
$ethiopiens = CafeBlog::postsByOrigin('ethiopie');

// Toutes les catégories avec comptage
$categories = CafeBlog::allCategories();

// Origines avec comptage d'articles
$origins = CafeBlog::coffeeOriginsWithCounts();
```

---

##  Directives Blade

```blade
{{-- Temps de lecture --}}
@readingTime($post->content)

{{-- Label d'origine formaté --}}
@coffeeOrigin($post->coffee_origin)
```

---

##  Tests

```bash
composer test
```

La suite inclut des tests Feature (routes, vues, commentaires, API) et des tests Unit (modèles, scopes, accessors, service).

---

##  Publication partielle

```bash
# Config seulement
php artisan vendor:publish --tag=cafe-blog-config

# Assets CSS/JS seulement
php artisan vendor:publish --tag=cafe-blog-assets

# Vues seulement
php artisan vendor:publish --tag=cafe-blog-views

# Migrations seulement
php artisan vendor:publish --tag=cafe-blog-migrations

# Traductions seulement
php artisan vendor:publish --tag=cafe-blog-lang

# Tout en même temps
php artisan vendor:publish --tag=cafe-blog
```

---

##  Origines du café supportées

Éthiopie · Yémen · Brésil · Colombie · Vietnam · Indonésie · Guatemala · Kenya · Costa Rica · Jamaïque · Mondial

##  Époques historiques supportées

- Antiquité (avant 600)
- Moyen Âge (600–1400)
- Renaissance (1400–1700)
- Époque Moderne (1700–1900)
- Époque Contemporaine (1900+)

---

##  Licence

MIT — voir [LICENSE.md](LICENSE.md)

---

## 🤝 Contribution

Les contributions sont les bienvenues ! Consultez [CONTRIBUTING.md](CONTRIBUTING.md).

1. Forkez le dépôt
2. Créez votre branche (`git checkout -b feature/ma-fonctionnalite`)
3. Committez vos changements (`git commit -m 'feat: ajouter ma fonctionnalité'`)
4. Pushez (`git push origin feature/ma-fonctionnalite`)
5. Ouvrez une Pull Request

---

*Fait avec ☕ et passion pour l'histoire du café.*
