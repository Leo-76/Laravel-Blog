# Guide de contribution — CaféHistoire Blog

Merci de l'intérêt que vous portez à ce projet ! 

## Prérequis

- PHP 8.1+
- Composer
- SQLite (pour les tests)

## Installation locale

```bash
git clone https://github.com/cafehistoire/laravel-cafe-blog.git
cd laravel-cafe-blog
composer install
```

## Lancer les tests

```bash
composer test
```

## Standards de code

- PSR-12 pour le style PHP
- Noms de variables et commentaires en anglais dans le code source
- Vues et chaînes visibles en français (avec traductions anglaises correspondantes)
- Chaque nouvelle fonctionnalité doit être accompagnée de tests Pest

## Processus de contribution

1. Ouvrez une issue pour discuter de votre idée avant de commencer
2. Forkez le dépôt et créez une branche depuis `main`
3. Nommez vos branches de façon descriptive :
   - `feature/nom-de-la-fonctionnalite`
   - `fix/description-du-bug`
   - `docs/mise-a-jour-readme`
4. Écrivez des tests pour toute nouvelle fonctionnalité
5. Assurez-vous que tous les tests passent : `composer test`
6. Soumettez une Pull Request avec une description claire

## Conventions de commit

Nous suivons [Conventional Commits](https://www.conventionalcommits.org/) :

```
feat: ajouter filtre par méthode de préparation
fix: corriger le compteur de vues en mode session
docs: améliorer la section installation du README
test: ajouter tests pour le scope byEra
refactor: extraire la logique SEO dans un trait
```

## Structure des fichiers

```
laravel-cafe-blog/
├── src/
│   ├── CafeBlogServiceProvider.php
│   ├── Console/Commands/
│   ├── Facades/
│   ├── Helpers/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   └── Api/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   └── Services/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── lang/{fr,en}/
│   └── views/
│       ├── admin/
│       ├── blog/
│       ├── components/
│       ├── layouts/
│       └── partials/
├── public/{css,js}/
├── routes/{web,api}.php
├── config/cafe-blog.php
└── tests/{Feature,Unit}/
```

## Rapport de bugs

Utilisez les [GitHub Issues](https://github.com/cafehistoire/laravel-cafe-blog/issues) en incluant :
- Version PHP et Laravel utilisées
- Description du comportement attendu vs observé
- Étapes pour reproduire le problème
- Logs d'erreur si disponibles

## Licence

En contribuant, vous acceptez que votre code soit distribué sous la [licence MIT](LICENSE.md).
